<?php

use App\Enums\FieldType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->enum('type', array_column(FieldType::cases(), 'value'))->change();
        });
    }

    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->enum('type', ['text', 'textarea', 'number', 'toggle', 'select', 'select_multiple'])->change();
        });
    }
};
