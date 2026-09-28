<div class="matrix u-stack u-stack-gap-m">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    @include($this->testMatrix->formView())
    @if (count($rows) < $this->testMatrix->maxRows())
        <div>
            <x-btn icon="plus" small wire:click="addRow">@lang('inspections.form.matrix_add_row')</x-btn>
        </div>
    @endif
</div>
