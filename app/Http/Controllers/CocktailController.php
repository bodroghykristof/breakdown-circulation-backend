<?php

namespace App\Http\Controllers;

class CocktailController extends Controller
{
    public function getAll() {
        $redis = app()->make('redis');
        return $redis->get("cocktails");
    }

}
