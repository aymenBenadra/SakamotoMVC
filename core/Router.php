<?php

namespace Core;

use Core\Helpers\{Request, Response};
use Exception;

/**
 * Router Class
 * - Load routes and defines routing rules
 * - Load the apprepriate controller and calls its method
 * 
 * @package App\Core
 * @uses Exception
 * @author Mohammed-Aymen Benadra
 */
class Router
{
    /**
     * All registered routes.
     *
     * @var array
     */
    public $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    /**
     * Load a user's routes file.
     *
     * @param string $file
     * @return object
     */
    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $route
     * @param array $middlewares
     * @return void
     */
    public function get($uri, $route, $middlewares = [])
    {
        $this->routes['GET'][$uri] = [
            'route' => $route,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $route
     * @param array $middlewares
     * @return void
     */
    public function post($uri, $route, $middlewares = [])
    {
        $this->routes['POST'][$uri] = [
            'route' => $route,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Register a PUT route.
     *
     * @param string $uri
     * @param string $route
     * @param array $middlewares
     * @return void
     */
    public function put($uri, $route, $middlewares = [])
    {
        $this->routes['PUT'][$uri] = [
            'route' => $route,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Register a DELETE route.
     *
     * @param string $uri
     * @param string $route
     * @param array $middlewares
     * @return void
     */
    public function delete($uri, $route, $middlewares = [])
    {
        $this->routes['DELETE'][$uri] = [
            'route' => $route,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Load the requested URI's associated controller method and middleware.
     *
     * @param Request $request
     */
    public function direct($request)
    {
        // preflight request to check if the request is valid or not
        if ($request->method() === 'OPTIONS') {
            Response::headers();
            Response::code();
            exit;
        }

        if (array_key_exists($request->uri, $this->routes[$request->method])) {
            $uri = explode('@', $this->routes[$request->method][$request->uri]['route']);

            // Load the controller
            $controller = "App\\Controllers\\{$uri[0]}";
            $method = $uri[1];

            // Load the middlewares
            $middlewares = $this->routes[$request->method][$request->uri]['middlewares'] ?? [];

            // Call the Middlewares and stop the execution if one of them returns false
            if (count($middlewares) > 0) {
                foreach ($middlewares as $middleware) {
                    $this->callMiddleware($middleware);
                }
            }

            $controller = new $controller;


            $this->callAction(
                $controller,
                $method,
                $request->data
            );
        }

        Router::abort(404, 'Not Found');
    }

    /**
     * Load and call Middlewares
     * 
     * @param string $middleware
     * @return void
     */
    public function callMiddleware($middleware)
    {
        $middleware = explode('@', $middleware);
        $param = $middleware[1];
        $scope = $middleware[2] ?? null;

        $middleware = "Core\\Middlewares\\{$middleware[0]}";

        // Instantiate the middleware class
        $middleware = new $middleware;

        // Call the middleware method
        if ($param && $scope) {
            $middleware->handle($param, $scope);
        } elseif ($param && !$scope) {
            $middleware->handle($param);
        } else {
            $middleware->handle();
        }
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     * @param array $data
     * @return void
     */
    protected function callAction($controller, $action, $data)
    {
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        if (empty($data)) {
            $controller->$action();
        }

        $controller->$action($data);
    }

    /**
     * Redirect to the given URI.
     *
     * @return string
     */
    public static function redirect($uri, $data = [], $statusCode = 302)
    {
        if (!empty($data)) {
            $uri .= '?' . http_build_query($data);
        }
        Response::code($statusCode);
        header("Location: {$uri}", true, $statusCode);
        exit;
    }

    /**
     * Abort the execution of the script with a given status code and message.
     * 
     * @param int $statusCode
     * @param string $message
     * @return void
     */
    public static function abort($statusCode, $message)
    {
        Response::code($statusCode);
        Response::send($message);
    }
}
