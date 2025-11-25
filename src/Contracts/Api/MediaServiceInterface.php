<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Models\Media;

interface MediaServiceInterface
{
    public function create(Data $data): Model;

    public function read(int $id): Model;

    public function update(int $id, Data $data): Model;

    public function delete(int $id): void;

    public function filter(): LengthAwarePaginator;

    public function store(StoreFileDto $data): Media;

    public function generateConversions(Media $media): void;

    public function getMediaNotConverted(): Collection;
}
