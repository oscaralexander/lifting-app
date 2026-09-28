<?php

namespace App\Enums;

use App\TestMatrices\EarthmoverMatrix;
use App\TestMatrices\LoaderCraneMatrix;
use App\TestMatrices\MobileCraneMatrix;
use App\TestMatrices\MobileTowerCraneMatrix;
use App\TestMatrices\TestMatrix;
use App\TestMatrices\TowerCraneMatrix;

enum TestMatrixType: string
{
    case EARTHMOVER = 'earthmover';
    case LOADER_CRANE = 'loader_crane';
    case MOBILE_CRANE = 'mobile_crane';
    case MOBILE_TOWER_CRANE = 'mobile_tower_crane';
    case TOWER_CRANE = 'tower_crane';

    public function matrix(): TestMatrix
    {
        return match ($this) {
            self::EARTHMOVER => new EarthmoverMatrix,
            self::LOADER_CRANE => new LoaderCraneMatrix,
            self::MOBILE_CRANE => new MobileCraneMatrix,
            self::MOBILE_TOWER_CRANE => new MobileTowerCraneMatrix,
            self::TOWER_CRANE => new TowerCraneMatrix,
        };
    }
}
