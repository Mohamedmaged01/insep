<?php

namespace App\Models\Concerns;

/**
 * Adds Arabic/English variants to a model's text fields.
 *
 * A model opts in with:
 *   use HasTranslations;
 *   protected array $translatable = ['title', 'description'];
 *
 * For each field `X` the table has nullable `X_ar` and `X_en` columns, plus the
 * legacy `X` column kept as a final fallback so older readers keep working.
 */
trait HasTranslations
{
    /**
     * Keep the legacy column `X` in sync with `X_ar`/`X_en` on every save, so
     * code (and search) that still reads the legacy column keeps working —
     * including API paths that persist via `$request->all()`.
     * Only syncs when the legacy field is a real fillable column of the model
     * (e.g. Section has name_ar/name_en but no legacy `name` column → skipped).
     */
    public static function bootHasTranslations(): void
    {
        static::saving(function ($model) {
            foreach ($model->translatable ?? [] as $field) {
                if (!in_array($field, $model->getFillable(), true)) continue;
                $ar = $model->getAttribute($field . '_ar');
                $en = $model->getAttribute($field . '_en');
                if (($ar !== null && $ar !== '') || ($en !== null && $en !== '')) {
                    $model->setAttribute($field, ($ar !== null && $ar !== '') ? $ar : $en);
                }
            }
        });
    }

    /**
     * Localized value for a field: current locale → ar → en → legacy column → ''.
     */
    public function tr(string $field): string
    {
        $locale = app()->getLocale() === 'en' ? 'en' : 'ar';

        return (string) (
            $this->getAttribute($field . '_' . $locale)
            ?: $this->getAttribute($field . '_ar')
            ?: $this->getAttribute($field . '_en')
            ?: $this->getAttribute($field)
            ?: ''
        );
    }

    /**
     * Build a save payload from request input: sets `X_ar`, `X_en`, and keeps the
     * legacy `X = X_ar ?: X_en` in sync for back-compat. Only touches fields that
     * are present in $input (so partial updates stay partial).
     */
    public function fillTranslatable(array $input): array
    {
        $data = [];
        foreach ($this->translatable ?? [] as $field) {
            $ar = $input[$field . '_ar'] ?? null;
            $en = $input[$field . '_en'] ?? null;

            // If the form only sent the legacy single field, treat it as Arabic.
            if ($ar === null && $en === null && array_key_exists($field, $input)) {
                $ar = $input[$field];
            }
            if ($ar === null && $en === null) {
                continue; // field not part of this submission
            }

            $data[$field . '_ar'] = $ar;
            $data[$field . '_en'] = $en;
            $data[$field]         = ($ar !== null && $ar !== '') ? $ar : $en; // legacy fallback column
        }
        return $data;
    }
}
