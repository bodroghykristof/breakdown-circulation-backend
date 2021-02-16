<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        return response()->json(DB::table('favourites')
            ->where('user_id', '=', $userId)
            ->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                "idDrink" => "required|int",
                "strDrink" => "required",
                "strDrinkThumb" => "required"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "validation_errors" => $validator->errors()
            ], 400);
        }

        $userId = $request->user()->id;
        $favourite = $request->all();
        $favourite['user_id'] = $userId;
        DB::table('favourites')->insert(
            $favourite
        );

        return response()->json([
            "token" => "Favourite added successfully"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function show(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favourite $favourite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $idDrink)
    {
        $userId = $request->user()->id;
        $favourite = DB::table('favourites')
            ->where('idDrink', '=', $idDrink)
            ->where('user_id', '=', $userId);

        $favouriteItem = $favourite->first();

        if ($favouriteItem == null) {
            return response()->json([
                "token" => "Favourite was not found"
            ], 400);
        }

        $favourite->delete();
        return response()->json([
            "token" => "Favourite deleted successfully"
        ], 200);
    }
}
