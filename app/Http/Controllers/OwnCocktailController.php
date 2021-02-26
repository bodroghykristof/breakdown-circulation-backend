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
            $ingredients = $ownCocktailData['ingredients'];

            foreach ($ingredients as $ingredient) {
                $ingredient['own_cocktail_id'] = $ownCocktailID;
                $ingredient['user_id'] = Auth::id();
                $savedIngredient = Ingredient::query()->create($ingredient);
                error_log($savedIngredient);
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
        return response()->json(DB::table('own_cocktails')
            ->select(
                DB::raw('group_concat(distinct ingredients.strIngredient) as ingredients'),
                'own_cocktails.id',
                'own_cocktails.strDrink',
                'own_cocktails.strInstructions',
                'own_cocktails.user_id')
            ->join('ingredients', 'own_cocktails.id', '=', 'ingredients.own_cocktail_id')
            ->where('own_cocktails.user_id', '=', $userId)
            ->groupBy( 'own_cocktails.id', 'own_cocktails.strDrink', 'own_cocktails.strInstructions', 'own_cocktails.user_id')
            ->get(), 200);

    }
}
