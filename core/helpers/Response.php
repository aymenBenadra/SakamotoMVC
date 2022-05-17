<?php

namespace Core\Helpers;

class Response
{
    /**
     * Set default headers
     */
    public static function headers(
        $contentType = 'application/json',
        $allowMethods = 'GET, POST, PUT, DELETE, OPTIONS',
        $allowHeaders = 'X-Requested-With, Content-Type, Authorization'
    ) {
        header('Content-Type: ' . $contentType . '; charset=UTF-8');
        header('Access-Control-Allow-Origin: ' . $_ENV['CLIENT_ADDRESS']);
        header('Access-Control-Allow-Methods: ' . $allowMethods);
        header('Access-Control-Allow-Headers: ' . $allowHeaders);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * Send response
     * 
     * @param  mixed $response
     * @return void
     */
    public static function send($response)
    {
        if (is_array($response) || is_object($response)) {
            $response = json_encode($response);
        }
        exit($response);
    }

    /**
     * Set response code header
     * 
     * @param int $code
     * @return void
     */
    public static function code($code = 200)
    {
        http_response_code($code);
    }
}
