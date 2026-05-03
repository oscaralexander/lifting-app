<?php

namespace App\Livewire\Admin\Forms;

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

    public $comment_en;

    public $comment_fr;

    public $comment_nl;

    #[Computed]
    public function formComment(): FormComment
    {
        $formComment = $this->id
            ? FormComment::findOrFail($this->id)
            : new FormComment;
        $formComment->form_id = $this->formId;

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
            $this->comment_nl = $this->formComment->comment_nl;
            $this->comment_en = $this->formComment->comment_en;
            $this->comment_fr = $this->formComment->comment_fr;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.forms.form-comment-modal');
    }

    public function rules(): array
    {
        return [
            'comment_nl' => ['required', 'string'],
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $exists = $this->formComment->exists;

        $this->formComment->comment_en = $this->comment_en ?? '';
        $this->formComment->comment_fr = $this->comment_fr ?? '';
        $this->formComment->comment_nl = $this->comment_nl;

        if (! $exists) {
            $maxFieldGroupPosition = $this->form->fieldGroups()->max('position') ?? 0;
            $maxFieldPosition = $this->form->fields()->get()->max(fn ($field) => $field->pivot->position ?? 0) ?? 0;
            $maxFormCommentPosition = $this->form->formComments()->max('position') ?? 0;
            $this->formComment->position = max($maxFieldGroupPosition, $maxFieldPosition, $maxFormCommentPosition) + 1;
        }

        $this->formComment->save();

        $this->dispatch('toast', message: __('form_comments.toast.saved'), type: 'success');

        $this->closeModalWithEvents([
            Edit::class => Event::REFRESH,
        ]);
    }
}
