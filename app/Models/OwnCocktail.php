<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnCocktail extends Model
{
    use HasFactory;

    protected $fillable = [
        'strDrink',
        'strInstructions',
        'user_id',
    ];
}
