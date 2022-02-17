<?php

namespace Core;

use Exception;

/**
 * Router Class
 * - Load routes and defines routing rules
 * - Load the apprepriate controller and calls its method
 * 
 * @package App\Core
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
        'POST' => []
        // 'PUT' => [],
        // 'DELETE' => []
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
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    // /**
    //  * Register a PUT route.
    //  *
    //  * @param string $uri
    //  * @param string $controller
    //  */
    // public function put($uri, $controller)
    // {
    //     $this->routes['PUT'][$uri] = $controller;
    // }

    // /**
    //  * Register a DELETE route.
    //  *
    //  * @param string $uri
    //  * @param string $controller
    //  */
    // public function delete($uri, $controller)
    // {
    //     $this->routes['DELETE'][$uri] = $controller;
    // }

    /**
     * Load the requested URI's associated controller method.
     *
     * @param string $uri
     * @param string $requestType
     * @param array $data
     */
    public function direct($uri, $requestType, $data)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            $uri = explode('@', $this->routes[$requestType][$uri]);
            return $this->callAction(
                $uri[0],
                $uri[1],
                $data
            );
        }

        return $this->callAction("Pages", "notFound", []);
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     * @param array $data
     */
    protected function callAction($controller, $action, $data)
    {
        $controllerName = "App\\Controllers\\{$controller}";
        $controller = new $controllerName;

        if (!method_exists($controller, $action)) {
            throw new Exception(
                "{$controllerName} does not respond to the {$action} action."
            );
        }

        if (empty($data)) {
            return $controller->$action();
        }

        return $controller->$action($data);
    }

    /**
     * Redirect to the given URI.
     *
     * @return string
     */
    public static function redirect($uri)
    {
        header("Location: {$uri}");
        exit;
    }
}
