<?php

namespace Wave8\Factotum\Base\Dtos;

use Illuminate\Contracts\Container\BindingResolutionException;
use Ramsey\Collection\Sort;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Services\Api\SettingService;

#[MapName(SnakeCaseMapper::class)]
class QueryFiltersDto extends Data
{
    public int|Optional $page;

    public int|Optional $perPage;

    public string|Optional $sortBy;

    public Sort|Optional $sortOrder;

    public function __construct(
        public readonly array $search = [],
    ) {
        /** @var SettingService $settingService */
        $settingService = app(SettingServiceInterface::class);

        $this->page = 1;
        $this->perPage = $settingService->getSystemSettingValue(Setting::PAGINATION_PER_PAGE, SettingGroup::PAGINATION) ?? 15;
        $this->sortBy = $settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_BY, SettingGroup::PAGINATION) ?? 'id';
        $this->sortOrder = Sort::tryFrom($settingService->getSystemSettingValue(Setting::PAGINATION_DEFAULT_ORDER_DIRECTION, SettingGroup::PAGINATION)) ?? Sort::Descending;

    }

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    //    public static function make(
    //        int $page = 1,
    //        ?int $perPage = null,
    //        ?string $sortBy = null,
    //        ?string $sortOrder = null,
    //        array $search = [],
    //    ): static {
    //
    //        return new static(
    //            $page,
    //            $perPage,
    //            $sortBy,
    //            $sortOrder,
    //            $search,
    //        );
    //    }
}
