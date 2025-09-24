<?php
// database/migrations/xxxx_xx_xx_create_classes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->unique(['nom', 'niveau_id']); // évite "6e A" plusieurs fois pour le même niveau
            $table->foreignId('niveau_id')->constrained('niveaux')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
