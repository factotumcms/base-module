<?php

describe('MediaModel', function () {
    it('successfully creates a new Media model instance', function () {

        $media = new \Wave8\Factotum\Base\Models\Media();

        expect($media)->toBeInstanceOf(\Wave8\Factotum\Base\Models\Media::class);
    });

    it('checks fillable columns', function () {

        $media = new \Wave8\Factotum\Base\Models\Media();

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
});



