<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response()->json(['experts'=>Expert::select('name','phone','address','experiences')->get(),
        ]);
    }

    public function myProfile()
    {
        $user = Auth::user()->id;
        $profile = User::where('id',$user)->select('name','email','phone','img','address')->get();
        return response()->json($profile,200);

    }
    public function RetrievingExpertConsultation()
    {
        $expert = Expert::with('consultations')->select('name','experiences','id')->get();
        return response()->json($expert,200);
    }

    public function searchConsultation($consultation)
    {
        $consultation = Consultation::
        where('name', 'like', '%'.$consultation.'%')->with('experts')->select('name','id')->get();
        return Response()->json(['Experts'=>$consultation]);
    }


    public function searchName($name)
    {
        $expert = Expert::
        where('name', 'like', '%'.$name.'%')->with('consultations')->select('name','id')->get();
        if($expert->isEmpty())
        {
            return Response()->json(['Message'=>'Not Found']);

        }
        return Response()->json(['Experts'=>$expert]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $expert = Expert::find($id);
        if($expert == null)
            return Response()->json(['error'=>'Expert not found']);
        return Response()->json(['expert'=>$expert]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = Validator::make(
            $request->all(),
            [
                'name' => 'string|max:255',
                'balance' => 'min:1',
               // 'phone' => 'digits_between:8,19',
                'img' => 'image',
                'address' => 'string',
            ]
        );

        if($data->fails())
        {
            return Response()->json(['error'=>$data->errors()]);
        }
        if($request==null)
        {
            return Response()->json([
                'error'=>'Error U should update thing',
            ]);
        }
        else {
            $user = User::find(Auth::user()->id);
            if($request->hasFile('img'))
            {
                // Create new filename
                $filenameToStore = time() . '.' .$request->img->extension();
                $request->img->move(public_path('images'),$filenameToStore);
                $imgUrl = URL::asset('images/'.$filenameToStore);
            }
            $user->forceFill($request->all());
            $user->save();
            $user = User::find(Auth::user()->id);

            return Response()->json([
                'User' => $user,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
