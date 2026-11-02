<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->boolean('is_approved_after_written_deregistration')->default(false)->after('requires_written_deregistration');
            $table->text('written_deregistration_description')->nullable()->after('is_approved_after_written_deregistration');
            $table->timestamp('written_deregistered_at')->nullable()->after('written_deregistration_description');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'is_approved_after_written_deregistration',
                'written_deregistration_description',
                'written_deregistered_at',
            ]);
        });
    }
};
