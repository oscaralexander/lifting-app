<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('outsmart_order_number')->nullable()->after('id');
            $table->json('outsmart_photos')->nullable()->after('outsmart_order_number');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['outsmart_order_number', 'outsmart_photos']);
        });
    }
};
