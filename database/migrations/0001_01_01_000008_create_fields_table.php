<?php

use App\Enums\FieldType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->json('attrs')->nullable();
            $table->text('description')->nullable();
            $table->text('label');
            $table->string('number')->nullable();
            $table->enum('type', array_column(FieldType::cases(), 'value'));
            $table->text('values')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fields');
    }
};