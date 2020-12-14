<?php

namespace App\Http\Controllers;

use Faker\Guesser\Name;
use Illuminate\Http\Request;
use App\User;
use App\Model\Staffs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use phpDocumentor\Reflection\Types\Null_;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;

class CertificateController extends Controller
{
    public  $okstr = array("0" => ["message" =>"Succeed"]);
    public  $errorstr = array("0" => ["message" =>"Error"]);
    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = auth()->user();
        return response()->json(compact('token', 'user'));
    }
    public function register(Request $request)
    {
        $user = User::create([
            'username' => $request->username,
            'political_party' => $request->political_party,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
}
