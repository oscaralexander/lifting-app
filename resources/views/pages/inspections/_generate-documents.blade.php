{{-- Generate documents --}}
<div class="inspection__generateDocuments">
    <div class="u-stack u-stack-gap-xs">
        <h3>@lang('inspections.form.heading_generate_documents')</h3>
        <p class="formatted">@lang('inspections.form.text_generate_documents')</p>
    </div>
    <div class="actions">
        @if ($this->inspection->exists)
            @if ($this->inspection->is_completed)
                <x-btn
                    icon="file-check"
                    small
                    wire:click="generateReport"
                    wire:loading.attr="disabled"
                    wire:loading.class="is-loading"
                    wire:target="generateReport"
                    x-cloak
                >@lang('inspections.form.btn_generate_report')</x-btn>
            @endif
            @if ($this->inspection->is_approved && $this->inspection->isCertifiable())
                <x-btn
                    icon="file-check"
                    small
                    wire:click="generateCertificate"
                    wire:loading.attr="disabled"
                    wire:loading.class="is-loading"
                    wire:target="generateCertificate"
                    x-cloak
                >@lang('inspections.form.btn_generate_certificate')</x-btn>
            @endif
        @endif
    </div>
</div>