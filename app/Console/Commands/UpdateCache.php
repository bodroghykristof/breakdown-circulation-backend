<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;


/**
 * Class UpdateCache
 * @package App\Console\Commands
 */
class UpdateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all cocktail data from third party API';

    private $BASE_COCKTAIL_API_URL = 'https://www.thecocktaildb.com/api/json/v1/1/search.php?f=';
    private $startAsciiIndex = 97;
    private $lastAsciiIndex = 122;

    private $BASE_INGREDIENT_API_URL = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?iid=';
    private $startIngredientIndex = 1;
    private $endIngredientIndex = 614;


    /**
     * UpdateCache constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $redis = app()->make('redis');
        $redis->set("time", date("H:i:s"));
        $allCocktails = $this->getAllCocktails();
        $redis->set("cocktails", $allCocktails);
        $allIngredients = $this->getAllIngredients();
        $redis->set("ingredients", $allIngredients);
    }

    /**
     * @return array|false|string
     */
    private function getAllCocktails()
    {
        $allCocktails = array();

        for($i = $this->startAsciiIndex; $i <= $this->lastAsciiIndex; $i++) {
            $url = $this->BASE_COCKTAIL_API_URL.chr($i);
            $cocktails = $this->getContentFromURL($url)["drinks"];
            if ($cocktails !== null) {
                foreach($cocktails as $cocktail) {
                    array_push($allCocktails, $cocktail);
                }
            }
        }
        return json_encode(array("cocktails" => $allCocktails, "time" => date("Y-m-d H:i:s")));
    }

    private function getAllIngredients()
    {
        $allIngredients = array();

        for ($i = $this->startIngredientIndex; $i <= $this->endIngredientIndex; $i++) {
            $url = $this->BASE_INGREDIENT_API_URL.$i;
            $ingredients = $this->getContentFromURL($url)['ingredients'];
            if ($ingredients !== null) {
                foreach ($ingredients as $ingredient) {
                    array_push($allIngredients, $ingredient);
                }
            }
        }
        return json_encode(array("ingredients" => $allIngredients, "time" => date("Y-m-d H:i:s")));
    }

    /**
     * @param string $url
     * @return array
     */
    private function getContentFromURL(string $url) : array
    {
        $cocktailsByLetter = file_get_contents($url);
        return json_decode($cocktailsByLetter, true);
    }

}
