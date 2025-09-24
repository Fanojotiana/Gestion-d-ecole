<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNiveauxTable extends Migration
{
    public function up()
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->json('classes_possibles')->nullable();

            $table->timestamps();
        });

        DB::table('niveaux')->insert([
            ['nom' => '6e'],
            ['nom' => '5e'],
            ['nom' => '4e'],
            ['nom' => '3e'],
            ['nom' => 'Seconde'],
            ['nom' => 'Première'],
            ['nom' => 'Terminale'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('niveaux');
    }
}
