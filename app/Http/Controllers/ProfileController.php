<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = auth()->user();
//        $staff = Staffs::with('user')->where('userid', $user->id)->get();
        return response()->json(['data'=>$user]);
    }
    public function getAdminProfile(Request $request)
    {
        $user = auth()->user();
        $staff = Staffs::with('user')->where('userid', $user->id)->get();
        return response()->json(['data'=>$staff]);
    }
    public function updateProfile(Request $request){
        $user = auth()->user();
        $result = User::update_profile($request, $user->id);
        return response()->json(['data'=>$result]);
    }
    public function changeProfilePhoto(Request $request){
        $file = $request->file('photoUpload');
        if($file) {
            $size = $file->getSize();
            if ($size > 5000000) return false;
            $ext = $file->getClientOriginalExtension();
            $filename = date('mdYhia').".".$ext;
            $file->move(public_path() . '/img', $filename);

            //---- import RTC file -------
            //reading payment_history.csv file
            $file_path = public_path(). "/img/".$filename;
//            $image_urls = url('/')."/public/img/".$filename;
            $image_urls = url('/')."/img/".$filename;
            if (!file_exists($file_path) || !is_readable($file_path))
                return response()->json(['data'=>['success' => false]]);
            else{
                $user = auth()->user();
                DB::table('users')->where('id', $user->id)
                    ->update(['image' => $image_urls]);

                return response()->json(['data'=>
                    ['success' => true,
                        'file_path' => $image_urls]
                ]);
            }

        }
    }
    public function savePassword(Request $request){
        $user = auth()->user();

        DB::table('users')->where('id', $user->id)
            ->update(['password' => Hash::make($request->get('password'))]);
        return response()->json(['data'=>'success']);
    }
}
