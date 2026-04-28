<?php

use App\Enums\InspectionObject\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_objects', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(Type::cases(), 'value'))->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_number')->nullable();
            $table->integer('year_manufacture')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_objects');
    }
};
