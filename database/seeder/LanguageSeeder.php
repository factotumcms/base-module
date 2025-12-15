<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Api\LanguageServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Language\RegisterLineDto;
use Wave8\Factotum\Base\Enums\Locale;

class LanguageSeeder extends Seeder
{
    public function __construct(
        private readonly LanguageServiceInterface $languageService
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Log::info('Creating default language lines..');

        $json = file_get_contents(__DIR__.'/../../resources/language-lines.json');
        $lines = json_decode($json, true);

        foreach ($lines as $line) {
            $this->languageService->create(
                data: new RegisterLineDto(
                    locale: Locale::from($line['locale']),
                    group: $line['group'],
                    key: $line['key'],
                    line: $line['line'],
                )
            );
        }
    }
}
