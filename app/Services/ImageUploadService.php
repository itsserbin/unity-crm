<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    public function uploadAndFormatImage($image, $quality)
    {
        $filename = $image->getClientOriginalName();

        if (env('APP_ENV') !== 'local') {
            $path = tenancy()->tenant->id . '/images/';
        } else {
            $path = 'local/' . tenancy()->tenant->id . '/images/';
        }

        $webpImage = Image::make($image)->encode('webp', $quality)->stream();

        $webpFilename = $this->getFormattedFilename($filename, 'webp');
        Storage::disk('s3')->put($path . $webpFilename, $webpImage);

        $jpegImage = Image::make($image)->encode('jpeg', $quality)->stream();

        $jpegFilename = $this->getFormattedFilename($filename, 'jpeg');
        Storage::disk('s3')->put($path . $jpegFilename, $jpegImage);

        return [
            'webp' => $webpFilename,
            'jpeg' => $jpegFilename,
        ];
    }

    private function getFormattedFilename($filename, $extension)
    {
        $nameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
        return $nameWithoutExtension . '.' . $extension;
    }
}
