<?php

use Wave8\Factotum\Base\Enums\Media\MediaPreset;
use Wave8\Factotum\Base\Enums\Media\MediaType;

describe('MediaEnums', function () {
    it('check MediaPresetEnum has exact values', function () {

        $values = array_flip(MediaPreset::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'profile_picture_preset',
            'thumbnail_preset',
        ]);

    });

    it('check MediaTypeEnum has exact values', function () {

        $values = array_flip(MediaType::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'image',
            'video',
            'audio',
            'pdf',
            'file',
        ]);

    });
});
