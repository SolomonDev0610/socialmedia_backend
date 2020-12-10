<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Clients extends Model
{
    protected $table = "clients";
    public $timestamps = false;
    public static function add_client($request){

        $clients = new Clients();
        $clients->email = $request['email'];
        $clients->image = $request['image'];
        $clients->first_name = $request['first_name'];
        $clients->last_name = $request['last_name'];
        $clients->phone_number = $request['phone_number'];
        $clients->birthday = $request['birthday'];
        $clients->address = $request['address'];
        $clients->timezone = $request['timezone'];
        $clients->job_title = $request['job_title'];
        $clients->company_name = $request['company_name'];
        $clients->tags = $request['tags'];
        $clients->private_notes = $request['private_notes'];
        $clients->link = $request['link'];

        $clients->save();
        return ['success' => true];
    }

    public static function update_client($request){
        $clients = Clients::find($request['id']);

        $clients->email = $request['email'];
        $clients->image = $request['image'];
        $clients->first_name = $request['first_name'];
        $clients->last_name = $request['last_name'];
        $clients->phone_number = $request['phone_number'];
        $clients->birthday = $request['birthday'];
        $clients->address = $request['address'];
        $clients->timezone = $request['timezone'];
        $clients->job_title = $request['job_title'];
        $clients->company_name = $request['company_name'];
        $clients->tags = $request['tags'];
        $clients->private_notes = $request['private_notes'];
        $clients->link = $request['link'];

        $clients->save();
        return ['success' => true];
    }
    public static function delete_client( $id ){
        self::where('id', $id)->delete();
        return true;
    }
}
