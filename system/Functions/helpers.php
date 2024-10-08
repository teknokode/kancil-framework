<?php

if (!function_exists('isBrowser')) 
{
    function isBrowser()
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
        foreach($browserList as $key)
        {
            if ( isset($_SERVER['HTTP_USER_AGENT']) && (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $key) !== false) )
            {
                $nonBrowser = false;
                break;
            } 
        }

        $isAjax = ( isset($_SERVER['X_REQUESTED_WITH']) && strtolower($_SERVER['X_REQUESTED_WITH']) == 'xmlhttprequest');
        $isAcceptJSON = ( isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) );
        
        if ($isAjax || $isAcceptJSON || $nonBrowser)
        {
            return false;
        }
        return true;
    }
}

if (!function_exists('encrypt')) 
{
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

if (!function_exists('decrypt')) 
{
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

if (!function_exists('escape')) 
{
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

if (!function_exists('inputFilter')) 
{
    function inputFilter( $data )
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

if (!function_exists('redirect')) 
{
    function redirect( $relative , $cookie = []) 
    {
       if ($cookie)
       {
            foreach($cookie as $key => $val)
            {
                setcookie( $key, $val, time() + 60*60*24*30);
            }
       }

       $url = BASE_URL . "/".trim($relative,"/");
       header('Location: ' . $url, true, $statusCode);
       die();
    }
}

if (!function_exists('pd')) 
{
    function pd( $var )
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
        if (is_array($var))
        {
            print_r($var);
        } else {
            print $var;
        }
        print "<br><br>";
        print "File: ".__FILE__."\n";
        print "Line: ".__LINE__."\n";
        //print "</xmp></pre>";
        print "</div>";
        print "</div>";
        print "<p style='margin-top:2rem'><img src='".BASE_URL."/assets/img/kancil.png' height=50></p>";
        print "</div>";
        die();
    }
}