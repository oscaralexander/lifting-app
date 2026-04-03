<?php

use App\Models\InspectionObject;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(InspectionObject::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Form::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('hash', 5)->unique()->collation('utf8mb4_bin')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_address')->nullable();
            $table->string('project_postal_code')->nullable();
            $table->string('project_city')->nullable();
            $table->string('project_country')->nullable();
            $table->json('form_data')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspections');
    }
};