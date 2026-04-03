<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Models\Form;
use App\Models\FormComment;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormCommentModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    #[Locked]
    public int $formId;

    public $comment;

    #[Computed]
    public function formComment(): FormComment
    {
        $formComment = FormComment::findOrNew($this->id);
        $formComment->form()->associate($this->formId);
        return $formComment;
    }

    #[Computed]
    public function form(): Form
    {
        return Form::findOrFail($this->formId);
    }

    public function mount(int $formId, ?int $id): void
    {
        $this->formId = $formId;
        $this->id = $id;

        if ($this->id) {
            $this->comment = $this->formComment->comment;
        }
    }

    public function render(): View
    {
        return view('livewire.schemas.form-comment-modal');
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'],
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $exists = $this->formComment->exists;

        if (!$exists) {
            $this->formComment->position = $this->form->getNextPosition();
        }

        $this->formComment->comment = $this->comment;
        $this->formComment->save();

        $this->dispatch('toast', message: __('form_builder.toast.form_comment_' . ($exists ? 'updated' : 'created')), type: 'success');
        $this->closeModalWithEvents([Event::REFRESH,]);
    }
}
