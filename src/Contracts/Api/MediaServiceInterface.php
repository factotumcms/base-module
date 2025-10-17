<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Database\Eloquent\Collection;
use Wave8\Factotum\Base\Contracts\EntityServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Models\Media;

interface MediaServiceInterface extends EntityServiceInterface
{
    public function store(StoreFileDto $data): Media;
    public function generateConversions(Media $media): void;
    public function getMediaNotConverted(): Collection;
}
