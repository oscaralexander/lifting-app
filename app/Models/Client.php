<?php

namespace App\Models;

use App\Enums\CountryCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $casts = [
        'country' => CountryCode::class,
    ];

    protected $guarded = ['id'];

    /**
     * Relationships
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
