<?php

namespace App\Livewire\Inspections;

use App\Enums\InspectionObject\Type as InspectionObjectType;
use App\Models\Form;
use App\Models\Inspection;
use App\Models\InspectionObject;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class AddAttachmentModal extends ModalComponent
{
    #[Locked]
    public string $parentInspectionHash;

    public ?int $inspectionObjectId = null;

    public ?int $formId = null;

    public function mount(string $parentInspectionHash): void
    {
        $this->parentInspectionHash = $parentInspectionHash;

        abort_if($this->parentInspection->isAttachment(), 403);

        if (count($this->formOptions) === 1) {
            $this->formId = array_key_first($this->formOptions);
        }
    }

    #[Computed]
    public function parentInspection(): Inspection
    {
        return Inspection::query()
            ->without(['form', 'form.fields', 'form.fieldGroups.fields', 'form.fieldGroups.formComments', 'form.formComments', 'user'])
            ->with('inspectionObject')
            ->where('hash', $this->parentInspectionHash)
            ->firstOrFail();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function attachmentOptions(): array
    {
        return InspectionObject::query()
            ->where('type', InspectionObjectType::ATTACHMENT)
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get()
            ->mapWithKeys(fn (InspectionObject $inspectionObject) => [
                $inspectionObject->id => collect([
                    $inspectionObject->type_label,
                    $inspectionObject->name,
                    $inspectionObject->serial_number,
                ])->filter()->implode(' · '),
            ])
            ->toArray();
    }

    /**
     * The attachments that are already linked to the main inspection.
     *
     * @return list<int>
     */
    #[Computed]
    public function linkedAttachmentIds(): array
    {
        return $this->parentInspection->attachmentInspections()
            ->pluck('inspection_object_id')
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function formOptions(): array
    {
        return Form::query()
            ->without(['fields', 'fieldGroups.fields', 'formComments', 'fieldGroups.formComments'])
            ->where('type', InspectionObjectType::ATTACHMENT)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.inspections.add-attachment-modal');
    }

    public function submit(): void
    {
        $this->validate([
            'inspectionObjectId' => [
                'required',
                Rule::exists('inspection_objects', 'id')
                    ->where('type', InspectionObjectType::ATTACHMENT->value)
                    ->whereNull('deleted_at'),
                Rule::notIn($this->linkedAttachmentIds),
            ],
            'formId' => [
                'required',
                Rule::exists('forms', 'id')->where('type', InspectionObjectType::ATTACHMENT->value),
            ],
        ], [
            'inspectionObjectId.not_in' => __('inspections.attachment.modal.already_linked'),
        ]);

        $form = Form::findOrFail($this->formId);

        $inspection = Inspection::create([
            ...$this->parentInspection->attachmentInspectionAttributes(),
            'form_id' => $form->id,
            'inspection_object_id' => $this->inspectionObjectId,
        ]);

        $this->redirectRoute('inspections.form', [
            'formSlug' => $form->slug,
            'inspectionObjectId' => $this->inspectionObjectId,
            'inspectionHash' => $inspection->hash,
        ], navigate: true);
    }
}
