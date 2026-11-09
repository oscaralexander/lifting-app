<?php

namespace App\Lib;

use App\Models\Inspection;

class InspectionResult
{
    /**
     * The findings of an inspection as shown in the report: the approval, or
     * the rejection together with the deficiencies and follow-up required.
     */
    public static function html(Inspection $inspection): string
    {
        return match (true) {
            $inspection->is_approved && $inspection->is_approved_after_written_deregistration => '<strong>'.e(__('models/inspection.is_approved_after_written_deregistration.label')).'</strong><br>'
                .e(__('models/inspection.written_deregistered_at.label')).': '.e($inspection->written_deregistered_at?->translatedFormat('j F Y') ?? '—'),
            $inspection->is_approved => __('enums/inspection_status.approved'),
            default => collect([
                __('enums/inspection_status.rejected'),
                $inspection->has_cat_a_deficiencies ? __('models/inspection.has_cat_a_deficiencies.label') : null,
                $inspection->has_cat_b_deficiencies ? __('models/inspection.has_cat_b_deficiencies.label') : null,
                $inspection->requires_reinspection ? __('models/inspection.requires_reinspection.label') : null,
                $inspection->requires_written_deregistration ? __('models/inspection.requires_written_deregistration.label') : null,
            ])->filter()->map(fn (string $line): string => e($line))->implode('<br>'),
        };
    }
}
