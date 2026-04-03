<?php

namespace App\Livewire\Forms;

use App\Enums\CountryCode;
use App\Enums\FieldType;
use App\Models\Form;
use App\Models\Inspection;
use Livewire\Form as LivewireForm;

class InspectionForm extends LivewireForm
{
    public array $comments = [];

    public array $fields = [];

    public $project_address;

    public $project_city;

    public CountryCode $project_country = CountryCode::NL;

    public $project_name;

    public $project_postal_code;

    public function init(Inspection $inspection, Form $form): void
    {
        if ($inspection->exists) {
            $this->project_name = $inspection->project_name;
            $this->project_address = $inspection->project_address;
            $this->project_postal_code = $inspection->project_postal_code;
            $this->project_city = $inspection->project_city;
            $this->project_country = CountryCode::tryFrom($inspection->project_country) ?? CountryCode::NL;
        }

        foreach ($form->fields as $field) {
            $key = 'field_' . $field->pivot->id;

            $this->comments[$key] = $inspection->exists
                ? ($inspection->comment_data[$key] ?? null)
                : null;

            if ($field->type === FieldType::SELECT_MULTIPLE) {
                $this->fields[$key] = $inspection->exists
                    ? $inspection->getAnswerForField($field->pivot->id, [])
                    : [];
            } else {
                $this->fields[$key] = $inspection->exists
                    ? ($inspection->form_data[$key] ?? null)
                    : null;
            }
        }
    }
}
