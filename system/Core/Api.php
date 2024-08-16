<?php
namespace Kancil\Core;

class Api
{
    public function requestMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function responseJSON($data = [], $status="Success", $message = "Ok", $code = 200)
    {
        $response = ["code" => $code, "status" => $status, "message" => $message, "data" => $data];
        http_response_code($code);
        header("Server: Kancil");
        header("Content-Type: application/json; charset=utf-8");
        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        die();
    }

    public function responseError($message = "Terjadi kesalahan", $code = 400, $data = [])
    {
        $this->responseJSON($data, "Error", $message, $code);
    }

    public function requestAuth()
    {
        $reqHeaders = $this->requestHeaders();
        $reqBearer = @$reqHeaders["Authorization"];
        return trim(str_replace("Bearer", "", $reqBearer));
    }

    public function requestJSON()
    {
        return json_decode(file_get_contents("php://input", true), true);
    }

    public function requestHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) != "HTTP_") {
                continue;
            }
            $header = str_replace(" ","-", ucwords(str_replace("_", " ", strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }
}
