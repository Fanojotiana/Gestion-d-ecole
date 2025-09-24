<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnseignantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->string('grade')->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->string('photo')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
}
