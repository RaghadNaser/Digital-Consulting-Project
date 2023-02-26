<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavouriteController extends Controller
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
        $data = Favourite::where([
            ['user_id', Auth::user()->id]
            , ['expert_id', $request['expert_id']
            ]])->get();
       $array = $data->toArray();
        if (!empty($array)) {
            return Response()->json(['message' => 'Error!'], 200);
        }
            $time = Favourite::create(
                [
                    'expert_id' => $request['expert_id'],
                    'user_id' => Auth::user()->id,
                ]
            );
            $time->save();
            return Response()->json(['message' => 'successfully!'], 200);

        }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
//        $user = Auth::user()->id;
//        $favourite = Favourite::where('user_id',$user)->get();
//        $obj = $favourite->experts();
//        return Response()->json(['Favourite Expert'=>$obj]);

        $favourites = DB::table('favourites')
//                ->select('articles.id as articles_id', ..... )
            ->join('experts', 'favourites.expert_id', '=', 'experts.id')
            ->join('users', 'favourites.user_id', '=', 'users.id')
            ->select('experts.name')
            ->get();
        return Response()->json(['Favourite Expert'=>$favourites]);
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
