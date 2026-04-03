<?php

namespace App\Models;

use App\Enums\InspectionObject\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Form extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Form $form) {
            $baseSlug = Str::slug($form->name);
            $slug = $baseSlug;
            $i = 1;

            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }

            $form->slug = $slug;
        });
    }

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => Type::class,
        ];
    }

    protected $with = [
        'fields',
        'fieldGroups.fields',
        'formComments',
        'fieldGroups.formComments',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getNextPosition(): int
    {
        $max = DB::table('field_form')->where('form_id', $this->id)->max('position');
        $max = max($max, DB::table('field_groups')->where('form_id', $this->id)->max('position'));
        $max = max($max, DB::table('form_comments')->where('form_id', $this->id)->max('position'));

        return $max + 1;
    }

    /**
     * Relationships
     */

    public function fieldGroups(): HasMany
    {
        return $this->hasMany(FieldGroup::class)->orderBy('position');
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class)
            ->withPivot('id', 'field_group_id', 'form_id', 'position', 'required')
            ->orderBy('position');
    }

    public function formComments(): HasMany
    {
        return $this->hasMany(FormComment::class)->orderBy('position');
    }
}