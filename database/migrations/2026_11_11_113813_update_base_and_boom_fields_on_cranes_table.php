<?php

use App\Enums\InspectionObject\Crane\BaseConfiguration;
use App\Models\InspectionObjects\Crane;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $oldBaseConfigurations = ['cross_frame', 'undercarriage', 'rail_travelling', 'recoverable_anchors', 'foundation_anchors'];

    public function up(): void
    {
        $newBaseConfigurations = array_column(BaseConfiguration::cases(), 'value');

        Schema::table('cranes', function (Blueprint $table) use ($newBaseConfigurations) {
            $table->enum('base_configuration', array_values(array_unique([...$this->oldBaseConfigurations, ...$newBaseConfigurations])))->nullable()->change();
        });

        Crane::query()
            ->whereIn('base_configuration', ['recoverable_anchors', 'foundation_anchors'])
            ->update(['base_configuration' => BaseConfiguration::ANCHORS->value]);

        Schema::table('cranes', function (Blueprint $table) use ($newBaseConfigurations) {
            $table->enum('base_configuration', $newBaseConfigurations)->nullable()->change();
            $table->dropColumn(['exchangeable_parts', 'outrigger_type']);
        });
    }

    public function down(): void
    {
        $newBaseConfigurations = array_column(BaseConfiguration::cases(), 'value');

        Schema::table('cranes', function (Blueprint $table) use ($newBaseConfigurations) {
            $table->string('exchangeable_parts')->nullable()->after('counter_ballast');
            $table->enum('outrigger_type', ['outrigger', 'dozer_blade'])->nullable()->after('base_configuration');
            $table->enum('base_configuration', array_values(array_unique([...$this->oldBaseConfigurations, ...$newBaseConfigurations])))->nullable()->change();
        });

        Crane::query()
            ->where('base_configuration', BaseConfiguration::ANCHORS->value)
            ->update(['base_configuration' => 'recoverable_anchors']);

        Crane::query()
            ->whereNotIn('base_configuration', $this->oldBaseConfigurations)
            ->update(['base_configuration' => null]);

        Schema::table('cranes', function (Blueprint $table) {
            $table->enum('base_configuration', $this->oldBaseConfigurations)->nullable()->change();
        });
    }
};
