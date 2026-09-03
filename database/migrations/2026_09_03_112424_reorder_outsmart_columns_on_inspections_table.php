<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('outsmart_external_reference')->nullable()->after('images')->change();
            $table->string('outsmart_order_number')->nullable()->after('outsmart_external_reference')->change();
            $table->json('outsmart_photos')->nullable()->after('outsmart_order_number')->change();
            $table->string('outsmart_work_order_id')->nullable()->after('outsmart_photos')->change();
            $table->string('inspector_name')->nullable()->after('inspection_date')->change();
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('outsmart_external_reference')->nullable()->after('id')->change();
            $table->string('outsmart_order_number')->nullable()->after('outsmart_external_reference')->change();
            $table->string('inspector_name')->nullable()->after('outsmart_order_number')->change();
            $table->json('outsmart_photos')->nullable()->after('inspector_name')->change();
            $table->string('outsmart_work_order_id')->nullable()->after('outsmart_photos')->change();
        });
    }
};
