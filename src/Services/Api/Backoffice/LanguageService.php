<?php

namespace Wave8\Factotum\Base\Services\Api\Backoffice;

use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\LanguageServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Language\RegisterLineDto;

class LanguageService implements LanguageServiceInterface
{
    /**
     * Register a new language line or update an existing one for a specific locale.
     *
     * @param  RegisterLineDto  $data  The data transfer object containing the language line details.
     */
    public function registerLine(RegisterLineDto $data): void
    {
        $line = LanguageLine::where('group', $data->group)
            ->where('key', $data->key)
            ->first();

        if (! $line) {
            $line = LanguageLine::create([
                'group' => $data->group,
                'key' => $data->key,
                'text' => [],
            ]);
        }

        $text = $line->text;

        $text[$data->locale->value] = $data->line;

        $line->text = $text;

        $line->save();
    }
}
