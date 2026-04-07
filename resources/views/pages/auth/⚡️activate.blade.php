<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public string $email;

    public string $password = '';

    public string $password_confirmation = '';

    #[Locked]
    public string $token;

    public User $user;

    public function mount(string $token): void
    {
        $this->user = User::where('token', $token)->firstOrFail();

        if (empty($token) || ! is_null($this->user->password)) {
            $this->redirectRoute('admin');
        }

        $this->email = $this->user->email;
        app()->setLocale($this->user->locale->value);
    }

    public function render()
    {
        return $this->view()
            ->title(__('auth.activate.title'));
    }

    public function submit(): void
    {
        $this->validate();

        $this->user->update([
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make($this->password),
            'token' => null,
        ]);

        auth('web')->login($this->user);
        $this->redirectRoute('admin');
    }

    protected function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64', 'ascii', 'exists:users,token'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
?>

<div class="u-stack u-stack-gap-xxl">
    <header class="guest__header">
        <a href="{{ route('admin') }}" wire:navigate><img alt="Van der Spek" class="guest__logo" src="/assets/img/van-der-spek.svg"></a>
    </header>
    <p class="guest__intro u-text-center">
        {!! __('auth.activate.intro') !!}
    </p>
    <x-form class="u-stack u-stack-gap-m">
        <input type="hidden" name="token" value="{{ request()->route('token') }}">
        <fieldset class="fields field--tight">
            <x-form.input :label="__('user.label_email')" model="email" name="email" type="email" disabled required />
            <x-form.new-password :label="__('user.label_password')" model="password" name="password" required with-rules />
            <x-form.new-password :label="__('user.label_password_confirmation')" model="password_confirmation" name="password_confirmation" required />
            <div>
                <button class="btn btn--primary" type="submit">{{ __('auth.activate.btn') }}</button>
            </div>
        </fieldset>
    </x-form>
</div>
