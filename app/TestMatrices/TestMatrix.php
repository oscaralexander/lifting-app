<?php

namespace App\TestMatrices;

use App\Enums\TestMatrixType;

/**
 * Defines a test matrix (beproevingstabel): the fields that are entered per
 * row and the deviations calculated from them. Each matrix has a form view
 * and a PDF view, resolved from its type.
 */
abstract class TestMatrix
{
    /**
     * A deviation (%) at or above this value fails the row.
     */
    public const MAX_DEVIATION = 10.0;

    /**
     * The options for the setup (opstelling) column of matrices that have one.
     *
     * @var list<string>
     */
    public const SETUP_OPTIONS = ['B', 'R', '0/1', '1/2', '3/4', '1/1', 'var'];

    abstract public function type(): TestMatrixType;

    /**
     * @return list<string>
     */
    abstract public function fields(): array;

    /**
     * The calculated deviations (%) of a row, keyed by name. A deviation is
     * null when its inputs are incomplete.
     *
     * @param  array<string, string|null>  $row
     * @return array<string, float|null>
     */
    abstract public function deviations(array $row): array;

    public function maxRows(): int
    {
        return 20;
    }

    public function formView(): string
    {
        return 'livewire.inspections.test-matrix.'.$this->slug();
    }

    public function pdfView(): string
    {
        return 'pdf.test-matrix.'.$this->slug();
    }

    /**
     * A new row, with each field set to its default value.
     *
     * @return array<string, string|null>
     */
    public function blankRow(): array
    {
        return array_fill_keys($this->fields(), null);
    }

    /**
     * The validation rules for the rows, keyed by the component's `rows` property.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'rows' => ['array', 'max:'.$this->maxRows()],
            'rows.*.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Keep only this matrix's fields, with at least one and at most the
     * maximum number of rows.
     *
     * @param  array<int, array<string, string|null>>  $rows
     * @return list<array<string, string|null>>
     */
    public function normalize(array $rows): array
    {
        $blankRow = $this->blankRow();
        $rows = array_slice(array_values($rows), 0, $this->maxRows()) ?: [$blankRow];

        return array_map(
            fn (mixed $row): array => array_merge($blankRow, array_intersect_key((array) $row, $blankRow)),
            $rows,
        );
    }

    /**
     * The normalized rows that have at least one value filled in other than
     * its default.
     *
     * @param  array<int, array<string, string|null>>  $rows
     * @return list<array<string, string|null>>
     */
    public function filledRows(array $rows): array
    {
        $blankRow = $this->blankRow();

        return array_values(array_filter(
            $this->normalize($rows),
            fn (array $row): bool => array_filter(
                $row,
                fn (mixed $value, string $field): bool => filled($value) && $value !== $blankRow[$field],
                ARRAY_FILTER_USE_BOTH,
            ) !== [],
        ));
    }

    /**
     * Whether a row passes: null when no deviation can be calculated yet,
     * false when any deviation reaches the maximum.
     *
     * @param  array<string, string|null>  $row
     */
    public function isApproved(array $row): ?bool
    {
        $deviations = array_filter($this->deviations($row), fn (?float $deviation): bool => $deviation !== null);

        if ($deviations === []) {
            return null;
        }

        foreach ($deviations as $deviation) {
            if ($deviation >= static::MAX_DEVIATION) {
                return false;
            }
        }

        return true;
    }

    public function formatDeviation(?float $deviation): string
    {
        return $deviation === null ? '—' : number_format($deviation, 2, ',', '').'%';
    }

    public function deviationStatus(?float $deviation): string
    {
        return $this->status($deviation === null ? null : $deviation < static::MAX_DEVIATION);
    }

    public function formatApproval(?bool $isApproved): string
    {
        return match ($isApproved) {
            null => '—',
            true => 'JA',
            false => 'NEE',
        };
    }

    public function status(?bool $isApproved): string
    {
        return match ($isApproved) {
            null => 'neutral',
            true => 'passed',
            false => 'failed',
        };
    }

    /**
     * The deviation (%) of a measured value from its permissible value.
     */
    public static function deviation(mixed $permissible, mixed $measured): ?float
    {
        $permissible = self::parseNumber($permissible);
        $measured = self::parseNumber($measured);

        if ($permissible === null || $measured === null || $permissible == 0.0) {
            return null;
        }

        return (($measured - $permissible) / $permissible) * 100;
    }

    /**
     * Parse a number entered with either a decimal comma or point.
     */
    public static function parseNumber(mixed $value): ?float
    {
        $value = str_replace(',', '.', trim((string) $value));

        return is_numeric($value) ? (float) $value : null;
    }

    protected function slug(): string
    {
        return str_replace('_', '-', $this->type()->value);
    }
}
