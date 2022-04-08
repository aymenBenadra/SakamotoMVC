<?php

namespace App\Controllers;

use Core\{Controller};

/**
 * Posts Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Examples extends Controller
{
    private $model;

    /**
     * Set the model to use
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = $this->model('Example');
    }

    /**
     * Example index page
     *
     * @return void
     */
    public function index()
    {
        $examples = $this->model->getAll() ?? [];

        // Start Session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->view('examples/index', compact('examples'));
    }
}
