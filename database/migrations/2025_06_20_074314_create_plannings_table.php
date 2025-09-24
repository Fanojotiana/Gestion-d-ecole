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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->string('classe');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->unsignedBigInteger('responsable_id');
            $table->timestamps();

            $table->foreign('responsable_id')->references('id')->on('responsables')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
