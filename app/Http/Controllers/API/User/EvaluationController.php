<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $data = Validator::make(
            $request->all(),
            [
                'rating' => 'integer|between:1,5',
            ]
        );
        if($data->fails())
        {
            return Response()->json(['error'=>$data->errors()]);
        }
        $expert_id = $request->input('expert_id');
        $evaluationable_id  = Auth::user()->id;
        $evaluationable_type = Auth::getDefaultDriver();
        $rating = $request->input('rating');
        //story the name of the mode;
        $evaluationable_type =Auth::getDefaultDriver();
        if ($evaluationable_type =='expert-api'){
            $evaluationable_type ='Expert';
        }
        else {
            $evaluationable_type = 'User';
        }
        //check if  this rating is already exist
        $data = Evaluation::where([
            ['evaluationable_id', Auth::user()->id]
            ,['evaluationable_type',$evaluationable_type]
            , ['expert_id', $request['expert_id']
            ]])->get();
        $array = $data->toArray();
        if (!empty($array)) {
            return Response()->json(['message' => 'Error!'],405);
        }

        $rate = Evaluation::create(
            [
                'rating' => $request['rating'],
                'expert_id' => $request['expert_id'],
                'evaluationable_type'=>$evaluationable_type,
                'evaluationable_id' =>$evaluationable_id,

            ]
        );
        $rate->save();
        //return $rate;
        return Response()->json(['message' => 'successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
                'rating' => 'integer|between:1,5',
            ]
        );
        if($data->fails())
        {
            return Response()->json(['error'=>$data->errors()]);
        }
        $user = Evaluation::find(Auth::user()->id);
        $user->forceFill($request->all());
        $user->save();
        $user = Evaluation::find(Auth::user()->id);
        return Response()->json([
            'User' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return float|int
     */
    public function retrievingExpertRating(Request $request)
    {
        $expert_id = $request->input('expert_id');
        $experts = Expert::select('id')->where('id',$expert_id)->with('evaluations')->get();
        $arrayexperts = $experts->toArray();
        $array = $arrayexperts[0]['evaluations'];
        //return $array;
        $count =0;
        $sum=0;
        foreach ($array as $expert){
            $sum = $sum +$array[$count]['rating'];
            $count++;
        }

        return $sum/$count;
    }
    public function destroy($id)
    {
        //
    }
}
