<?php

namespace App\Actions;

use App\Exceptions\ScrapingException;
use App\Member;
use App\Term;

class ScrapeMembersAction extends Action
{
    private $scrapeAction;

    public function __construct(ScrapeAction $scrapeAction)
    {
        $this->scrapeAction = $scrapeAction;
    }

    public function execute(Term $term): void
    {
        try {
            $response = $this->scrapeAction->execute('members', [
                'term' => $term->number,
            ]);
        } catch (ScrapingException $exception) {
            report($exception);

            return;
        }

        $total = count($response);

        foreach ($response as $key => $data) {
            $current = $key + 1;
            $this->log("Importing member {$current} of {$total}", $data);

            $member = Member::firstOrCreate([
                'web_id' => $data['web_id'],
            ]);

            $member->terms()->syncWithoutDetaching($term);
        }
    }
}
