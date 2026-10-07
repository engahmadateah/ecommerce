<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Turns uploaded JPG/PNG product photos into smaller WebP files.
 *
 * Needs the PHP "gd" extension with WebP support. If anything is missing or
 * goes wrong the original file is kept, so an upload can never fail because of this.
 */
class ImageOptimizer
{
    public static function optimize(?string $path, string $disk = 'public'): ?string
    {
        if (! $path || ! config('shop.images.webp', true) || ! function_exists('imagewebp')) {
            return $path;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            return $path; // already webp / gif / svg ...
        }

        try {
            $storage = Storage::disk($disk);

            if (! $storage->exists($path)) {
                return $path;
            }

            $image = @imagecreatefromstring($storage->get($path));

            if ($image === false) {
                return $path;
            }

            if (function_exists('imagepalettetotruecolor')) {
                imagepalettetotruecolor($image);
            }
            imagealphablending($image, true);
            imagesavealpha($image, true);

            $maxWidth = (int) config('shop.images.max_width', 1600);

            if (imagesx($image) > $maxWidth) {
                $scaled = imagescale($image, $maxWidth);

                if ($scaled !== false) {
                    $image = $scaled;
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
            }

            ob_start();
            $ok = imagewebp($image, null, (int) config('shop.images.quality', 82));
            $data = ob_get_clean();

            if (! $ok || $data === '' || $data === false) {
                return $path;
            }

            $newPath = preg_replace('/\.[^.]+$/', '', $path).'.webp';
            $storage->put($newPath, $data);

            if ($newPath !== $path) {
                $storage->delete($path);
            }

            return $newPath;
        } catch (Throwable $e) {
            report($e);

            return $path;
        }
    }
}
