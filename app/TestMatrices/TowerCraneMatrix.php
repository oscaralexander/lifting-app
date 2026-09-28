<?php

namespace App\TestMatrices;

use App\Enums\TestMatrixType;

class TowerCraneMatrix extends TestMatrix
{
    public function type(): TestMatrixType
    {
        return TestMatrixType::TOWER_CRANE;
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        return [
            'hoist_rope_falls', // 1. Aantal parten hijskabel
            'lmb_code', // 2. LMB code / gang
            'test_load', // 3. Proeflast
            'permissible_radius', // 4. Toelaatbare vlucht bij proeflast
            'lmb_trolley_out_at', // 5. Katten uit treedt in werking bij
            'lmb_hoist_up_at', // 6. Hijsen uit treedt in werking bij
            'lmb_permissible_load', // 7. Toelaatbare bedrijfslast bij kolom 5
            'lb_triggered_at', // 9. LB treedt in werking
            'lb_permissible_load', // 10. Toelaatbare bedrijfslast bij kolom 9
        ];
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
}
