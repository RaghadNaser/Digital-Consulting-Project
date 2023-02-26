<?php

namespace App\Http\Controllers\API\Expert;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response()->json(['experts'=>Expert::all()]);
    }
    public function myProfile()
    {
        $user = Auth::user()->id;
        $profile = Expert::where('id',$user)->with('consultations')->select('name','email','phone','img','address','experiences')->get();
        return response()->json($profile,200);

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeConsultaion(Request $request)
    {
        $expert = Auth::user();
        $consultations = $request->input();
        foreach($consultations as $x) {
            $expert->consultations()->syncWithoutDetaching($x);
        }
        return Response()->json(['message'=>'successfully!'],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */


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
              //  'password'=> 'string|max:50|min:8',
              //  'phone' => 'digits_between:8,19',
                'address' => 'string',
                'experiences' => 'string',
                'img' => 'image'
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
            $expert = Expert::find(Auth::user()->id);
            if($request->hasFile('img'))
            {
                // Create new filename
                $filenameToStore = time() . '.' .$request->img->extension();
                $request->img->move(public_path('images'),$filenameToStore);
                $imgUrl = URL::asset('images/'.$filenameToStore);
            }
            $expert->forceFill($request->all());
            $expert->save();
            $expert = Expert::find(Auth::user()->id);

            return Response()->json([
                'expert' => $expert,
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
