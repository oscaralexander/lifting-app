<?php

namespace App\Models;

use App\Enums\FieldType;
use App\Enums\InspectionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Inspection extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'comment_data' => 'json:unicode',
        'form_data' => 'json:unicode',
        'has_cat_a_deficiencies' => 'boolean',
        'has_cat_b_deficiencies' => 'boolean',
        'has_no_sticker_provided' => 'boolean',
        'image_data' => 'json:unicode',
        'images' => 'json:unicode',
        'matrix' => 'json',
        'meta_data' => 'json:unicode',
        'requires_reinspection' => 'boolean',
        'requires_written_deregistration' => 'boolean',
        'type' => InspectionType::class,
    ];

    protected $with = [
        'form',
        'form.fields',
        'form.fieldGroups.fields',
        'form.fieldGroups.formComments',
        'form.formComments',
        'user',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inspection) {
            $inspection->hash = self::getUniqueHash();
        });
    }

    public function getAnswerForField(int $pivotId, $default = null)
    {
        $answer = $this->answers->firstWhere('field.pivot.id', $pivotId);

        if ($answer) {
            if ($answer->get('field')->type === FieldType::TOGGLE) {
                return $answer->get('answer') === true;
            }

            return $answer->get('answer');
        }

        return $default;
    }

    public function getRouteKeyName(): string
    {
        return 'hash';
    }

    public static function getUniqueHash(int $length = 5): string
    {
        $hash = Str::random($length);

        while (self::where('hash', $hash)->exists()) {
            $hash = Str::random($length);
        }

        return $hash;
    }

    /**
     * Attributes
     */
    public function answers(): Attribute
    {
        $answers = collect();

        if (! empty($this->form_data)) {
            foreach ($this->form_data as $key => $answer) {
                $pivotId = Str::of($key)->afterLast('_')->toInteger();
                $field = $this->form->fields->firstWhere('pivot.id', $pivotId);

                if ($field) {
                    if ($field->type === FieldType::SELECT_MULTIPLE) {
                        $keys = array_keys(explode("\n", $field->values));
                        $selected = explode(',', $answer);
                        $answer = array_map(function ($key) use ($selected) {
                            return in_array($key, $selected);
                        }, $keys);
                    }

                    /*
                    if ($field->type === FieldType::DOCUMENT || $field->type === FieldType::IMAGE) {
                        $answer = explode(',', $answer);
                    }
                    */

                    $answers[] = collect([
                        'answer' => $answer,
                        'field' => $field,
                    ]);
                }
            }
        }

        return new Attribute(
            get: fn (): Collection => $answers,
        );
    }

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

    public function inspectionObject(): BelongsTo
    {
        return $this->belongsTo(InspectionObject::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
