<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CommentVoteUsers extends Model
{
    protected $table = "comment_vote_users";
    public $timestamps = false;
    protected $fillable = [
        'id', 'comment_id', 'user_id'
    ];
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function comment() {
        return $this->belongsTo('App\Model\Comments', 'comment_id');
    }
}
