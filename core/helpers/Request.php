<?php

namespace Core\Helpers;

class Request
{
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    /**
     * Fetch the data associated with request.
     *
     * @return array
     */
    public static function data()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return empty($_GET) ? [] : $_GET;
                break;
            case 'POST':
                return $_POST;
                break;
            // case 'PUT':
            //     parse_str(file_get_contents('php://input'), $_PUT);
            //     return $_PUT;
            //     break;
            // case 'DELETE':
            //     parse_str(file_get_contents('php://input'), $_DELETE);
            //     return $_DELETE;
            //     break;
            default:
                return [];
                break;
        }
    }
}
