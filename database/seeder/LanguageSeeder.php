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
        // Create admin default user
        Log::info('Creating default language lines..');

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'login_successful',
                line: 'Accesso effettuato con successo.',
            )
        );

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'login_failed',
                line: 'Accesso fallito, email o password errati.',
            )
        );

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'logout_successful',
                line: 'Logout effettuato con successo.',
            )
        );

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'login_successful',
                line: 'Login successful.',
            )
        );

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'login_failed',
                line: 'Login failed, email or password is incorrect.',
            )
        );

        $this->languageService->registerLine(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'logout_successful',
                line: 'Logout successful.',
            )
        );
    }
}
