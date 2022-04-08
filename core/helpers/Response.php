<?php

namespace Core\Helpers;

class Response
{
    /**
     * Set default headers
     */
    public static function headers(
        $contentType = 'application/json',
        $allowOrigin = '*',
        $allowMethods = 'GET, POST, PUT, DELETE, OPTIONS'
    ) {
        header('Content-Type: ' . $contentType . '; charset=UTF-8');
        header('Access-Control-Allow-Origin: ' . $allowOrigin);
        header('Access-Control-Allow-Methods: ' . $allowMethods);
        header('Access-Control-Allow-Headers: clientRef, Content-Type, Authorization, X-Requested-With');
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
        exit(json_encode($response));
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
