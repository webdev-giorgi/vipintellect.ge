<?php
namespace functions;

class getWebpUrl
{
    public function index($url, $crop = array())
    {
        // ---------- CONFIG ----------
        $allowedHosts = array('vipintellect.ge');
        $cacheDir     = 'cache/';
        $cacheUrl     = '/cache/';
        $cacheTtl     = 60 * 60 * 24 * 30; // 30 days
        $quality      = 75;
        $allowedExt   = array('jpg', 'jpeg', 'png', 'gif');
        // ----------------------------

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }

        $parts = parse_url($url);

        if (
            !isset($parts['scheme']) ||
            !isset($parts['host']) ||
            !isset($parts['path']) ||
            ($parts['scheme'] !== 'http' && $parts['scheme'] !== 'https') ||
            !in_array($parts['host'], $allowedHosts, true)
        ) {
            return '';
        }

        $localPath = $_SERVER['DOCUMENT_ROOT'] . $parts['path'];

        if (!is_file($localPath)) {
            return '';
        }

        $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            return '';
        }

        // Cache key MUST include crop size
        $cropKey = '';
        if (is_array($crop) && count($crop) === 2) {
            $cropKey = (int)$crop[0] . 'x' . (int)$crop[1];
        }

        $hash     = hash('sha256', $parts['path'] . '|' . $cropKey);
        $jpegFile = $cacheDir . $hash . '.jpg';
        $jpegUrl  = $cacheUrl . $hash . '.jpg';

        if (is_file($jpegFile) && (time() - filemtime($jpegFile)) < $cacheTtl) {
            return $jpegUrl;
        }

        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        // ---------- LOAD IMAGE ----------
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $image = @imagecreatefromjpeg($localPath);
                break;

            case 'png':
                $image = @imagecreatefrompng($localPath);
                break;

            case 'gif':
                $image = @imagecreatefromgif($localPath);
                break;

            default:
                return '';
        }

        if (!$image) {
            return '';
        }

        // ---------- FLATTEN TRANSPARENCY ----------
        $srcW = imagesx($image);
        $srcH = imagesy($image);

        $base = imagecreatetruecolor($srcW, $srcH);
        $white = imagecolorallocate($base, 255, 255, 255);
        imagefilledrectangle($base, 0, 0, $srcW, $srcH, $white);
        imagecopy($base, $image, 0, 0, 0, 0, $srcW, $srcH);
        imagedestroy($image);
        $image = $base;

        // ---------- OPTIONAL CROP ----------
        $doCrop = false;
        if (
            is_array($crop) &&
            count($crop) === 2 &&
            (int)$crop[0] > 0 &&
            (int)$crop[1] > 0
        ) {
            $targetW = (int)$crop[0];
            $targetH = (int)$crop[1];

            // Only crop if the requested size is different from the image size
            if ($srcW != $targetW || $srcH != $targetH) {
                $doCrop = true;
            }
        }

        if ($doCrop) {
            $srcRatio    = $srcW / $srcH;
            $targetRatio = $targetW / $targetH;

            if ($srcRatio > $targetRatio) {
                // Source wider
                $newH = $srcH;
                $newW = (int)($srcH * $targetRatio);
                $srcX = (int)(($srcW - $newW) / 2);
                $srcY = 0;
            } else {
                // Source taller
                $newW = $srcW;
                $newH = (int)($srcW / $targetRatio);
                $srcX = 0;
                $srcY = (int)(($srcH - $newH) / 2);
            }

            $cropped = imagecreatetruecolor($targetW, $targetH);
            $white = imagecolorallocate($cropped, 255, 255, 255);
            imagefilledrectangle($cropped, 0, 0, $targetW, $targetH, $white);

            imagecopyresampled(
                $cropped,
                $image,
                0, 0,
                $srcX, $srcY,
                $targetW, $targetH,
                $newW, $newH
            );

            imagedestroy($image);
            $image = $cropped;
        }

        // ---------- SAVE ----------
        $result = @imagejpeg($image, $jpegFile, $quality);
        imagedestroy($image);

        if (!$result) {
            return '';
        }

        return $jpegUrl;
    }
}