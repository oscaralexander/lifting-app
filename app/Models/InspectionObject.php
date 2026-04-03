<?php

namespace App\Models;

use App\Enums\InspectionObject\Type;
use App\Enums\CountryCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InspectionObject extends Model
{
    protected $casts = [
        'country' => CountryCode::class,
        'type' => Type::class,
    ];

    protected $guarded = ['id'];

    /**
     * Relationships
     */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inspectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
