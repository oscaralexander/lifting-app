<?php

namespace App\Models;

use App\Enums\CountryCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    public const PER_PAGE = 25;

    protected $casts = [
        'country' => CountryCode::class,
    ];

    protected $guarded = ['id'];

    public function getOutsmartUrlAttribute(): ?string
    {
        if (! $this->outsmart_id) {
            return null;
        }

        return "https://app.out-smart.com/next/crm/relations/{$this->outsmart_id}/";
    }

    /**
     * Relationships
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
