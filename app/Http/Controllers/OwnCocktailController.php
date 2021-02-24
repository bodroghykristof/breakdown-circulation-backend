<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\OwnCocktail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OwnCocktailController extends Controller
{
    //
    public function saveOwnCocktail(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "description" => "required",
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
}
