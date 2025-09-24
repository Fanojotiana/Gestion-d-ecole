<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();

            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
