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
        public readonly int $page,
        public readonly ?int $per_page,
        public readonly ?string $sort_by,
        public readonly ?string $sort_order,
        public readonly array $search = [],
    ) {}

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function make(
        int $page,
        ?int $per_page = null,
        ?string $sort_by = null,
        ?string $sort_order = null,
        array $search = [],
    ): static {
        /** @var SettingService $settingService */
        $settingService  = app()->make(SettingServiceInterface::class);

        $per_page = $per_page ?? $settingService->getSystemSettingValue(Setting::PAGINATION_PER_PAGE, SettingGroup::PAGINATION);
        $sort_by = $sort_by ?? $settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_BY, SettingGroup::PAGINATION);
        $sort_order = $sort_order ?? $settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_DIRECTION, SettingGroup::PAGINATION);

        return new static(
            $page,
            $per_page,
            $sort_by,
            $sort_order,
            $search,
        );
    }
}
