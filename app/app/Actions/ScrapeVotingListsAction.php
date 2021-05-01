<?php

namespace App\Actions;

use App\Enums\VotePositionEnum;
use App\Member;
use App\Term;
use App\VotingList;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ScrapeVotingListsAction extends Action
{
    private $scrapeAction;
    private $compileAction;

    public function __construct(
        ScrapeAction $scrapeAction,
        CompileStatsAction $compileAction
    ) {
        $this->scrapeAction = $scrapeAction;
        $this->compileAction = $compileAction;
    }

    public function execute(Term $term, Carbon $date): void
    {
        $response = $this->scrapeAction->execute('voting_lists', [
            'term' => $term->number,
            'date' => $date->toDateString(),
        ]);

        // Preload a list of all active members on the day
        $members = $this->buildMembersLookup($date);

        $total = count($response);

        foreach ($response as $key => $data) {
            $current = $key + 1;

            $this->log("Importing vote {$current} of {$total}", [
                'doceo_vote_id' => $data['doceo_vote_id'],
            ]);

            $votingList = $this->createOrUpdateVotingList($members, $term, $date, $data);
            $this->createOrUpdateVotings($members, $date, $votingList, $data['votings']);
            $this->compileAction->execute($votingList);
        }

        $this->log("Imported {$total} votes for {$date}");
    }

    protected function createOrUpdateVotingList(
        Collection $members,
        Term $term,
        Carbon $date,
        array $data
    ): VotingList {
        $votingList = VotingList::firstOrNew([
            'doceo_vote_id' => $data['doceo_vote_id'],
            'term_id' => $term->id,
            'date' => $date,
        ]);

        $votingList->fill([
            'description' => $data['description'],
            'reference' => $data['reference'],
        ]);

        $votingList->save();

        return $votingList;
    }

    protected function createOrUpdateVotings(
        Collection $members,
        Carbon $date,
        VotingList $votingList,
        array $votings
    ): void {
        $memberIds = [];

        foreach ($votings as $voting) {
            // To reduce response size, votings are encoded
            // as two-element arrays
            $name = $voting[0];
            $position = VotePositionEnum::make($voting[1]);

            $member = $this->findMember($members, $date, $name);

            $memberIds[$member->id] = [
                'position' => $position,
            ];
        }

        $votingList->members()->detach();
        $votingList->members()->attach($memberIds);
    }

    protected function findMember(Collection $members, Carbon $date, string $name): Member
    {
        $name = Member::normalizeName($name);

        // The official vote results provided by the parliament
        // only contain member's last names. Only if the last name
        // is ambiguous (i.e. there are two members with the same
        // last name active in parliament at the same time), the
        // respective first and last names are listed.
        $member = $members->first(function ($member) use ($name) {
            return $member->last_name_normalized == $name;
        });

        if ($member) {
            return $member;
        }

        return $members->first(function ($member) use ($name) {
            $fullName = "{$member->last_name_normalized} {$member->first_name_normalized}";

            return $fullName == $name;
        });
    }

    protected function buildMembersLookup(Carbon $date): Collection
    {
        $this->log('Building members lookup');

        return Member::activeAt($date)
            ->select('id', 'first_name_normalized', 'last_name_normalized')
            ->get();
    }
}
