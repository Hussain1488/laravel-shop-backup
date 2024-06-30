<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantPosts extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function link()
    {
        return route('front.posts.show', ['post' => $this]);
    }
    public function like()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function refreshLikesCount()
    {
        $this->update([
            'like_count'    => $this->like()->where('type', 'like')->count(),
            'dislike_count' => $this->like()->where('type', 'dislike')->count(),
        ]);
    }

    public function scopeCommentCount()
    {
        return $this->comments()->count();
    }
    public function scopeAcceptedCommentCount()
    {
        return $this->comments()->accepted()->count();
    }

    public function scopeAccepted($query)
    {
        return $query->where('state', 'valid');
    }
}
