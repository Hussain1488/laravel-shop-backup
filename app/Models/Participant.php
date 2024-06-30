<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function participantPost()
    {
        return $this->hasMany(ParticipantPosts::class, 'participant_id');
    }
}
