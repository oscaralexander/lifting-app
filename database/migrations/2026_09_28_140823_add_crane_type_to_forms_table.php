<?php

use App\Enums\InspectionObject\Crane\Type as CraneType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->enum('crane_type', array_column(CraneType::cases(), 'value'))->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('crane_type');
        });
    }
};
