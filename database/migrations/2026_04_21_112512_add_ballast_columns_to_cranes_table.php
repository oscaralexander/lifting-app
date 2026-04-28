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
            $table->integer('central_ballast')->nullable()->after('boom_luffing_angle');
            $table->integer('counter_ballast')->nullable()->after('central_ballast');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cranes', function (Blueprint $table) {
            $table->dropColumn(['central_ballast', 'counter_ballast']);
        });
    }
};
