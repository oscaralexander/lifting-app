@use('App\Enums\FieldType')

<div>
    <x-modal.header>@lang($id ? 'fields.modal.title_edit' : 'fields.modal.title_create')</x-modal.header>
    <x-modal.body class="fieldModal">
        <x-form class="u-stack u-stack-gap-xl">
            @if (empty($type))
                {{--  Step 1 --}}
                <div class="fieldTypeSelect" x-transition>
                    @foreach (FieldType::cases() as $type)
                        <button
                            class="btn fieldTypeSelect__button"
                            type="button"
                            wire:click="selectFieldType('{{ $type }}')"
                            wire:key="type-{{ $type->value }}"
                        >
                            <x-icon :icon="$type->icon()" />
                            {{ $type->label() }}
                        </button>
                    @endforeach
                </div>
            @else
                {{-- Step 2 --}}
                <div class="u-stack u-stack-gap-xl" x-transition>
                    <div class="fieldModal__back">
                        <div class="fieldModal__back-iconLabel">
                            <x-icon :icon="$type->icon()" />
                            {{  $type->label() }}
                        </div>
                        <button
                            class="btn btn--text"
                            wire:click="unsetFieldType()"
                            type="button"
                        >@lang('ui.back')</button>
                    </div>
                    <div class="grid grid--gap-m" x-data="{ showDescription: false }">
                        <div class="grid__col grid__col--span-6">
                            <x-form.input
                                autofocus
                                :label="__('models/field.number.label')"
                                maxlength="4"
                                model="number"
                                pattern="[0-9]{4}"
                                :placeholder="__('models/field.number.placeholder')"
                                required
                            />  
                        </div>
                        <div class="grid__col">
                            <div class="u-stack u-stack-gap-m">
                                <x-form.textarea
                                    :label="__('models/field.label.label')"
                                    model="label"
                                    required
                                    rows="1"
                                />
                                @if ($type !== FieldType::TOGGLE)
                                    <div class="actions" x-show="!showDescription">
                                        <x-btn
                                            icon="plus"
                                            mini
                                            x-on:click="showDescription = true"
                                        >@lang('fields.modal.btn_add_description')</x-btn>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="grid__col" x-show="showDescription">
                            <x-form.input
                                :label="__('models/field.description.label')"
                                model="description"
                            />
                        </div>
                        @if ($type === FieldType::SELECT || $type === FieldType::SELECT_MULTIPLE)
                            <div class="grid__col">
                                <x-form.textarea
                                    :description="__('models/field.values.description')"
                                    :label="__('models/field.values.label')"
                                    model="values"
                                    required
                                />
                            </div>
                        @endif
                        @if ($type === FieldType::TOGGLE)
                            <div class="grid__col">
                                <x-form.lightswitch
                                    model="attrs.default_checked"
                                    :text="__('fields.modal.label_default_checked')"
                                />
                            </div>
                        @endif
                    </div>
                    <div class="actions">
                        <button
                            class="btn btn--primary"
                            type="submit"
                            x-bind:disabled="$wire.id === null"
                        >@lang('ui.save')</button>
                        <span>
                            @lang('ui.or')
                            <button
                                class="btn btn--text"
                                wire:click="$dispatch('closeModal')"
                                type="button"
                            >@lang('ui.cancel')</button>
                        </span>
                    </div>
                </div>
            @endif
        </x-form>
    </x-modal.body>
</div>