<?php

namespace App\TestMatrices;

use App\Enums\TestMatrixType;
use Illuminate\Validation\Rule;

class EarthmoverMatrix extends TestMatrix
{
    public function type(): TestMatrixType
    {
        return TestMatrixType::EARTHMOVER;
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        return [
            'setup', // 1. Opstelling
            'boom_length', // 2. Mono- / knikgiek (m)
            'dipper_arm_length', // 3. Lepelsteel (m)
            'attachment_number', // 4. Aanbouwdeel (nr)
            'attachment_extension', // 5. Aanbouwdeel (in/uit)
            'boom_angle', // 6. Hoofd / knikgiek (gr)
            'attachment_angle', // 7. Aanbouwdeel (gr)
            'hoist_rope_falls', // 8. Aantal parten hijskabel
            'slewing_angle', // 9. Zwenkhoek
            'counterweight', // 10. Massa contraballast
            'test_load', // 11. Proeflast
            'permissible_radius', // 12. Toelaatbare vlucht bij proeflast
            'lmb_triggered_at', // 13. LMB treedt in werking bij
            'lmb_permissible_load', // 14. Toelaatbare bedrijfslast bij kolom 13
            'lb_triggered_at', // 16. LB treedt in werking
            'lb_permissible_load', // 17. Toelaatbare bedrijfslast bij kolom 16
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
        return 'em';
    }
}
