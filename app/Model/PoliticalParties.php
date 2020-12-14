<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PoliticalParties extends Model
{
    protected $table = "political_parties";
    public $timestamps = false;
    protected $fillable = [
        'id', 'name',
    ];
    public static function add_PoliticalParty($request){

        $party = new PoliticalParties();
        $party->name = $request['name'];

        $party->save();
        return ['success' => true, 'id' => $party->id];
    }
    public static function update_PoliticalParty($request){
        $party = PoliticalParties::find($request['id']);
        $party->name = $request['name'];

        $party->save();
        return ['success' => true];
    }
    public static function delete_PoliticalParty( $id ){
        self::where('id', $id)->delete();
        return true;
    }
}
