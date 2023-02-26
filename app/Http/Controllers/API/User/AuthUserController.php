<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//use Validator;

class AuthUserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required',
            'email' =>'required|email|unique:users',
            'password' =>'required|min:8',
        ]);

        if ($validator->fails()) {
           // return $this->sendError('Please validate error' ,$validator->errors() );
            return response()->json(['error' => $validator->errors()->all()]);

        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('keyToken@~#',['user'])->accessToken;
        $success['name'] = $user->name;

       // return $this->sendResponse($success ,'User registered successfully' );
        return response()->json($success, 200);

    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('keyToken@~#',['user'])->accessToken;

            return response()->json($success, 200);
        }else{
            return response()->json(['error' => ['Email and Password are Wrong.']], 401);
        }
    }

    public function userLogout(Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            "success" => "1",
            "message" => "logged out successfully"
        ],200);
    }
}
