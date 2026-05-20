<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('outsmart_id')->nullable()->unique()->after('id');
            $table->string('outsmart_debtor_number')->nullable()->after('outsmart_id');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['outsmart_id']);
            $table->dropColumn(['outsmart_id', 'outsmart_debtor_number']);
        });
    }
};
