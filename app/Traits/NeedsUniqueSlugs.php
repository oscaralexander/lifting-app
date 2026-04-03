<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait NeedsUniqueSlugs
{
    public function getUniqueSlug(string $name, bool $perCompany = false): string
    {
        $slug = $this->slug;

        if (empty($slug)) {
            $slug = Str::of($name)->slug()->lower()->toString();
        }

        $uniqueSlug = $slug;

        while (
            self::where('slug', $uniqueSlug)
                ->when(
                    $perCompany,
                    fn (Builder $query) => $query->where('company_id', $this->company_id)
                )->withoutGlobalScopes()
                ->exists()
        ) {
            $uniqueSlug = $slug . '-' . Str::random(5);
        }

        return $uniqueSlug;
    }
}
