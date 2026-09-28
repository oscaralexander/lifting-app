<?php

namespace App\Enums;

use App\TestMatrices\TestMatrix;
use App\TestMatrices\TowerCraneMatrix;

enum TestMatrixType: string
{
    case TOWER_CRANE = 'tower_crane';

    public function matrix(): TestMatrix
    {
        return match ($this) {
            self::TOWER_CRANE => new TowerCraneMatrix,
        };
    }
}
