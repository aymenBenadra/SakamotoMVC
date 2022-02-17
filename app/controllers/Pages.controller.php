<?php

namespace App\Controllers;

use Core\Controller;

class Pages extends Controller
{
    public function index()
    {
        echo 'Home page';
        $this->view('pages/index');
    }

    public function about()
    {
        echo 'About page';
        $this->view('pages/about');
    }

    public function notFound()
    {
        echo '404 page';
        $this->view('pages/404');
    }
}
