<?php

use App\Enums\InspectionStatus;
use App\Enums\InspectionType;
use App\Models\Inspection;
use App\Models\InspectionObject;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public const LIST_LIMIT = 8;

    /**
     * Objects that are due for inspection, soonest (and most overdue) first.
     */
    #[Computed]
    public function dueInspectionObjects(): Collection
    {
        return InspectionObject::query()
            ->withNextInspectionDate()
            ->whereInspected()
            ->with('latestInspection.client')
            ->orderBy('next_inspection_date')
            ->limit(self::LIST_LIMIT)
            ->get();
    }

    #[Computed]
    public function recentInspections(): Collection
    {
        return Inspection::query()
            ->with('inspectionObject', 'form', 'client')
            ->where('is_completed', true)
            ->whereNotNull('inspection_date')
            ->orderByDesc('inspection_date')
            ->orderByDesc('id')
            ->limit(self::LIST_LIMIT)
            ->get();
    }

    public function render()
    {
        return $this->view()
            ->title(__('dashboard.title'));
    }
}
?>

<div>
    <x-header :title="__('dashboard.title')" :intro="__('dashboard.intro')" />
    <div class="u-stack u-stack-gap-xl">
        <div class="grid grid--gap-xl">
            <div class="grid__col l:grid__col--span-6">
                <div class="u-stack u-stack-gap-l">
                    <div class="u-flex u-flex-align-center u-flex-justify-between">
                        <h2>@lang('dashboard.recent.title')</h2>
                        <x-btn :href="route('inspections')" small>@lang('dashboard.recent.view_all')</x-btn>
                    </div>
                    @if ($this->recentInspections->isEmpty())
                        <table class="table table--border">
                            <tbody>
                                <tr>
                                    <td class="table__empty">@lang('dashboard.recent.empty')</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table table--border">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('dashboard.recent.col_project')</th>
                                    <th scope="col">@lang('dashboard.recent.col_date')</th>
                                    <th class="table__num" scope="col">@lang('dashboard.recent.col_status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->recentInspections as $inspection)
                                    <tr wire:key="recent-{{ $inspection->id }}">
                                        <td>
                                            <a
                                                class="table__main"
                                                href="{{ route('inspections.form', [
                                                    'inspectionObjectId' => $inspection->inspection_object_id,
                                                    'formSlug' => $inspection->form->slug,
                                                    'inspectionHash' => $inspection->hash,
                                                ]) }}"
                                                wire:navigate
                                            >{{ $inspection->project_name ?: __('dashboard.recent.unnamed') }}</a>
                                            @if ($inspection->client)
                                                <div class="u-text-lc u-text-s">{{ $inspection->client->name }}</div>
                                            @endif
                                        </td>
                                        <td><span class="u-text-nowrap">{{ $inspection->inspection_date->translatedFormat('d M Y') }}</span></td>
                                        <td class="table__num">
                                            <span
                                                @class([
                                                    'label',
                                                    'label--danger' => in_array($inspection->status, [InspectionStatus::REJECTED, InspectionStatus::CAT_A_DEFICIENCIES, InspectionStatus::CAT_B_DEFICIENCIES]),
                                                    'label--success' => $inspection->status === InspectionStatus::APPROVED,
                                                ])
                                            >{{ $inspection->status->label() }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="grid__col l:grid__col--span-6">
                <div class="u-stack u-stack-gap-l">
                    <div class="u-flex u-flex-align-center u-flex-justify-between">
                        <h2>@lang('dashboard.upcoming.title')</h2>
                        <x-btn :href="route('inspection-objects')" small>@lang('dashboard.upcoming.view_all')</x-btn>
                    </div>
                    @if ($this->dueInspectionObjects->isEmpty())
                        <table class="table table--border">
                            <tbody>
                                <tr>
                                    <td class="table__empty">@lang('dashboard.upcoming.empty')</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table table--border">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('dashboard.upcoming.col_object')</th>
                                    <th scope="col">@lang('dashboard.upcoming.col_due')</th>
                                    <th scope="col">@lang('dashboard.upcoming.col_type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->dueInspectionObjects as $inspectionObject)
                                    <tr wire:key="due-{{ $inspectionObject->id }}">
                                        <td>
                                            <a
                                                class="table__main"
                                                href="{{ route('inspection-objects.show', $inspectionObject) }}"
                                                wire:navigate
                                            >{{ $inspectionObject->name ?: __('inspection_objects.show.unnamed') }}</a>
                                            @if ($inspectionObject->latestInspection?->client)
                                                <div class="u-text-lc u-text-s">{{ $inspectionObject->latestInspection->client->name }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $daysUntilDue = (int) today()->diffInDays($inspectionObject->next_inspection_date);
                                            @endphp
                                            @if ($daysUntilDue < 0)
                                                <span class="label label--danger">{{ $inspectionObject->next_inspection_date->translatedFormat('d M Y') }}</span>
                                            @else
                                                <span class="u-text-nowrap">{{ $inspectionObject->next_inspection_date->translatedFormat('d M Y') }}</span>
                                            @endif
                                            <div @class(['u-text-s', 'u-text-lc' => $daysUntilDue >= 0, 'u-text-danger' => $daysUntilDue < 0])>
                                                @if ($daysUntilDue < 0)
                                                    {{ trans_choice('dashboard.upcoming.days_overdue', abs($daysUntilDue)) }}
                                                @else
                                                    {{ trans_choice('dashboard.upcoming.days_until', $daysUntilDue) }}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($inspectionObject->latestInspection->type === InspectionType::TCVT)
                                                <img alt="TCVT" class="table__logo" height="24" width="56" src="/assets/img/tcvt.svg" />
                                            @else
                                                {{ $inspectionObject->latestInspection->type?->label() }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
