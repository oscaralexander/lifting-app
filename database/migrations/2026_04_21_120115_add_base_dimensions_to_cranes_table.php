<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cranes', function (Blueprint $table) {
            $table->integer('base_length')->nullable()->after('base_crane_track_length');
            $table->integer('base_width')->nullable()->after('base_length');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cranes', function (Blueprint $table) {
            $table->dropColumn(['base_length', 'base_width']);
        });
    }
};
