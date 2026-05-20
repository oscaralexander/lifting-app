<?php

use App\Enums\CountryCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('country', array_column(CountryCode::cases(), 'value'))->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('country', ['be', 'nl', 'fr'])->nullable()->change();
        });
    }
};
