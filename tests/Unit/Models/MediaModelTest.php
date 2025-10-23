<?php

use Wave8\Factotum\Base\Models\Media;
use Wave8\Factotum\Base\Tests\TestCase;

describe('MediaModel', function () {
    it('successfully creates a new Media model instance', function () {
        $media = new Media;

        expect($media)->toBeInstanceOf(Media::class);
    });

    it('checks fillable columns', function () {
        $media = new Media;

        expect($media->getFillable())->toEqual([
            'uuid',
            'name',
            'file_name',
            'mime_type',
            'media_type',
            'presets',
            'disk',
            'path',
            'size',
            'custom_properties',
            'conversions',
        ]);
    });
})->uses(TestCase::class);
