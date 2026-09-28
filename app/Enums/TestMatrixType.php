<?php

namespace App\Enums;

use App\TestMatrices\MobileCraneMatrix;
use App\TestMatrices\TestMatrix;
use App\TestMatrices\TowerCraneMatrix;

enum TestMatrixType: string
{
    case MOBILE_CRANE = 'mobile_crane';
    case TOWER_CRANE = 'tower_crane';

    public function matrix(): TestMatrix
    {
        return match ($this) {
            self::MOBILE_CRANE => new MobileCraneMatrix,
            self::TOWER_CRANE => new TowerCraneMatrix,
        };
    }
}
