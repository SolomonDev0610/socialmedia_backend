<?php

namespace App\Http\Controllers;

use App\Model\PoliticalParties;
use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\User;
use Illuminate\Support\Facades\Mail;

class PoliticalPartyController extends Controller
{
    public function createPoliticalParty(Request $request){
        $result = PoliticalParties::add_PoliticalParty($request);
        return response()->json(['data'=>$result]);
    }

    public function updatePoliticalParty(Request $request){
        $result = PoliticalParties::update_PoliticalParty($request);
        return response()->json(['data'=>$result]);
    }
    public function getPoliticalPartyList(Request $request){
        $result = PoliticalParties::all();
        return response()->json(['data'=>$result]);
    }
    public function getPoliticalPartyDetail(Request $request){
        $result = PoliticalParties::where('id', $request->id)->get();
        return response()->json(['data'=>$result]);
    }

    public function deletePoliticalParty(Request $request){
        $result = PoliticalParties::delete_PoliticalParty($request->id);
        return response()->json(['data'=>$result]);
    }
}
