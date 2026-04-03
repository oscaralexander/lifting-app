@use('App\Enums\CountryCode')

<div>
    <x-modal.header>@lang('clients.modal.title_' . ($id ? 'edit' : 'create'))</x-modal.header>
    <x-modal.body>
        <x-form class="u-stack u-stack-gap-xl">
            <x-tabs id="client-modal">
                <x-tabs.tab-list>
                    <x-tabs.tab active id="company">@lang('clients.form.tab_company')</x-tabs.tab>
                    <x-tabs.tab id="contact">@lang('clients.form.tab_contact')</x-tabs.tab>
                </x-tabs.tab-list>
                <x-tabs.tab-panels>
                    <x-tabs.tab-panel id="company">
                        <div class="grid grid--gap-m">
                            <div class="grid__col">
                                <x-form.input
                                    :label="__('clients.form.name.label')"
                                    model="name"
                                    required
                                />
                            </div>
                            <div class="grid__col">
                                <x-form.input
                                    :label="__('clients.form.address.label')"
                                    model="address"
                                    required
                                />
                            </div>
                            <div class="grid__col l:grid__col--span-4">
                                <x-form.input
                                    :label="__('clients.form.postal_code.label')"
                                    model="postal_code"
                                    :placeholder="$this->postalCodePlaceholder()"
                                    required
                                />
                            </div>
                            <div class="grid__col l:grid__col--span-8">
                                <x-form.input
                                    :label="__('clients.form.city.label')"
                                    model="city"
                                    required
                                />
                            </div>
                            <div class="grid__col">
                                <x-form.select
                                    :label="__('clients.form.country.label')"
                                    wire:model.live="country"
                                    :options="CountryCode::options()"
                                    required
                                />
                            </div>
                        </div>
                    </x-tabs.tab-panel>
                    <x-tabs.tab-panel id="contact">
                        <div class="grid grid--gap-m">
                            <div class="grid__col">
                                <x-form.input
                                    :label="__('clients.form.contact_name.label')"
                                    model="contact_name"
                                />
                            </div>
                            <div class="grid__col">
                                <x-form.input
                                    :label="__('clients.form.contact_email.label')"
                                    model="contact_email"
                                />
                            </div>
                            <div class="grid__col">
                                <x-form.input
                                    :label="__('clients.form.contact_phone.label')"
                                    model="contact_phone"
                                    :placeholder="$this->phonePlaceholder()"
                                />
                            </div>
                        </div>
                    </x-tabs.tab-panel>
                </x-tabs.tab-panels>
            </x-tabs>
            <div class="actions">
                <x-btn primary submit>@lang('ui.save')</x-btn>
                <span>
                    @lang('ui.or')
                    <x-btn text wire:click="$dispatch('closeModal')">@lang('ui.cancel')</x-btn>
                </span>
            </div>
        </x-form>
    </x-modal.body>
</div>