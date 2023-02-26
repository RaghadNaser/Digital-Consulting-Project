<?php

namespace App\Http\Controllers\API\Expert;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Expert;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AvailableTimeController extends Controller
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
                'start' => 'required|date_format:H:i:s',
                'end' => 'required|date_format:H:i:s',
                'day_id' => 'integer',
            ]
        );
        if($data->fails())
        {
            return Response()->json(['error'=>$data->errors()]);
        }
        $time = Time::create(
            [
                'start' => $request['start'],
                'end' => $request['end'],
                'day_id' => $request['day_id'],
                'expert_id' => Auth::user()->id,
            ]
        );
        $time->save();
        return Response()->json(['message'=>'successfully!'],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
         $expert_id = $request->query('expert_id');
         $time = Time::where('expert_id',$expert_id)->get();
        return Response()->json(['Available Time '=>$time],200);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
