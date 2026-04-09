@use(App\Enums\Locale)
@use(App\Enums\UserRole)

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
    @if (auth('web')->user()->is_admin)
        <div class="grid grid--gap-l">
            <div class="grid__col grid__col--span-6">
                <x-form.select
                    :label="__('user.label_role')"
                    model="form.role"
                    :options="UserRole::options()"
                    required
                />
            </div>
        </div>
    @endif
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
    @endif
</fieldset>
