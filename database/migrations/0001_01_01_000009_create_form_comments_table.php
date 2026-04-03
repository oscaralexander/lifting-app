<?php

use App\Models\FieldGroup;
use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FieldGroup::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Form::class)->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->integer('position')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_comments');
    }
};
