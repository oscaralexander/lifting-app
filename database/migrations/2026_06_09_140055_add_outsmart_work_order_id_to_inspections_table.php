<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('outsmart_work_order_id')->nullable()->index()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropIndex(['outsmart_work_order_id']);
            $table->dropColumn('outsmart_work_order_id');
        });
    }
};
