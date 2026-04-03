<?php

use App\Enums\CountryCode;
use App\Enums\InspectionObject\Type;
use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspection_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->nullable()->constrained()->nullOnDelete();
            $table->enum('type', array_column(Type::cases(), 'value'))->nullable();
            $table->unsignedBigInteger('inspectable_id')->nullable();
            $table->string('inspectable_type')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->enum('country', array_column(CountryCode::cases(), 'value'))->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspection_objects');
    }
};