<div class="u-stack u-stack-gap-l">
    <div class="u-flex u-flex-align-center u-flex-justify-between">
        <h2>@lang('inspection_objects.show.heading_inspections')</h2>
        <x-popout icon="plus" small :label="__('inspection_objects.show.btn_create_inspection')">
            @foreach ($this->forms as $form)
                <x-popout.item
                    :label="$form->name"
                    wire:click="$dispatch('openModal', { component: 'inspections.start-inspection-modal', arguments: { formId: {{ $form->id }}, inspectionObjectId: {{ $this->inspectionObject->id }} } })"
                />
            @endforeach
        </x-popout>
    </div>
    @if ($this->inspectionObject->inspections->isEmpty())
        <table class="table table--border">
            <tbody>
                <td style="padding: 1rem; text-align: center;">@lang('inspection_objects.show.no_inspections')</td>
            </tbody>
        </table>
    @else
        <table class="table table--border">
            <thead>
                <tr>
                    <th scope="col">@lang('inspections.index.col_project_name')</th>
                    <th scope="col">@lang('inspections.index.col_date')</th>
                    <th scope="col">@lang('inspections.index.col_signed')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->inspectionObject->inspections as $inspection)
                    <tr>
                        <td>
                            <a
                                class="table__main"
                                href="{{ route('inspections.form', [
                                    'inspectionObjectId' => $inspection->inspection_object_id,
                                    'formSlug' => $inspection->form->slug,
                                    'inspectionHash' => $inspection->hash,
                                ]) }}"
                                wire:navigate
                            >
                                {{ $inspection->project_name ?? '—' }}
                            </a>
                        </td>
                        <td>{{ $inspection->created_at->translatedFormat('d M, Y') }}</td>
                        <td>{{ $inspection->signed_at?->translatedFormat('d M, Y') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>