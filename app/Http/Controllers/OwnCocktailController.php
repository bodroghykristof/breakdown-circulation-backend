<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\OwnCocktail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OwnCocktailController extends Controller
{
    public function saveOwnCocktail(Request $request): JsonResponse
    {
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
//            error_log($ownCocktailID);
            $ingredients = $ownCocktailData['ingredients'];

            foreach ($ingredients as $ingredient) {
                $new_ingredient['own_cocktail_id'] = $ownCocktailID;
                $new_ingredient['own_ingredient_id'] = $ingredient;
                $new_ingredient['user_id'] = Auth::id();
                $savedIngredient = Ingredient::query()->create($new_ingredient);
                if (is_null($savedIngredient)) {
                    return response()->json([
                        "success" => false,
                        "message" => "Whoops! Failed to save in db"], 400);
                }
            }
            return response()->json([
                "success" => true,
                "data" => $ownCocktailID], 200);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getOwnCocktails(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $own_cocktails = DB::select('SELECT strDrink,strInstructions,own_cocktails.id,
                 GROUP_CONCAT(oi.strIngredient) AS ingredients FROM own_cocktails
                join ingredients i on own_cocktails.id = i.own_cocktail_id
                join own_ingredients oi on i.own_ingredient_id = oi.id
                where own_cocktails.user_id = ?
                group by own_cocktails.id, own_cocktails.strDrink, own_cocktails.strInstructions;', [$userId]);

        return response()->json($own_cocktails, 200);

    }
}
