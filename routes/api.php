<?php

use App\Http\Controllers\CocktailController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\OwnCocktailController;
use App\Http\Controllers\OwnIngredientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/get-all-cocktails", [CocktailController::class, "getAllCocktails"]);
//Route::get("/get-all-ingredients", [CocktailController::class, "getAllIngredients"]);
Route::get("/get-all-ingredients", [OwnIngredientController::class, "getAllIngredients"]);

Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);
Route::group(["middleware" => ["auth:sanctum"]], function() {
    Route::post("/logout", [UserController::class, "logout"]);
    Route::apiResource("/favourite", FavouriteController::class);
    Route::post("/save-own-cocktail", [OwnCocktailController::class, "saveOwnCocktail"]);
    Route::get("/get-own-cocktails", [OwnCocktailController::class, "getOwnCocktails"]);
    Route::get("/get-ingredient-data/{id}", [OwnIngredientController::class, "getIngredientData"]);
});
