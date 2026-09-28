<?php

namespace App\Enums;

use App\TestMatrices\MobileCraneMatrix;
use App\TestMatrices\MobileTowerCraneMatrix;
use App\TestMatrices\TestMatrix;
use App\TestMatrices\TowerCraneMatrix;

enum TestMatrixType: string
{
    case MOBILE_CRANE = 'mobile_crane';
    case MOBILE_TOWER_CRANE = 'mobile_tower_crane';
    case TOWER_CRANE = 'tower_crane';

    public function matrix(): TestMatrix
    {
        return match ($this) {
            self::MOBILE_CRANE => new MobileCraneMatrix,
            self::MOBILE_TOWER_CRANE => new MobileTowerCraneMatrix,
            self::TOWER_CRANE => new TowerCraneMatrix,
        };
    }
}
