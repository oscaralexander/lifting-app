@use(App\Enums\Locale)
@use(App\Enums\UserRole)
@use(App\Models\Inventory)
@use(App\Models\User)

<x-form>
    <fieldset class="fields fields--tight">
        <div class="grid grid--gap-l">
            <div class="grid__col grid__col--span-6">
                <x-form.input
                    :label="__('user.label_first_name')"
                    model="form.first_name"
                    required
                />
            </div>
            <div class="grid__col grid__col--span-6">
                <x-form.input
                    :label="__('user.label_last_name')"
                    model="form.last_name"
                    required
                />
            </div>
        </div>
        <x-form.input
            :label="__('user.label_email')"
            model="form.email"
            required
        />
        <div class="grid grid--gap-l">
            @if (auth('web')->user()->is_admin)
                <div class="grid__col grid__col--span-6">
                    <x-form.select
                        :label="__('user.label_role')"
                        model="form.role"
                        :options="UserRole::options()"
                        required
                    />
                </div>
            @endif
            <div class="grid__col grid__col--span-6">
                <x-form.select
                    :label="__('user.label_locale')"
                    model="form.locale"
                    :options="Locale::options()"
                    required
                />
            </div>
        </div>
        @if ($user->isActive)
            <div class="grid grid--gap-l">
                <div class="grid__col grid__col--span-6">
                    <x-form.input
                        :label="__('user.label_password_new')"
                        model="form.password"
                        type="password"
                    />
                </div>
                <div class="grid__col grid__col--span-6">
                    <x-form.input
                        :label="__('user.label_password_new_confirmation')"
                        model="form.password_confirmation"
                        type="password"
                    />
                </div>
            </div>
        @endunless
    </fieldset>
    <div class="actions">
        <button
            class="btn btn--primary"
            type="submit"
        >@lang('ui.save')</button>
        @can('viewAny', App\Models\User::class)
            <span>
                @lang('ui.or')
                <a href="{{ route('admin.users') }}" wire:navigate>@lang('ui.cancel')</a>
            </span>
        @endcan
    </div>
</x-form>
