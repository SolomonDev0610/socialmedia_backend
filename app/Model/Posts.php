<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Posts extends Model
{
    protected $table = "posts";
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'political_party_id', 'title', 'contents', 'images','keywords','comment_count', 'point1', 'point2', 'total_point', 'created_at'
    ];
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function comments(){
        return $this->hasMany('App\Model\Comments', 'post_id');
    }
}
