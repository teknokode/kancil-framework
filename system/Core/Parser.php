<?php
namespace Kancil\Core;

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

class Parser 
{
    protected $handlebars;

    public function __construct( )
    {
        # Set the partials files
        //$partialsDir = __DIR__."/../../../app/Views";
        $partialsDir = APP_PATH."/app/Views";

        $partialsLoader = new FilesystemLoader($partialsDir,
            [
                "extension" => "html"
            ]
        );

        # We'll use $handlebars throughout this the examples, assuming the will be all set this way
        $this->handlebars = new Handlebars([
            "loader" => $partialsLoader,
            "partials_loader" => $partialsLoader
        ]);
    }

    public function render( $file, $param = [])
    {
        $data["base_url"] = BASE_URL;
        $data = array_merge($param, $data);

        $cache_file = APP_PATH."/storage/Caches/".substr(base64_encode($file.json_encode($param)),0,100).".html";
        //pd($cache_file);

        if ($this->isCacheExpired($cache_file, 0)) {

            $content = $this->handlebars->render($file, $data);
            return $this->savePrintCache($cache_file, $content);

        } else {

            return file_get_contents($cache_file);
        }


        // print "<pre>";
        // print_r($data);

        //return $this->handlebars->render($file, $data);
    }

    // Check file time of cache
    private function isCacheExpired($cache_file, $minutes = 0)
    {
        // Minutes = 0, cache is disabled
        if ($minutes==0) return true;
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

    // Save and print cache 
    private function savePrintCache( $cache_file, $content )
    {
        $content .= "<!-- st.mun.57 | 28.11.72 | " . date("Y.m.d H:i:s") . " -->";
        file_put_contents( $cache_file, $content);
        //echo $content;
        return $content;
    }

}