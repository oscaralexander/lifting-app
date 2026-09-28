<?php

namespace App\TestMatrices;

use App\Enums\TestMatrixType;
use Illuminate\Validation\Rule;

class LoaderCraneMatrix extends TestMatrix
{
    public function type(): TestMatrixType
    {
        return TestMatrixType::LOADER_CRANE;
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        return [
            'setup', // 1. Opstelling
            'main_boom_length', // 2. Hoofd / knikgiek (m)
            'knuckle_boom_extension', // 3. Mech. deel knikgiek (in/uit)
            'fly_jib_length', // 4. Fly jib (m)
            'fly_jib_extension', // 5. Mech. deel fly-jib (in/uit)
            'main_boom_angle', // 6. Hoofd / knikgiek (gr)
            'fly_jib_angle', // 7. Fly jib (gr)
            'hoist_rope_falls', // 8. Aantal parten hijskabel
            'slewing_angle', // 9. Zwenkhoek
            'counterweight', // 10. Massa contraballast / schoteldruk
            'test_load', // 11. Proeflast
            'permissible_radius', // 12. Toelaatbare vlucht bij proeflast
            'lmb_triggered_at', // 13. LMB treedt in werking bij
            'lmb_permissible_load', // 14. Toelaatbare bedrijfslast bij kolom 13
            'lmb_max_deviation', // 15. Max. toelaatbare afwijking (%)
            'lb_triggered_at', // 17. LB treedt in werking
            'lb_permissible_load', // 18. Toelaatbare bedrijfslast bij kolom 17
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function blankRow(): array
    {
        return array_merge(parent::blankRow(), ['slewing_angle' => 'R']);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'rows.*.setup' => ['nullable', Rule::in(self::SETUP_OPTIONS)],
        ]);
    }

    /**
     * @param  array<string, string|null>  $row
     * @return array{lmb: float|null, lb: float|null}
     */
    public function deviations(array $row): array
    {
        return [
            'lmb' => self::deviation($row['lmb_permissible_load'] ?? null, $row['test_load'] ?? null),
            'lb' => self::deviation($row['lb_permissible_load'] ?? null, $row['lb_triggered_at'] ?? null),
        ];
    }

    /**
     * The LMB deviation is limited by the maximum entered in column 15.
     *
     * @param  array<string, string|null>  $row
     * @return array{lmb: float|null, lb: float}
     */
    public function maxDeviations(array $row): array
    {
        return [
            'lmb' => self::parseNumber($row['lmb_max_deviation'] ?? null),
            'lb' => static::MAX_DEVIATION,
        ];
    }

    /**
     * Column 15 is an inclusive maximum (Δ ≤ …), so reaching it still passes.
     */
    protected function isWithinLimit(string $key, float $deviation, float $maxDeviation): bool
    {
        return $key === 'lmb'
            ? $deviation <= $maxDeviation
            : parent::isWithinLimit($key, $deviation, $maxDeviation);
    }

    protected function slug(): string
    {
        return 'lc';
    }
}
