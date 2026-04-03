@use('App\Enums\CountryCode')
@use('App\Enums\InspectionObject\Type as InspectionObjectType')

<x-tabs.tab-panel id="general">
    <div class="grid grid--gap-m">
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('inspection_object.client_id.label')"
                model="form.client_id"
                :options="$this->clients"
                :selected="$form->client_id"
                required
            >
                <x-slot:field-action>
                    <x-btn icon="plus" small text x-on:click="$dispatch('openModal', { component: 'clients.client-modal', arguments: { id: null } })">Nieuwe klant</x-btn>
                </x-slot:field-action>
            </x-form.select>
        </div>
        <div class="grid__col">
            <x-form.input
                :label="__('clients.form.address.label')"
                model="form.address"
            />
        </div>
        <div class="grid__col l:grid__col--span-4">
            <x-form.input
                :label="__('clients.form.postal_code.label')"
                model="form.postal_code"
                :placeholder="$this->postalCodePlaceholder()"
            />
        </div>
        <div class="grid__col l:grid__col--span-8">
            <x-form.input
                :label="__('clients.form.city.label')"
                model="form.city"
            />
        </div>
        <div class="grid__col">
            <x-form.select
                :label="__('clients.form.country.label')"
                wire:model.live="form.country"
                :options="CountryCode::options()"
            />
        </div>
        <div class="grid__col">
            <x-form.textarea
                :label="__('inspection_object.description.label')"
                model="form.description"
            />
        </div>
    </div>
</x-tabs.tab-panel>
