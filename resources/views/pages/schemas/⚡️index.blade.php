<?php

use App\Constants\Event;
use App\Models\Form;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function forms(): LengthAwarePaginator
    {
        return Form::withCount('fields')->paginate(10);
    }

    public function delete(int $id): void
    {
        Form::findOrFail($id)->delete();
        $this->dispatch('toast', message: __('schemas.toast.deleted'), type: 'success');
    }

    #[On(Event::REFRESH)]
    public function render()
    {
        return $this->view()
            ->title(__('schemas.index.title'));
    }
}
?>

<div>
    <x-header :title="__('schemas.index.title')">
        <x-slot:actions>
            <x-btn
                icon="plus"
                primary
                x-on:click="$dispatch('openModal', { component: 'schemas.schema-modal', arguments: { id: null } })"
            >@lang('schemas.index.btn_create')</x-btn>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('schemas.index.col_name')</th>
                    <th class="table__num" scope="col">@lang('schemas.index.col_fields')</th>
                    <th scope="col">@lang('schemas.index.col_updated_at')</th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->forms as $form)
                    <tr wire:key="schema-{{ $form->id }}">
                        <td>
                            <a class="table__main" href="{{ route('schemas.edit', $form) }}" wire:navigate>{{ $form->name }}</a>
                        </td>
                        <td class="table__num">{{ $form->fields_count }}</td>
                        <td>{{ $form->updated_at->translatedFormat('d F Y') }}</td>
                        <td>
                            <div class="table__actions">
                                <x-popout
                                    id="popout-form-{{ $form->id }}"
                                    position="tl"
                                    small
                                    transparent
                                >
                                    <x-popout.item
                                        icon="pencil"
                                        :label="__('ui.edit')"
                                        :navigate="false"
                                        x-on:click.prevent="$dispatch('openModal', { component: 'schemas.schema-modal', arguments: { id: {{ $form->id }} } })"
                                    />
                                    <x-popout.item
                                        danger
                                        icon="trash"
                                        :label="__('ui.delete')"
                                        wire:click.prevent="delete({{ $form->id }})"
                                        wire:confirm="{{ __('schemas.index.confirm_delete') }}"
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
