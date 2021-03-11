<?php

namespace Database\Seeders;

use App\Models\OwnIngredient;
use Illuminate\Database\Seeder;

class IngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = file_get_contents(__DIR__.'/../../resources/data/ingredients.json');
        foreach (json_decode($ingredients, true) as $key=>$ingredientsByKey) {
            foreach ($ingredientsByKey as $ingredient) {
                $data = [
                  'idIngredient' => $ingredient['idIngredient'],
                  'strIngredient' => $ingredient['strIngredient'],
                  'strDescription' => $ingredient['strDescription'],
                  'strType' => $key,
                  'strABV' => $ingredient['strABV'],
                ];

                OwnIngredient::query()->create($data);
            }
        }
    }
}
