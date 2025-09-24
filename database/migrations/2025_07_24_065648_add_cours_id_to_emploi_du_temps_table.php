<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            $table->foreignId('cours_id')->nullable()->constrained('cours')->onDelete('cascade')->after('classe_id');
        });
    }

    public function down(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            $table->dropForeign(['cours_id']);
            $table->dropColumn('cours_id');
        });
    }
};
