<?php

namespace Wave8\Factotum\Base\Services\Api;

use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Contracts\Api\LanguageServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Language\RegisterLineDto;

class LanguageService implements LanguageServiceInterface
{
    public function create(RegisterLineDto $data): void
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
