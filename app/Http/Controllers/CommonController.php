<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\Mail\ContactUs;

class CommonController extends Controller
{
    public function uploadPhoto(Request $request){
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
            $image_urls = url('/')."/img/".$filename;
//            $image_urls = url('/')."/img/".$filename;
            if (!file_exists($file_path) || !is_readable($file_path))
                return response()->json(['data'=>['success' => false]]);
            else
                return response()->json(['data'=>
                    ['success' => true,
                     'file_path' => $image_urls]
                ]);
        }
    }
    public function getUserInfo(Request $request){
        $result = User::find($request['userid']);
        return response()->json(['data'=>$result]);
    }
    public function emailVerify(Request $request){
        Mail::to($request["email"])->send(new EmailVerify($request["email"], $request['confirm_code']));
        return response()->json(['data'=>1]);
    }
    public function sendContactMessage(Request $request){
        Mail::to("powersource0160@outlook.com")->send(new ContactUs(
            $request["sender_email"],
            $request['fullname'],
            $request['subject'],
            $request['sender_message']
        ));
        return response()->json(['data'=>1]);
    }
}
