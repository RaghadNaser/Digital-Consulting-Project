<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
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
        $expert_id = $request->input('expert_id');
        $time = $request->input('time_id');
        $consultation_id = $request->input('consultation_id');
        $appointmentable_id = Auth::user()->id;
        $start = $request->input('start');
        $end = $request->input('end');
        //story the name of the mode;
        $appointmentable_type =Auth::getDefaultDriver();
        if ($appointmentable_type =='expert-api'){
            $appointmentable_type ='Expert';
        }
        else {
            $appointmentable_type = 'User';
        }

        //check  expert-consaltation
        $expert = Expert::where('id',"$expert_id")->get()->first();
        $expertconsultation =  $expert->consultations()->get();
        $bool = false;
        foreach($expertconsultation as $x){
            if($x->id == $consultation_id)
                $bool = true;
        }
        if($bool ==false)
            return  response()->json(['message'=>'Error this consultation is not found']);

        //get the cost of consultation
        $consultionCost = Consultation::select('cost')->where('id',$consultation_id)->get();
        //get customer(user or expert) balance
        if ($appointmentable_type =='User'){
            $customerBalance = User::select('balance')->where('id',"$appointmentable_id")->get();
        }
        elseif ($appointmentable_type == 'Expert'){
            $customerBalance = Expert::select('balance')->where('id',"$appointmentable_id ")->get();
        }

        //decrese customer balance
        if($customerBalance[0]['balance']<$consultionCost[0]['cost']){
            return  response()->json([
                'message'=>'Error there is not enough balance your balance is : '.$customerBalance[0]['balance'].
                    ' The cost of the consultation is : '.$consultionCost[0]['cost']
            ]);
        }

        else{
            $appointment = Appointment::create(
                [
                    'expert_id'=>$request['expert_id'],
                    'time_id'=>$request['time_id'],
                    'consultation_id'=>$request['consultation_id'],
                    'appointmentable_id' => Auth::user()->id,
                    'appointmentable_type'=>$appointmentable_type,
                    'start' => $request['start'],
                    'end' => $request['end'],
                ]
            );
            $appointment->save();
            //save thenew customer balance:
            $newcustomerBalance = $customerBalance[0]['balance']-$consultionCost[0]['cost'];
            if ($appointmentable_type =='User'){
                $user = User::where('id', $appointmentable_id )->get()->first();
                $user->forceFill(['balance' =>$newcustomerBalance]);
                $user->save();
            }
            elseif ($appointmentable_type == 'Expert'){
                $expert = Expert::where('id', $appointmentable_id )->get()->first();
                $expert->forceFill(['balance' =>$newcustomerBalance]);
                $expert->save();
            }


            //get expert balance
            $expertBalance = Expert::select('balance')->where('id',"$expert_id")->get();
            //increse expert balance
            $newExoertBalance  = $expertBalance[0]['balance']+$consultionCost[0]['cost'];
            $expert = Expert::where('id', $expert_id)->get()->first();
            $expert->forceFill(['balance' => $newExoertBalance ]);
            $expert->save();
            return   response()->json(['meesage'=>'successfully'],200);
        }




//        $consultation_id = $request->input('consultation_id');
//        $appointmentable_id = Auth::user()->id;
//            //$request->input('user_id');
//        $expert_id = $request->input('expert_id');
//        $appointmentable_type = $request->input('costumer');
//
//        $data = Validator::make(
//            $request->all(),
//            [
//                'start' => 'required|date_format:H:i:s',
//                'end' => 'required|date_format:H:i:s',
//                'time_id' => 'integer',
//                'balance' => 'min:0',
//            ]
//        );
//        if($data->fails())
//        {
//            return Response()->json(['error'=>$data->errors()]);
//        }
//
//
////        $cost = Consultation::select()->where('id',$consultation_id)->get();
////        $findOrFail =
//        //check  expert-consaltation
//        $expert = Expert::where('id',$expert_id)->get()->first();
//        $expertConsultation =  $expert->consultations()->get();
//        $bool = false;
//        foreach($expertConsultation as $value){
//            if($value->id == $consultation_id)
//                $bool = true;
//        }
//        if($bool ==false)
//            return  response()->json(['message'=>'Error this consultation is not found']);
//        //get the cost of consultation
//        $consultionCost = Consultation::select('cost')->where('id',$consultation_id)->get();
//
//        //get user balance
//        $userBalance = User::select('balance')->where('id',$appointmentable_id)->get();
//
//        //decrese user balance
//        if($userBalance[0]['balance']<$consultionCost[0]['cost'])
//        {
//            return  response()->json(['message'=>'Error there is not enough balance your balance is : '.$userBalance[0]['balance'].' The cost of the consultation is : '.$consultionCost[0]['cost']]);
//        }
//        else {
//            $time = Appointment::create(
//                [
//                    'start' => $request['start'],
//                    'end' => $request['end'],
//                    'time_id' => $request['time_id'],
//                    'consultation_id' => $request['consultation_id'],
//                    'expert_id' => $request['expert_id'],
//                    'appointmentable_id' => Auth::user()->id,
//                    'appointmentable_type' => $request['costumer'],
//                ]
//            );
//            $time->save();
//            $newUserBalance = $userBalance[0]['balance'] - $consultionCost[0]['cost'];
//            $user = User::where('id', $appointmentable_id)->get()->first();
//            $user->forceFill(['balance' => $newUserBalance]);
//            $user->save();
//
//            //get expert balance
//            $expertBalance = Expert::select('balance')->where('id', "$expert_id")->get();
//
//            //increse expert balance
//            $newExoertBalance = $expertBalance[0]['balance'] + $consultionCost[0]['cost'];
//            $expert = Expert::where('id', $expert_id)->get()->first();
//            $expert->forceFill(['balance' => $newExoertBalance]);
//            $expert->save();
//            return Response()->json(['message'=>'successfully!'],200);
//        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $expert = Auth::user()->id;
        $appointment = Appointment::where('expert_id',$expert)->with('time')->get();
        return Response()->json(['Appointment'=>$appointment]);

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
