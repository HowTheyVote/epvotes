<?php

namespace App;

use App\Enums\VoteResultEnum;
use App\Enums\VoteTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
/*
 * A Vote takes place during one session. A Vote has exactly one
 * VotingList that matches with it.
 *
 * VotingList and Votes hold different information. VotingLists hold
 * the information about actual voting behaviors, while the Vote
 * contains further information, most importantly whether the subject
 * of the vote was adopted or rejected. Thus, both are necessary and
 * need to be matched. See also the comment in the VoteCollection model.
 */
{
    use HasFactory;

    protected $fillable = [
        'author',
        'subject',
        'type',
        'amendment',
        'result',
        'split_part',
        'vote_collection_id',
        'formatted',
        'remarks',
        'reference',
        'subheading',
        'session_id',
        'final',
        'unmatched',
        'notes',

    ];

    protected $casts = [
        'result' => VoteResultEnum::class,
        'type' => VoteTypeEnum::class,
    ];

    public function voteCollection()
    {
        return $this->belongsTo(VoteCollection::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function votingList()
    {
        return $this->hasOne(VotingList::class);
    }

    public function summary()
    {
        return $this->voteCollection->summary();
    }

    public function getDisplayTitleAttribute()
    {
        $title = $this->voteCollection->title;
        $suffix = null;

        if ($this->isAmendmentVote()) {
            $suffix = "Amendment {$this->amendment}";
        }

        if ($this->isSeparateVote()) {
            $suffix = $this->split_part
                ? "{$this->subject}/{$this->split_part}"
                : $this->subject;
        }

        if ($suffix) {
            return "{$title} ({$suffix})";
        }

        return $title;
    }

    public function getSubtitleAttribute()
    {
        if ($this->isAmendmentVote()) {
            return __('votes.subtitle.amendment', [
                'amendment' => $this->amendment,
                'author' => $this->author,
            ]);
        }

        if ($this->isSeparateVote()) {
            $subject = $this->split_part
                ? "{$this->subject}/{$this->split_part}"
                : $this->subject;

            return __('votes.subtitle.separate', ['subject' => $subject]);
        }

        if ($this->isFinalVote()) {
            return __('votes.subtitle.final');
        }
    }

    public function relatedVotes()
    {
        return $this
            ->hasMany(self::class, 'vote_collection_id', 'vote_collection_id')
            ->where('id', '!=', $this->id);
    }

    public function finalVote()
    {
        return $this
            ->hasOne(self::class, 'vote_collection_id', 'vote_collection_id')
            ->ofMany(['id' => 'max'], function ($query) {
                return $query->final();
            });
    }

    public function scopeFinal($query)
    {
        return $query->where('final', 1);
    }

    public function scopeMatched($query)
    {
        return $query->whereHas('votingList');
    }

    public function isFinalVote()
    {
        return $this->final;
    }

    public function isPrimaryVote()
    {
        return VoteTypeEnum::PRIMARY()->equals($this->type);
    }

    public function isSeparateVote()
    {
        return VoteTypeEnum::SEPARATE()->equals($this->type);
    }

    public function isAmendmentVote()
    {
        return VoteTypeEnum::AMENDMENT()->equals($this->type);
    }

    public function hasRelatedVotes()
    {
        return $this->relatedVotes()->exists();
    }

    public function getUrlAttribute(): ?string
    {
        if (! $this->votingList) {
            return null;
        }

        return $this->votingList->url;
    }
}
