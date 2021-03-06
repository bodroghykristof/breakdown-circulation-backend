<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OwnIngredient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('own_ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('idIngredient');
            $table->string('strIngredient');
            $table->longText('strDescription')->nullable();
            $table->string('strType')->nullable();
            $table->string('strABV')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('own_ingredients');

    }
}
