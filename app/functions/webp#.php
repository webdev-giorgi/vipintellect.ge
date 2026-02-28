<?php 
namespace functions;

class webp
{
	public function __construct()
	{

	}

	public function index($url)
	{
		// ---------- CONFIG ----------
        $allowedHosts = ['vipintellect.ge'];
        $cacheDir     = 'cache/';
        $cacheUrl     = '/cache/';
        $cacheTtl     = 60 * 60 * 24 * 30; // 30 days
        $quality      = 80;
        $allowedExt   = ['jpg', 'jpeg', 'png', 'gif'];
        // ----------------------------

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }

        $parts = parse_url($url);

        if (
            empty($parts['scheme']) ||
            empty($parts['host']) ||
            empty($parts['path']) ||
            !in_array($parts['scheme'], ['http', 'https'], true) ||
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

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $hash     = hash('sha256', $parts['path']);
        $webpFile = $cacheDir . $hash . '.webp';
        $webpUrl  = $cacheUrl . $hash . '.webp';

        // Cached & fresh → return URL
        if (is_file($webpFile) && (time() - filemtime($webpFile)) < $cacheTtl) {
            return $webpUrl;
        }

        // Load image from disk ONLY (no file_get_contents)
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $image = @imagecreatefromjpeg($localPath);
                break;
            case 'png':
                $image = @imagecreatefrompng($localPath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
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

        if (!imagewebp($image, $webpFile, $quality)) {
            imagedestroy($image);
            return '';
        }

        imagedestroy($image);

        return $webpUrl;
	}
}