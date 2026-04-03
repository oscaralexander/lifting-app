<?php

namespace App\Models;

use App\Enums\CountryCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    protected $casts = [
        'country' => CountryCode::class,
    ];

    protected $guarded = ['id'];

    /**
     * Relationships
     */

    public function inspectionObjects(): HasMany
    {
        return $this->hasMany(InspectionObject::class);
    }

    public function inspections(): HasManyThrough
    {
        return $this->hasManyThrough(Inspection::class, InspectionObject::class);
    }
}
