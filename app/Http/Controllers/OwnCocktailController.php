<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\OwnCocktail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OwnCocktailController extends Controller
{
    //
    public function saveOwnCocktail(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                "strDrink" => "required",
                "strInstructions" => "required",
                "ingredients" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad request",
                    "validation_errors" => $validator->errors()
                ], 400);
            }

            $ownCocktailData = $request->all();
            $ownCocktailData["user_id"] = Auth::id();

            $ownCocktail = OwnCocktail::query()->create($ownCocktailData);
            $ownCocktailID = $ownCocktail->id;
            $ingredients = $ownCocktailData['ingredients'];

            foreach ($ingredients as $ingredient) {
                $ingredient['own_cocktail_id'] = $ownCocktailID;
                $ingredient['user_id'] = Auth::id();
                $savedIngredient = Ingredient::query()->create($ingredient);
                if (is_null($savedIngredient)) {
                    return response()->json([
                        "success" => false,
                        "message" => "Whoops! Failed to register. Please try again."], 400);
                }
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getOwnCocktails(Request $request)
    {
        $userId = $request->user()->id;
        return response()->json(DB::table('own_cocktails')
            ->where('user_id', '=', $userId)
            ->get(), 200);
    }
}
