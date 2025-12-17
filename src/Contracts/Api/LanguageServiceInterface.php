<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Wave8\Factotum\Base\Dtos\Api\Language\RegisterLineDto;

interface LanguageServiceInterface
{
    public function create(RegisterLineDto $data): void;
}
