<?php

use App\Models\Field;
use App\Models\FieldGroup;
use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('field_form', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Field::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(FieldGroup::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Form::class)->constrained()->cascadeOnDelete();
            $table->integer('position')->default(1);
            $table->boolean('required')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_form');
    }
};