<?php

use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('number')->nullable();
            $table->integer('position')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_groups');
    }
};
