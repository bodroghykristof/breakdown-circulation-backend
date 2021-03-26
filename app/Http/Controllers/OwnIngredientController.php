<?php

namespace App\Http\Controllers;

use App\Models\OwnIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnIngredientController extends Controller
{
    public function insert(array $data)
    {
        OwnIngredient::query()->create($data);
    }

    public function getAllIngredients()
    {
        return OwnIngredient::all();
    }

    public function getIngredientData(Request $request, $id)
    {

        error_log($id);
        $ingredient = DB::table('own_ingredients')->find($id);
        return response()->json([
            "ingredient" => $ingredient
        ], 200);
    }
}
