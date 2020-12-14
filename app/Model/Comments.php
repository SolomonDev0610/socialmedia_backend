<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Comments extends Model
{
    protected $table = "comments";
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'political_party_id', 'level', 'parent_id', 'point','comment','like_count', 'dislike_count'
    ];
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
