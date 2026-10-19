<?php

namespace App\Support;

use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidatePicture
{
    /**
     * The disk and directory the pictures live on.
     */
    public const DISK = 'local';

    public const DIRECTORY = 'candidates';

    /**
     * The width pictures are scaled down to, and the AVIF quality they are
     * encoded with. AVIF needs the GD image driver - ImageMagick is usually
     * built without the AVIF delegate and then fails to encode.
     */
    public const WIDTH = 600;

    public const QUALITY = 80;

    /**
     * The largest cropped image accepted from the browser.
     */
    protected const MAX_INPUT_BYTES = 10 * 1024 * 1024;

    /**
     * Store a picture cropped in the browser (a JPEG data URI) and return its
     * id, or null if it is not a usable image.
     */
    public static function store(?string $dataUri): ?string
    {
        if (! $dataUri || strlen($dataUri) > self::MAX_INPUT_BYTES) {
            return null;
        }

        $id = (string) Str::uuid();

        try {
            $stored = Image::fromBase64(Str::after($dataUri, 'base64,'))
                ->scale(width: self::WIDTH)
                ->toAvif()
                ->quality(self::QUALITY)
                ->storeAs(path: self::DIRECTORY, name: $id.'.avif', disk: self::DISK);
        } catch (ImageException) {
            return null;
        }

        return $stored === false ? null : $id;
    }

    /**
     * Get a temporary URL for a stored picture.
     */
    public static function temporaryUrl(?string $id, int $minutes = 5): ?string
    {
        if (! $id) {
            return null;
        }

        return Storage::disk(self::DISK)->temporaryUrl(self::path($id), now()->addMinutes($minutes));
    }

    /**
     * Delete a stored picture.
     */
    public static function delete(?string $id): void
    {
        if ($id) {
            Storage::disk(self::DISK)->delete(self::path($id));
        }
    }

    /**
     * Get the path of a stored picture on its disk.
     */
    public static function path(string $id): string
    {
        return self::DIRECTORY.'/'.$id.'.avif';
    }
}
