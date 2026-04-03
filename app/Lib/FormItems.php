<?php

namespace App\Lib;

use App\Models\Form;
use Illuminate\Support\Collection;

class FormItems
{
    public static function get(Form $form): Collection
    {
        $items = collect();

        // Add field groups
        foreach ($form->fieldGroups->sortBy('position') as $fieldGroup) {
            $items->push([
                'fieldGroup' => $fieldGroup,
                'position' => $fieldGroup->position ?? 0,
                'type' => 'fieldGroup',
            ]);
        }

        // Add comments not in any group
        $formComments = $form->formComments->filter(function($formComment) {
            return is_null($formComment->field_group_id);
        })->sortBy('position');
    
        foreach ($formComments->sortBy('position') as $formComment) {
            $items->push([
                'formComment' => $formComment,
                'position' => $formComment->position ?? 0,
                'type' => 'formComment',
            ]);
        }
    
        // Add fields not in any group
        $groupedFieldPivotIds = $form->fieldGroups->flatMap(function($fieldGroup) {
            return $fieldGroup->fields->pluck('pivot.id');
        })->unique();
    
        foreach ($form->fields->sortBy('pivot.position') as $field) {
            if (!$groupedFieldPivotIds->contains($field->pivot->id)) {
                $items->push([
                    'field' => $field,
                    'position' => $field->pivot->position ?? $field->position ?? 0,
                    'type' => 'field',
                ]);
            }
        }
    
        // Order all items by position
        $items = $items->sortBy('position');
        return $items;
    }
}