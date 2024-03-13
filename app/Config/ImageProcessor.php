<?php

namespace Config;

use Closure;
use Exception;
use Intervention\Image\ImageManagerStatic as Image;
use System\Config\AppConfig;

class ImageProcessor
{
    static function optimizeImages(
        string|array $format,
        int $quality
    ): void {
        if (extension_loaded("GD")) {
            $imagePaths = strtoupper(gettype($format)) == "STRING" ? glob($format) : $format;

            $validExt = ["jpg", "png", "gif", "tif", "bmp", "ico", "psd", "webp"];

            foreach ($imagePaths as $imagePath) {
                if (file_exists($imagePath)) {
                    $info = pathinfo($imagePath);

                    if (isset($info["extension"]) && in_array(strtolower($info["extension"]), $validExt)) {
                        self::resizeImage(
                            img: $imagePath,
                            newWidth: null,
                            newHeight: null,
                            callback: fn ($constraint) => $constraint->aspectRatio()
                        );
                        self::encodeImage(
                            img: $imagePath,
                            quality: $quality,
                            format: $info["extension"]
                        );
                    }
                }
            }
        } else throw new Exception("Enable the \"GD\" extension");
    }

    static function encodeImage(
        string $img,
        int $quality = 90,
        ?string $format = "data-url"
    ): void {
        Image::make($img)->encode($format, $quality)->save($img);
    }

    static function resizeImage(
        string $img,
        ?int $newWidth = null,
        ?int $newHeight = null,
        ?Closure $callback = null
    ): void {
        Image::make($img)->resize($newWidth, $newHeight, $callback)->save($img);
    }

    static function rotateImage(
        string $img,
        int $angle,
        ?string $bgcolor = null
    ): void {
        Image::make($img)->rotate($angle, $bgcolor)->save($img);
    }

    static function correctImageURL(
        string $url
    ): ?string {
        $url = str_replace([
            AppConfig::BASE_FOLDER,
            AppConfig::BASE_SERVER
        ], "", $url);

        $img = trim($url, "/");

        $pathFolder = AppConfig::BASE_FOLDER . "/" . $img;
        $pathServer = AppConfig::BASE_SERVER . "/" . $img;

        return (file_exists($pathFolder) ? $pathServer : null);
    }
}
