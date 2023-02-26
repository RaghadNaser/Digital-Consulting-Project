<?php

namespace App\Http\Controllers\API\Expert;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthExpertController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required',
            'email' =>'required|email|unique:experts',
            'password' =>'required|min:8',
           // 'phone' => 'digits_between:8,19',
            'address' => 'string',
            'img' => 'image',
        ]);

        if ($validator->fails()) {
            // return $this->sendError('Please validate error' ,$validator->errors() );
            return response()->json(['error' => $validator->errors()->all()],401);

        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if ($request->hasFile('img')) {
            $filenameToStore = time() . '.' .$request->img->extension();
            $request->img->move(public_path('images'),$filenameToStore);
            $input['imgUrl'] = URL::asset('images/'.$filenameToStore);
          }


        $expert = Expert::create($input);
        $expert = Expert::find($expert->id);
        $expert['token'] = $expert->createToken('keyToken@~#',['expert'])->accessToken;
        return response()->json($expert, 200);
    }

    public function expertLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8|max:50',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }


        if(auth()->guard('expert')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'expert']);

            $expert = Expert::select('experts.*')->find(auth()->guard('expert')->user()->id);
            $success =  $expert;
            $success['token'] =  $expert->createToken('keyToken@~#',['expert'])->accessToken;
            return response()->json($success, 200);
        }else{
            return response()->json(['error' => ['Email and Password are Wrong.']], 401);
        }
    }

    public function expertLogout(Request $request) : \Illuminate\Http\JsonResponse
    {
        // $token = $request->user->token;
        $request->user()->token()->revoke();
        // $token->revoke();

        return response()->json([
            "success" => "1",
            "message" => "logged out successfully"
        ],200);
    }
}
