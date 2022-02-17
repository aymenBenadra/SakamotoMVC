<?php

namespace Core;
use Exception;

/**
 * Base Controller
 * - Load Models
 * - Load Views with Data
 * 
 * @author 		Mohammed-Aymen Benadra
 * @package 	Core
 */
abstract class Controller
{
    /**
     * Load Model
     *
     * @param  mixed $model
     * @return object
     */
    public function model($model)
    {
        $model = "App\\Models\\{$model}";
        return new $model();
    }
    
    /**
     * Load View with data
     *
     * @param  mixed $view
     * @param  mixed $data
     * @return void
     */
    public function view($view, $data = [])
    {
        extract($data);

        // check for view file
        if (file_exists("../app/views/$view.view.php")) {
            require_once "../app/views/$view.view.php";
        } else {
            // View does not exist
            throw new Exception('View does not exist');
        }
    }
}
