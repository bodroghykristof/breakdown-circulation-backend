<?php

namespace App\Http\Controllers;

class CocktailController extends Controller
{
    public function getAllCocktails() {
        $redis = app()->make('redis');
        return $redis->get("cocktails");
    }

    public function getAllIngredients() {
        $redis = app()->make('redis');
        return $redis->get("ingredients");
    }
}
