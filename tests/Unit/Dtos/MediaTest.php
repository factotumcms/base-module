<?php

use Illuminate\Http\UploadedFile;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Wave8\Factotum\Base\Dtos\Api\Media\CreateMediaDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaCropDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaCustomPropertiesDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaFitDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaPresetConfigDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaResizeDto;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

describe('MediaDto', function () {
    it('successfully create a new CreateMediaDto instance', function () {
        $val = 'test';

        $dto = new CreateMediaDto(
            name: $val, fileName: $val, mimeType: $val, mediaType: MediaType::IMAGE, presets: null, disk: Disk::LOCAL, path: $val, size: 123, customProperties: '{}', conversions: null
        );

        expect($dto)->toBeInstanceOf(CreateMediaDto::class);
    });

    it('successfully create a new MediaCropDto instance', function () {

        $dto = new MediaCropDto(
            width: 100, height: 100, position: CropPosition::Center
        );

        expect($dto)->toBeInstanceOf(MediaCropDto::class);
    });

    it('successfully create a new MediaCustomPropertiesDto instance', function () {
        $val = 'test';

        $dto = new MediaCustomPropertiesDto(
            alt: $val, title: $val
        );

        expect($dto)->toBeInstanceOf(MediaCustomPropertiesDto::class);
    });

    it('successfully create a new MediaFitDto instance', function () {

        $dto = new MediaFitDto(
            method: Fit::Contain, width: 100, height: 100
        );

        expect($dto)->toBeInstanceOf(MediaFitDto::class);
    });

    it('successfully create a new MediaPresetConfigDto instance', function () {

        $dto = new MediaPresetConfigDto(
            suffix: 'thumb', optimize: true, resize: null, fit: new MediaFitDto(method: Fit::Contain, width: 100, height: 100), crop: null
        );

        expect($dto)->toBeInstanceOf(MediaPresetConfigDto::class);
    });

    it('successfully create a new MediaResizeDto instance', function () {

        $dto = new MediaResizeDto(
            width: 100, height: 100
        );

        expect($dto)->toBeInstanceOf(MediaResizeDto::class);
    });

    it('successfully create a new StoreFileDto instance', function () {

        $file = UploadedFile::fake()->image('test.jpg');

        $dto = new StoreFileDto(
            file: $file, presets: null
        );

        expect($dto)->toBeInstanceOf(StoreFileDto::class);
    });

});
