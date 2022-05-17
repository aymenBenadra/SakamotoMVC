<?php

namespace Core\Helpers;

/**
 * Request Helper Class
 * - Get Request URI
 * - Get Request Method
 * - Get Request Data (POST, GET)
 * 
 * @package Core\Helpers
 * @author Mohammed-Aymen Benadra
 */
class Request
{
    public $uri;
    public $method;
    public $data;

    /**
     * Create a new Request instance from the current request.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->uri = self::uri();
        $this->method = self::method();
        $this->data = self::data();
    }
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $uri === '/' ? $uri : trim($uri, '/');
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
                $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
                return empty($_FILES) ? $data : array_merge($data, $_FILES);
                break;
            case 'PUT':
                return json_decode(file_get_contents('php://input'), true);
                break;
            case 'DELETE':
                return json_decode(file_get_contents('php://input'), true);
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * Get header value
     * 
     * @param  string $header
     * @return string
     */
    public static function header($header)
    {
        $header = strtoupper($header);
        $header = str_replace('-', '_', $header);

        return $_SERVER['HTTP_' . $header] ?? null;
    }

    /**
     * Get Authorization jwt access token
     * 
     * @return string
     */
    public static function authorization()
    {
        // Get authorization token from header
        $auth = isset($_SERVER['Authorization']) && preg_match('/Bearer\s(\S+)/', $_SERVER['Authorization'], $matches) ? $matches[1] : null;

        $auth ??= isset($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches) ? $matches[1] : null;

        return $auth ?? false;
    }

    /**
     * Get Refresh Token from httponly Cookie
     * 
     * @return string
     */
    public static function refreshToken()
    {
        return isset($_COOKIE['auth']) ? $_COOKIE['auth'] : false;
    }
}
