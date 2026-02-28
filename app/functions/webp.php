<?php
declare(strict_types=1);

namespace functions;

class Webp
{
    private array $allowedHosts = ['vipintellect.ge'];
    private string $cacheDir;
    private string $cacheUrl = '/cache/';
    private int $cacheTtl = 60 * 60 * 24 * 30;

    private int $targetSize   = 50 * 1024; // 50KB
    private int $startQuality = 85;
    private int $minQuality   = 40;

    private array $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct()
    {
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/cache/';
    }

    public function index(string $url, int $size = 3): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $parts = parse_url($url);

        if (
            empty($parts['scheme']) ||
            empty($parts['host']) ||
            empty($parts['path']) ||
            !in_array($parts['scheme'], ['http', 'https'], true) ||
            !in_array($parts['host'], $this->allowedHosts, true)
        ) {
            return $url;
        }

        if (!$this->supportsWebp()) {
            return $url;
        }

        $localPath = $_SERVER['DOCUMENT_ROOT'] . $parts['path'];

        if (!is_file($localPath)) {
            return $url;
        }

        $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowedExt, true)) {
            return $url;
        }

        // Size presets
        [$targetWidth, $targetHeight] = match ($size) {
            1 => [100, 100],
            2 => [400, 400],
            3 => [555, 320],
            default => [0, 0], // keep original ratio
        };

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }

        // Include size in hash so each size is cached separately
        $hash = hash('xxh3', $parts['path'] . '_s' . $size);
        $webpFile = $this->cacheDir . $hash . '.webp';
        $webpUrl  = $this->cacheUrl . $hash . '.webp';

        if (is_file($webpFile) && (time() - filemtime($webpFile)) < $this->cacheTtl) {
            return $webpUrl;
        }

        $image = $this->createImage($localPath, $ext);
        if (!$image) {
            return $url;
        }

        // Resize if needed
        if ($targetWidth > 0 && $targetHeight > 0) {
            // $image = $this->resizeExact($image, $targetWidth, $targetHeight);
            $image = $this->cropAndResize($image, $targetWidth, $targetHeight);
        }

        // Compress dynamically to target size
        $this->compressToTarget($image, $webpFile);

        imagedestroy($image);

        // If conversion failed or bigger than original → fallback
        if (!is_file($webpFile) || filesize($webpFile) >= filesize($localPath)) {
            @unlink($webpFile);
            return $url;
        }

        return $webpUrl;
    }

    private function createImage(string $path, string $ext)
    {
        return match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png'         => $this->createPng($path),
            'gif'         => @imagecreatefromgif($path),
            default       => null,
        };
    }

    private function createPng(string $path)
    {
        $image = @imagecreatefrompng($path);
        if ($image) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        return $image;
    }

    // private function resizeExact($image, int $newWidth, int $newHeight)
    // {
    //     $resized = imagecreatetruecolor($newWidth, $newHeight);

    //     imagecopyresampled(
    //         $resized,
    //         $image,
    //         0, 0, 0, 0,
    //         $newWidth,
    //         $newHeight,
    //         imagesx($image),
    //         imagesy($image)
    //     );

    //     imagedestroy($image);

    //     return $resized;
    // }

    private function cropAndResize($image, int $targetWidth, int $targetHeight)
    {
        $origWidth  = imagesx($image);
        $origHeight = imagesy($image);

        $targetRatio = $targetWidth / $targetHeight;
        $origRatio   = $origWidth / $origHeight;

        if ($origRatio > $targetRatio) {
            // Image is wider → crop width
            $newHeight = $origHeight;
            $newWidth  = (int)($origHeight * $targetRatio);
            $srcX = (int)(($origWidth - $newWidth) / 2);
            $srcY = 0;
        } else {
            // Image is taller → crop height
            $newWidth  = $origWidth;
            $newHeight = (int)($origWidth / $targetRatio);
            $srcX = 0;
            $srcY = (int)(($origHeight - $newHeight) / 2);
        }

        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled(
            $resized,
            $image,
            0, 0,
            $srcX, $srcY,
            $targetWidth, $targetHeight,
            $newWidth, $newHeight
        );

        imagedestroy($image);

        return $resized;
    }

    private function compressToTarget($image, string $webpFile): void
    {
        $quality = $this->startQuality;

        do {
            imagewebp($image, $webpFile, $quality);
            clearstatcache(true, $webpFile);

            if (!file_exists($webpFile)) {
                return;
            }

            if (filesize($webpFile) <= $this->targetSize) {
                return;
            }

            $quality -= 5;

        } while ($quality >= $this->minQuality);
    }

    private function supportsWebp(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) &&
               str_contains($_SERVER['HTTP_ACCEPT'], 'image/webp');
    }
}