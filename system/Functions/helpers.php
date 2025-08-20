<?php

if (!function_exists('day_name')) {

    function day_name($isodate)
    {

        // Buat object DateTime dengan zona waktu Asia/Jakarta (WIB)
        $date = new DateTime($isodate, new DateTimeZone('Asia/Jakarta'));

        // Array nama hari dalam Bahasa Indonesia
        $day_names = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu"
        ];

        // Ambil index hari (0 = Minggu, 6 = Sabtu)
        $index = $date->format('w');

        return $day_names[$index];
    }
}


/**
 * Bersihkan string: hapus <script> dan <style>, semua tag HTML, dan trim.
 */
if (!function_exists('sanitize_string')) {

    function sanitize_string($input)
    {
        // hapus <script>...</script> dan <style>...</style>
        $clean = preg_replace('#<script.*?>.*?</script>#is', '', $input);
        $clean = preg_replace('#<style.*?>.*?</style>#is', '', $clean);

        // hapus tag HTML lain
        $clean = strip_tags($clean);

        // trim spasi dan control character
        $clean = trim($clean);

        return $clean;
    }
}

/**
 * Rekursif sanitize array / value.
 */
if (!function_exists('sanitize_recursive')) {

    function sanitize_recursive($value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = sanitize_recursive($v);
            }
            return $value;
        }
        if (is_string($value)) {
            return sanitize_string($value);
        }
        return $value; // int, bool, null tetap
    }
}

/**
 * Override superglobals (opsional).
 * Panggil ini sekali di bootstrap supaya $_GET/$_POST sudah bersih.
 */
if (!function_exists('sanitize_inputs')) {

    function sanitize_inputs(): void
    {
        $_GET = sanitize_recursive($_GET);
        $_POST = sanitize_recursive($_POST);
        $_REQUEST = sanitize_recursive($_REQUEST); // kalau dipakai
    }
}

// helper cepat untuk ngambil dengan default
if (!function_exists('input_get')) {

    function input_get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
}

if (!function_exists('input_post')) {
    function input_post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
}

//-------------

if (!function_exists('is_browser')) {

    function is_browser()
    {
        $browserList[] = 'chrome';
        $browserList[] = 'chromium';
        $browserList[] = 'firefox';
        $browserList[] = 'edge';
        $browserList[] = 'opera';
        $browserList[] = 'safari';
        $browserList[] = 'samsungbrowser';
        $browserList[] = 'redmi';
        $browserList[] = 'iphone';
        $browserList[] = 'uc browser';
        $browserList[] = 'vivaldi';
        $browserList[] = 'brave';
        $browserList[] = 'maxthon';
        $browserList[] = 'palemoon';
        $browserList[] = 'blisk';
        $browserList[] = 'thorium';
        $browserList[] = 'yandex';
        $browserList[] = 'puffin';
        $browserList[] = 'qqbrowser';
        $browserList[] = 'coc coc';
        $browserList[] = 'whale';
        $browserList[] = '2345 explorer';
        $browserList[] = 'icecat';
        $browserList[] = 'lunascape';
        $browserList[] = 'seznam browser';
        $browserList[] = 'sleipnir';
        $browserList[] = 'sputnik';
        $browserList[] = 'oculus';
        $browserList[] = 'salamweb';
        $browserList[] = 'swing';
        $browserList[] = 'safe exam';
        $browserList[] = 'colibri';
        $browserList[] = 'xvast';
        $browserList[] = 'atom';
        $browserList[] = 'netcast';
        $browserList[] = 'lg browser';

        //print_r($_SERVER);

        $nonBrowser = true;
        foreach ($browserList as $key) {
            if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $key) !== false)) {
                $nonBrowser = false;
                break;
            }
        }

        $isAjax = (isset($_SERVER['X_REQUESTED_WITH']) && strtolower($_SERVER['X_REQUESTED_WITH']) == 'xmlhttprequest');
        $isAcceptJSON = (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false));

        if ($isAjax || $isAcceptJSON || $nonBrowser) {
            return false;
        }
        return true;
    }
}

if (!function_exists('encrypt')) {

    function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_KEY), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
}

if (!function_exists('decrypt')) {

    function decrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_KEY), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
}

if (!function_exists('escape')) {

    function escape($value)
    {
        $return = '';
        for ($i = 0; $i < strlen($value); ++$i) {
            $char = $value[$i];
            $ord = ord($char);
            if ($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
                $return .= $char;
            else
                $return .= '\\x' . dechex($ord);
        }
        return $return;
    }
}

if (!function_exists('inputFilter')) {

    function inputFilter($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $element) {
                $data[$key] = inputFilter($element);
            }
        } else {
            $data = trim(htmlentities(strip_tags($data)));
            $data = stripslashes($data);
            $data = escape($data);
        }
        return $data;
    }
}

if (!function_exists('redirect')) {

    function redirect($relative, $cookie = [])
    {
        if ($cookie) {
            foreach ($cookie as $key => $val) {
                setcookie($key, $val, time() + 60 * 60 * 24 * 30);
            }
        }

        $url = BASE_URL . "/" . trim($relative, "/");
        header('Location: ' . $url, true, $statusCode);
        die();
    }
}

if (!function_exists('pd')) {

    function pd($var)
    {
        print "<style>
        * {
          box-sizing: border-box;
          font-family: 'Inter', 'Open Sans', sans-serif;
        }

        html,body {
          height: 100%;
          width: 100%;
        }

        .container {
          align-items: center;
          display: flex;
          justify-content: center;
          flex-direction: column;
          height: 100%;
          width: 100%;
        }</style>";

        print "<div class='container'>";
        print "<div style=\"width:80%; background-color:#efefef; border: 1px solid #F7B5CA; padding: 0; border-radius: 0.25rem\">";
        print "<div style=\"width:100%; background-color:EECAD5; padding: 0.75rem 1rem; margin: 0\"><b>Kesalahan</b>";
        print "</div>";

        print "<div style=\"width:100%; background-color: #FFE6E6; padding: 0.5rem 1rem; margin: 0\">";
        //print "<pre><xmp>";
        if (is_array($var)) {
            print_r($var);
        } else {
            print $var;
        }
        print "<br><br>";
        print "File: " . __FILE__ . "\n";
        print "Line: " . __LINE__ . "\n";
        //print "</xmp></pre>";
        print "</div>";
        print "</div>";
        print "<p style='margin-top:2rem'><img src='" . BASE_URL . "/assets/img/kancil.png' height=50></p>";
        print "</div>";
        die();
    }
}
