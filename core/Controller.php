<?php

namespace Core;

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
        if (file_exists(dirname(__DIR__) . "/views/$view.view.php")) {
            require_once dirname(__DIR__) . "/views/$view.view.php";
        } else {
            // View does not exist
            Router::abort(404, "View '$view' does not exist.");
        }
    }

    
    /**
     * Upload photo to server and return its filename if extension is valid
     * 
     * @param  mixed $data
     * @return string
     */
    protected function uploadPhoto($data){
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = pathinfo($data['name'], PATHINFO_EXTENSION);

        if (!in_array($extension, $allowedExtensions)) {
            Router::abort(400, 'Invalid file extension');
        }

        // generate a unique file name
        $fileName = uniqid('', true) . '.' . $extension;

        $path = 'assets/uploads/' . $fileName;

        if (!move_uploaded_file($data['tmp_name'], $path)) {
            Router::abort(500, 'Error uploading file');
        }

        return $fileName;
    }
}
