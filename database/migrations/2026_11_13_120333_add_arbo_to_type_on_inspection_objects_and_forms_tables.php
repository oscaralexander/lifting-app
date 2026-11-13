<?php

use App\Enums\InspectionObject\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->enum('type', array_column(Type::cases(), 'value'))->nullable()->change();
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->enum('type', array_column(Type::cases(), 'value'))->change();
        });
    }

    public function down(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->enum('type', ['crane', 'operator_lift', 'attachment'])->nullable()->change();
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->enum('type', ['crane', 'operator_lift', 'attachment'])->change();
        });
    }
};
