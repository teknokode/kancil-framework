<?php
namespace Kancil\Core;

class Api
{
    // Mendapatkan request method dari client
    public function requestMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Mengirimkan response JSON kepada client
    public function responseJSON($data = [], $status="Success", $message = "Ok", $code = 200)
    {
        $response = ["code" => $code, "status" => $status, "message" => $message, "data" => $data];
        //http_response_code($code);
        http_response_code(200);
        header("Server: Kancil");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        die();
    }

    // Mengirimkan response error kepada client
    public function responseError($message = "Terjadi kesalahan", $code = 400, $data = [])
    {
        $this->responseJSON($data, "Error", $message, $code);
    }

    // Membaca header Authorization dari client
    public function requestAuth()
    {
        $reqHeaders = $this->requestHeaders();
        $reqBearer = @$reqHeaders["Authorization"];
        // return trim(str_replace("Bearer", "", $reqBearer));
        if (!empty($reqBearer)) {
            return preg_replace('/^Bearer\s+/i', '', $reqBearer);
        }
        return "";
    }

    // Membaca request JSON dari client (body)
    public function requestJSON()
    {
        return json_decode(file_get_contents("php://input", true), true);
    }

    // Membaca request header dari client
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
