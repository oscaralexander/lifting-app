<?php

namespace App\Lib;

class MetaLabel
{
    public static function render(string $html, int $fieldPivotId): string
    {
        if (! str_contains($html, '%%')) {
            return $html;
        }

        $index = 0;
        $promptCopy = htmlspecialchars(
            json_encode(__('inspections.form.meta_prompt'), JSON_UNESCAPED_UNICODE),
            ENT_QUOTES,
            'UTF-8',
        );

        return preg_replace_callback('/%%/u', function () use ($fieldPivotId, &$index, $promptCopy): string {
            $path = "submissionForm.meta.field_{$fieldPivotId}.{$index}";
            $index++;

            return sprintf(
                '<span class="metaToken" role="button" tabindex="0"'
                    .' x-on:click.stop.prevent="const cur = $wire.$get(\'%1$s\'); const v = window.prompt(%2$s, cur ?? \'\'); if (v !== null) { $wire.$set(\'%1$s\', v) }"'
                    .' x-text="$wire.$get(\'%1$s\') || \'…\'"></span>',
                $path,
                $promptCopy,
            );
        }, $html);
    }
}
