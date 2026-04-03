<?php

use App\Enums\InspectionObject\Crane\BallastConfiguration;
use App\Enums\InspectionObject\Crane\BoomConfiguration;
use App\Enums\InspectionObject\Crane\BoomType;
use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Enums\InspectionObject\Crane\OutriggerType;
use App\Enums\InspectionObject\Crane\Type;
use App\Enums\InspectionObject\Crane\Undercarriage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cranes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(Type::cases(), 'value'))->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_number')->nullable();
            $table->integer('year_manufacture')->nullable();
            $table->integer('hook_height')->nullable();
            $table->string('exchangeable_parts')->nullable();
            $table->enum('undercarriage', array_column(Undercarriage::cases(), 'value'))->nullable();
            $table->enum('base_configuration', array_column(BaseConfiguration::cases(), 'value'))->nullable();
            $table->enum('outrigger_type', array_column(OutriggerType::cases(), 'value'))->nullable();
            
            // Undercarriage/Base
            $table->string('base_manufacturer')->nullable();
            $table->string('base_model')->nullable();
            $table->string('base_serial_number')->nullable();
            $table->string('base_asset_number')->nullable();
            $table->integer('base_rail_track_gauge')->nullable();
            $table->integer('base_rail_wheelbase')->nullable();
            $table->integer('base_crane_track_length')->nullable();

            // Boom/jib
            $table->enum('boom_type', array_column(BoomType::cases(), 'value'))->nullable();
            $table->boolean('boom_is_adjustable')->default(0);
            $table->boolean('boom_is_luffing')->default(0);
            $table->boolean('boom_is_trolley')->default(0);
            $table->integer('boom_length')->nullable();
            $table->integer('boom_parts')->nullable();
            $table->integer('boom_luffing_angle')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cranes');
    }
};