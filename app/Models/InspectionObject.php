<?php

namespace App\Models;

use App\Enums\InspectionObject\Type;
use App\Enums\InspectionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionObject extends Model
{
    use SoftDeletes;

    public const PER_PAGE = 25;

    protected $casts = [
        'next_inspection_date' => 'date',
        'type' => Type::class,
        'year_manufacture' => 'integer',
    ];

    protected $guarded = ['id'];

    /**
     * Select the date this object is due for its next inspection: the most
     * recent inspection date plus the validity period of that inspection's
     * type. Expressed in SQL so the result stays sortable and filterable.
     */
    public function scopeWithNextInspectionDate(Builder $query): Builder
    {
        $interval = collect(InspectionType::cases())
            ->map(fn (InspectionType $type) => "WHEN '{$type->value}' THEN {$type->intervalMonths()}")
            ->implode(' ');

        return $query->addSelect([
            'next_inspection_date' => Inspection::query()
                ->selectRaw("DATE_ADD(inspection_date, INTERVAL (CASE type {$interval} ELSE ? END) MONTH)", [
                    InspectionType::PERIODICAL->intervalMonths(),
                ])
                ->whereColumn('inspection_object_id', 'inspection_objects.id')
                ->whereNotNull('inspection_date')
                ->orderByDesc('inspection_date')
                ->limit(1),
        ]);
    }

    /**
     * Limit to objects that have been inspected at least once, and so have a
     * next inspection date to report on.
     */
    public function scopeWhereInspected(Builder $query): Builder
    {
        return $query->whereHas('inspections', fn (Builder $query) => $query->whereNotNull('inspection_date'));
    }

    /**
     * Attributes
     */
    public function name(): Attribute
    {
        return new Attribute(
            get: fn () => collect([$this->manufacturer, $this->model])->filter()->implode(' '),
        );
    }

    /**
     * Relationships
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function latestInspection(): HasOne
    {
        return $this->hasOne(Inspection::class)
            ->whereNotNull('inspection_date')
            ->latestOfMany('inspection_date');
    }
}
