<?php

namespace Wave8\Factotum\Base\Services;

use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Contracts\Services\LanguageServiceInterface;
use Wave8\Factotum\Base\Types\LocaleType;

class LanguageService implements LanguageServiceInterface
{
    public function updateByLocale(LocaleType $locale, string $group, string $key, string $data): void
    {
        // todo:: cambiare gli argomenti con un dto

        $line = LanguageLine::where('group', $group)
            ->where('key', $key)
            ->firstOrFail();

        $text = $line->text;

        $text[$locale->value] = $data;

        $line->text = $text;

        $line->save();
    }
}
