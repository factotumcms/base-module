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

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'login_successful',
                line: 'Accesso effettuato con successo.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'login_failed',
                line: 'Accesso fallito, email o password errati.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'auth',
                key: 'logout_successful',
                line: 'Logout effettuato con successo.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'login_successful',
                line: 'Login successful.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'login_failed',
                line: 'Login failed, email or password is incorrect.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'auth',
                key: 'logout_successful',
                line: 'Logout successful.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'passwords',
                key: 'expired',
                line: 'La tua password è scaduta. Per favore aggiorna la tua password per continuare',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'passwords',
                key: 'expired',
                line: 'Your password has expired. Please update your password to continue',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::IT,
                group: 'validation',
                key: 'password.history_used',
                line: 'La :attribute fornita è stata precedentemente utilizzata. Per favore scegli una :attribute diversa.',
            )
        );

        $this->languageService->create(
            data: new RegisterLineDto(
                locale: Locale::EN,
                group: 'validation',
                key: 'password.history_used',
                line: 'The given :attribute has been previously used. Please choose a different :attribute.',
            )
        );
    }
}
