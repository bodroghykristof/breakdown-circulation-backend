<?php

namespace App\Http\Controllers;

class CocktailController extends Controller
{
    private $BASE_COCKTAIL_API_URL = 'https://www.thecocktaildb.com/api/json/v1/1/search.php?f=';
    private $startIndexAsc = 97;
    private $endIndexAsc = 122;

    /**
     * @return array|false|string
     */
    public function getAllCocktails()
    {
        $allCocktails = array();
        for($i = $this->startIndexAsc; $i <= $this->endIndexAsc; $i++) {
            $cocktails = $this->getCocktailsByLetter($i)["drinks"];
            if ($cocktails !== null) {
                foreach($cocktails as $cocktail) {
                    array_push($allCocktails, $cocktail);
                }
            }
        }
        return json_encode(array("cocktails" => $allCocktails, "time" => date("H:i:s")));
    }

    /**
     * @param int $letterIndex
     * @return array
     */
    private function getCocktailsByLetter(int $letterIndex) : array
    {
        $cocktailsByLetter = file_get_contents($this->BASE_COCKTAIL_API_URL.chr($letterIndex));
        return json_decode($cocktailsByLetter, true);
    }
}
