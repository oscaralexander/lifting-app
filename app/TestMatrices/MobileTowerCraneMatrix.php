<?php

namespace App\TestMatrices;

use App\Enums\TestMatrixType;
use Illuminate\Validation\Rule;

class MobileTowerCraneMatrix extends TestMatrix
{
    public function type(): TestMatrixType
    {
        return TestMatrixType::MOBILE_TOWER_CRANE;
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        return [
            'setup', // 1. Opstelling
            'boom_length', // 2. Gieklengte
            'hook_height', // 3. Haakhoogte
            'counterweight', // 4. Eigen gewicht contraballast
            'hoist_rope_falls', // 5. Aantal parten hijskabel
            'slewing_angle', // 6. Zwenkhoek
            'lmb_code', // 7. LMB code / gang
            'test_load', // 8. Proeflast
            'permissible_radius', // 9. Toelaatbare vlucht bij proeflast
            'lmb_trolley_out_at', // 10. Katten uit treedt in werking bij
            'lmb_hoist_up_at', // 11. Hijsen uit treedt in werking bij
            'lmb_permissible_load', // 12. Toelaatbare bedrijfslast bij kolom 11
            'lb_triggered_at', // 14. LB treedt in werking
            'lb_permissible_load', // 15. Toelaatbare bedrijfslast bij kolom 14
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

    protected function slug(): string
    {
        return 'mtc';
    }
}
