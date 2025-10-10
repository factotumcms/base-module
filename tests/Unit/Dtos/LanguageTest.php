<?php

use Wave8\Factotum\Base\Dtos\Api\Backoffice\Language\RegisterLineDto;
use Wave8\Factotum\Base\Enums\Locale;

describe('LanguageDto', function () {
    it('successfully create a new RegisterLineDto instance', function () {
        $val = 'test';

        $dto = new RegisterLineDto(
            locale: Locale::EN, group: $val, key: $val, line: $val
        );

        expect($dto)->toBeInstanceOf(RegisterLineDto::class);
    });

});
