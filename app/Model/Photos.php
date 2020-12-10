<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Photos extends Model
{
    protected $table = "photos";
    public $timestamps = false;
}
