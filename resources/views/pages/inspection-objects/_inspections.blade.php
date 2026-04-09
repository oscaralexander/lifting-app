<div class="u-stack u-stack-gap-l">
    <div class="u-flex u-flex-align-center u-flex-justify-between">
        <h2>@lang('inspection_objects.show.heading_inspections')</h2>
        <x-popout icon="plus" small :label="__('inspection_objects.show.btn_create_inspection')">
            @foreach ($this->forms as $form)
                <x-popout.item
                    :href="route('inspections.form', ['inspectionObjectId' => $this->inspectionObject->id, 'formSlug' => $form->slug])"
                    :label="$form->name"
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
                    <th scope="col">@lang('inspection_objects.show.col_project')</th>
                    <th scope="col">@lang('inspection_objects.show.col_date')</th>
                    <th scope="col">@lang('inspection_objects.show.col_signed')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->inspectionObject->inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->project_name ?? '—' }}</td>
                        <td>{{ $inspection->created_at->format('d-m-Y') }}</td>
                        <td>{{ $inspection->signed_at?->format('d-m-Y') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>