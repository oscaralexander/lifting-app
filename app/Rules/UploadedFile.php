<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadedFile implements ValidationRule
{
    protected string $disk; // local|public

    protected string $type; // file|image

    public function __construct(string $disk = 'public', string $type = 'file')
    {
        $this->disk = $disk;
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof TemporaryUploadedFile) {
            if ($this->type === 'image') {
                if (!str_starts_with($value->getMimeType(), 'image/')) {
                    $fail('validation.image')->translate();
                }
            }

            return;
        }
        
        if (!Storage::disk($this->disk)->exists($value)) {
            $fail('validation.file_not_found')->translate();
        }
    }
} 