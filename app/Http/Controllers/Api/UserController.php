<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'Basarili ile kayit olundu', 201]);   
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if(Auth::attempt($request->only('email','password'))){
            $user=User::where('email',$request->email)->first();
            $token=$user->createToken('auth_token')->plainTextToken;
            
            return response()->json(['token'=>$token],200);
        }
        return response()->json(['message'=>'Unauthorized'],401);
    }
    public function sendResetLinkEmail(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
        ]);
        if($validator->failed()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $response=Password::sendResetLink(
            $request->only('email')
        ); 
        return $response==Password::RESET_LINK_SENT
                    ? response()->json(['message'=>__($response)])
                    : response()->json(['message'=>__($response)],500);

    }
    public function resetPassword(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed|min:8'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $response=Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user,$password)
            {
                $user->forceFill([
                    'password'=>bcrypt($password),
                    'remember_token'=>Str::random(60)
                ])->save();
            }
        ); 
        return $response==Password::PASSWORD_RESET
                    ?response()->json(['message'=>__($response)])
                    :response()->json(['message'=>__($response)],500);
    }
}
