<?php

namespace Kancil\Core;

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

// Seharusnya ini sudah autoload pada class controller

class Parser
{
    protected $handlebars;
    protected $currentTheme;

    // Pengaturan awal Handlebars
    //public function __construct($theme = 'default')
    public function __construct()
    {
        // Apakah ada setting THEME di .env, bila tidak set default
        $default = get_value( THEME, 'default'); 
        // Apakah ada current theme dari code
        $theme = get_value( $this->currentTheme, $default );

        //$partialsDir = APP_PATH."/app/Views";
        $partialsDir = APP_PATH . "/app/Themes/" . $theme;

        $partialsLoader = new FilesystemLoader(
            $partialsDir,
            [
                "extension" => "html"
            ]
        );

        $this->handlebars = new Handlebars([
            "loader" => $partialsLoader,
            "partials_loader" => $partialsLoader
        ]);
    }

    // Set theme secara program
    public function setTheme($theme)
    {
        $this->currentTheme =  $theme;
    }

    // Render sebuah halaman dengan parameter data
    public function render($file, $param = [])
    {
        $data["base_url"] = BASE_URL;
        $data = array_merge($param, $data);

        $cache_file = APP_PATH . "/storage/Caches/" . substr(base64_encode($file . json_encode($param)), 0, 100) . ".html";

        if ($this->isCacheExpired($cache_file, 0)) {
            $content = $this->handlebars->render($file, $data);
            return $this->savePrintCache($cache_file, $content);
        } else {

            return file_get_contents($cache_file);
        }
    }

    // Periksa jam modifikasi file cache
    private function isCacheExpired($cache_file, $minutes = 0)
    {
        // Kalau minutes = 0, cache dimatikan
        if ($minutes == 0) return true;
        if (file_exists($cache_file)) {
            $modified_time = filemtime($cache_file);
            $time_diff = time() - $modified_time;
            if ($time_diff < ($minutes * 60)) {
                return false;
            }
            unlink($cache_file);
        }
        return true;
    }

    // Simpan dan tampilkan cache
    private function savePrintCache($cache_file, $content)
    {
        $content .= "<!-- mac.gyver.57 | 28.11.72 | " . date("Y.m.d H:i:s") . " -->";
        file_put_contents($cache_file, $content);
        return $content;
    }
}
