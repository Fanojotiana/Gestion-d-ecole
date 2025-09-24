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
        Schema::table('presences', function (Blueprint $table) {
            $table->unsignedBigInteger('emploi_du_temps_id')->after('id');
            $table->foreign('emploi_du_temps_id')->references('id')->on('emploi_du_temps')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign(['emploi_du_temps_id']);
            $table->dropColumn('emploi_du_temps_id');
        });
    }
};
