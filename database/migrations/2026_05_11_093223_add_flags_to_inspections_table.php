<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->boolean('has_cat_a_deficiencies')->default(false)->after('signed_at');
            $table->boolean('has_cat_b_deficiencies')->default(false)->after('has_cat_a_deficiencies');
            $table->boolean('requires_reinspection')->default(false)->after('has_cat_b_deficiencies');
            $table->boolean('requires_written_deregistration')->default(false)->after('requires_reinspection');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'has_cat_a_deficiencies',
                'has_cat_b_deficiencies',
                'requires_reinspection',
                'requires_written_deregistration',
            ]);
        });
    }
};
