<?php

use App\Enums\InspectionObject\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->enum('type', array_column(Type::cases(), 'value'))->change();
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->enum('type', ['crane', 'operator_lift'])->change();
        });
    }
};
