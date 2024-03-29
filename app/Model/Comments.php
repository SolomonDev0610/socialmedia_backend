<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Comments extends Model
{
    protected $table = "comments";
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'political_party_id', 'depth', 'parent_id','post_id','point','comment','like_count', 'dislike_count', 'created_at', 'join_post_id'
    ];
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function post() {
        return $this->belongsTo('App\Model\Posts', 'post_id');
    }
}
