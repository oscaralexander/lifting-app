<?php

namespace App\Livewire\App\Submission;

use App\Constants\SessionKey;
use App\Enums\FieldType;
use App\Enums\SubmissionPdfType;
use App\Lib\SubmissionPdf;
use App\Mail\SubmissionCompleted;
use App\Models\Form as ModelsForm;
use App\Models\Sticker;
use App\Models\Submission;
use App\Rules\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public array $fields = [];

    public int $formId;

    public bool $is_completed = false;

    public $signature;

    public $signature_name;

    public string $stickerHash;

    public function deleteUpload(string $model, string $filename)
    {
        $fieldId = Str::of($model)->afterLast('_')->toInteger();
        $field = $this->form->fields->firstWhere('id', $fieldId);

        if ($field->type === FieldType::DOCUMENT || $field->type === FieldType::IMAGE) {
            if (Storage::disk('public')->delete($filename)) {
                $this->fields['field_'.$field->id] = array_filter($this->fields['field_'.$field->id], function ($file) use ($filename) {
                    return $file !== $filename;
                });

                if (empty($this->fields['field_'.$field->id])) {
                    $this->fields['field_'.$field->id] = null;
                }
            }
        }
    }

    public function download(string $path)
    {
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $filename = basename($path);

        return response()->download(Storage::disk('public')->path($path), $filename);
    }

    #[Computed]
    public function form(): ModelsForm
    {
        return ModelsForm::with([
            'fields',
            'formComments',
            'fieldGroups.fields',
            'fieldGroups.formComments',
        ])->findOrFail($this->formId);
    }

    #[Computed]
    public function submission(): Submission
    {
        return $this->sticker->stockItem->latestSubmissionForForm($this->formId) ?? new Submission;
    }

    public function mount(string $stickerHash, int $formId)
    {
        $this->stickerHash = $stickerHash;
        $this->formId = $formId;

        // Get latest Submission, if available
        $submission = $this->submission;

        // Define array type fields
        $arrayFields = [FieldType::SELECT_MULTIPLE, FieldType::DOCUMENT, FieldType::IMAGE];

        // Initialize answers
        foreach ($this->form->fields as $field) {
            if (in_array($field->type, $arrayFields)) {
                $this->fields['field_'.$field->pivot->id] = $submission->getAnswerForField($field->pivot->id, []);
            } else {
                $default = null;

                if ($field->type === FieldType::TOGGLE) {
                    $default = $field->attrs['default_checked'] ?? false;
                }

                $this->fields['field_'.$field->pivot->id] = $default;
            }
        }

        $this->signature_name = $submission->signature_name ?? auth('web')->user()->name;
        $this->is_completed = $submission->is_completed ?? false;
    }

    public function processUploads(): void
    {
        foreach ($this->fields as $key => $value) {
            $pivotId = Str::of($key)->afterLast('_')->toInteger();
            $field = $this->form->fields->firstWhere('pivot.id', $pivotId);

            if ($field) {
                if ($field->type === FieldType::DOCUMENT || $field->type === FieldType::IMAGE) {
                    $basePath = ($field->type === FieldType::IMAGE)
                        ? config('path.submissions.images')
                        : config('path.submissions.documents');

                    if (is_array($value) && count($value) > 0) {
                        $files = [];

                        foreach ($value as $file) {
                            if ($file instanceof TemporaryUploadedFile) {
                                $files[] = $file->storeAs(
                                    name: $file->getClientOriginalName(),
                                    options: [
                                        'disk' => 'public',
                                    ],
                                    path: implode('/', [
                                        $basePath,
                                        $this->sticker->hash,
                                        $this->form->id,
                                    ]),
                                );
                            } else {
                                $files[] = $file;
                            }
                        }

                        $this->fields['field_'.$field->pivot->id] = empty($files) ? null : $files;
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.app.submission.form');
    }

    #[Computed]
    public function rules(): array
    {
        $rules = [];

        foreach ($this->form->fields as $field) {
            $fieldRules = [];
            $key = 'fields.field_'.$field->pivot->id;

            if ($field->pivot->required === 1) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->type === FieldType::DOCUMENT) {
                $fieldRules[] = new UploadedFile(disk: 'public', type: 'file');
                $key = $key.'.*';
            }

            if ($field->type === FieldType::IMAGE) {
                $fieldRules[] = new UploadedFile(disk: 'public', type: 'image');
                $key = $key.'.*';
            }

            if ($field->type === FieldType::NUMBER) {
                $fieldRules[] = 'numeric';
            }

            if ($field->type === FieldType::TOGGLE) {
                $fieldRules[] = 'boolean';
            }

            $rules[$key] = $fieldRules;
        }

        // Make signature and signature name required when form is completed
        if ($this->is_completed && ! $this->submission->signed_at) {
            $rules['signature'] = 'required';
            $rules['signature_name'] = 'required';
        }

        return $rules;
    }

    #[Computed]
    public function sticker(): Sticker
    {
        return Sticker::query()
            ->with([
                'stockItem',
                'stockItem.submissions.form',
                'stockItem.submissions.form.fields',
                'stockItem.submissions.form.fieldGroups.fields',
                'stockItem.submissions.form.formComments',
                'stockItem.submissions.form.fieldGroups.formComments',
                'stockItem.machine',
            ])
            ->whereHash($this->stickerHash)
            ->firstOrFail();
    }

    public function submit()
    {
        $this->validate(rules: $this->rules());
        $this->processUploads();

        $formData = $this->fields;

        // Turn multiple select values into comma-separated strings
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $pivotId = Str::of($key)->afterLast('_')->toInteger();
                $field = $this->form->fields->firstWhere('pivot.id', $pivotId);

                if ($field) {
                    if ($field->type === FieldType::SELECT_MULTIPLE) {
                        $formData[$key] = implode(',', array_keys(array_filter($value)));
                    }

                    if ($field->type === FieldType::DOCUMENT || $field->type === FieldType::IMAGE) {
                        $formData[$key] = implode(',', $value);
                    }
                }
            }
        }

        // Filter out empty values
        $formData = array_filter($formData, fn ($value) => trim($value) !== '');

        // Check for newly completed submission
        $isNewlyCompleted = ($this->is_completed && ! $this->submission->is_completed);

        // $submission = new Submission();
        $this->submission->form_id = $this->form->id;
        $this->submission->stock_item_id = $this->sticker->stockItem->id;
        $this->submission->user_id = auth('web')->id();
        $this->submission->form_data = $formData;
        $this->submission->is_completed = $this->is_completed;
        $this->submission->save();

        if ($isNewlyCompleted) {
            // Process signature
            if ($this->signature) {
                $filename = $this->submission->hash.'.png';
                Storage::disk('public')->put('signatures/'.$filename, base64_decode(Str::of($this->signature)->after(',')));
                $this->submission->signature_name = $this->signature_name;
                $this->submission->signed_at = now();
                $this->submission->save();
            }

            // Generate internal PDF
            $pdf = new SubmissionPdf($this->submission, $this->sticker, SubmissionPdfType::INTERNAL);
            $pathInternal = $pdf->save(Storage::disk('public')->path('submissions'));

            // Generate external PDF (English)
            $currentLocale = app()->getLocale();
            app()->setLocale('en');
            $pdf = new SubmissionPdf($this->submission, $this->sticker, SubmissionPdfType::EXTERNAL);
            $pathExternal = $pdf->save(Storage::disk('public')->path('submissions'));
            app()->setLocale($currentLocale);

            // Send email to admin
            Mail::to(env('APP_SUBMISSIONS_EMAIL'))->send(new SubmissionCompleted($this->submission, $pathInternal, $pathExternal));

            // Delete submission PDFs
            unlink($pathInternal);
            unlink($pathExternal);

            return redirect()->route('qr.show', [
                'hash' => $this->sticker->hash,
            ]);
        }

        session()->flash(SessionKey::TOAST_SUCCESS, 'Opgeslagen!');

        $this->redirect(
            route('submission.form', [
                'formId' => $this->form->id,
                'stickerHash' => $this->sticker->hash,
            ])
        );
    }
}
