<?php

use App\Models\User;
use App\Services\OutsmartService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::paginate(10);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function employees(): array
    {
        return app(OutsmartService::class)->getEmployees();
    }

    public function render()
    {
        return $this->view()
            ->title(__('users.index.title'));
    }
}
?>

<div>
    <x-header :title="__('users.index.title')">
        <x-slot:actions>
            <x-btn :href="route('users.create')" icon="plus" primary>@lang('users.index.btn_create')</x-btn>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('users.index.col_name')</th>
                    <th class="l:u-show" scope="col">@lang('users.index.col_status')</th>
                    <th class="l:u-show" scope="col">@lang('users.index.col_email')</th>
                    <th class="l:u-show" scope="col">@lang('users.index.col_role')</th>
                    <th class="l:u-show" scope="col">@lang('users.index.col_last_seen_at')</th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td>
                            <a href="{{ route('users.edit', $user) }}" wire:navigate>{{ $user->name }}</a>
                            <ul class="meta table__detailsMobile l:u-hide">
                                <li>{{ $user->email }}</li>
                                <li>@lang('user_role.' . $user->role->value)</li>
                            </ul>
                        </td>
                        <td class="l:u-show">
                            <span @class(['label', 'label--active' => $user->isActive])>@lang('user.status.' . ($user->is_active ? 'active' : 'inactive'))</span>
                        </td>
                        <td class="l:u-show">{{ $user->email }}</td>
                        <td class="l:u-show">@lang('user_role.' . $user->role->value)</td>
                        <td class="l:u-show">
                            @if ($user->last_seen_at)
                                @if ($user->id === auth('web')->id())
                                    @lang('ui.now')
                                @else
                                    {{ $user->last_seen_at->diffForHumans() }}
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="table__actions">
                                <x-popout
                                    id="popout-user-{{ $user->id }}"
                                    position="tl"
                                    small
                                    transparent
                                >
                                    <x-popout.item
                                        :href="route('users.edit', $user)"
                                        icon="pencil"
                                        :label="__('ui.edit')"
                                    />
                                    @if (!$user->is_active)
                                        <x-popout.item
                                            icon="mail"
                                            :label="__('users.index.popout_resend_invite')"
                                            wire:click="resendInvite({{ $user->id }})"
                                        />
                                    @endif
                                    <x-popout.item
                                        danger
                                        icon="trash"
                                        :label="__('ui.delete')"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="{{ __('users.index.delete_confirm') }}"
                                    />
                                </x-popout>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>