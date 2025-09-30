<?php

namespace Wave8\Factotum\Base\Dtos;

use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting;
use Wave8\Factotum\Base\Enums\SettingGroup;
use Wave8\Factotum\Base\Services\SettingService;

class QueryFiltersDto extends Data
{
    public function __construct(
        public readonly int $page = 1,
        public readonly ?int $perPage,
        public readonly ?string $sortBy,
        public readonly ?string $sortOrder,
        public readonly array $search = [],
    ) {}

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function make(
        int $page = 1,
        ?int $perPage = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        array $search = [],
    ): static {
        /** @var SettingService $settingService */
        $settingService = app()->make(SettingServiceInterface::class);

        $perPage = $perPage ?? $settingService->getSystemSettingValue(Setting::PAGINATION_PER_PAGE, SettingGroup::PAGINATION);
        $sortBy = $sortBy ?? $settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_BY, SettingGroup::PAGINATION);
        $sortOrder = $sortOrder ?? $settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_DIRECTION, SettingGroup::PAGINATION);

        return new static(
            $page,
            $perPage,
            $sortBy,
            $sortOrder,
            $search,
        );
    }
}
