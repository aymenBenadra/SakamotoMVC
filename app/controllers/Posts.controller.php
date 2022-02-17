<?php

namespace App\Controllers;

use Core\Controller;
use Core\Router;
use Exception;

class Posts extends Controller
{
    private $model;

    /**
     * Initialize Post model
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = $this->model('Post');
    }

    /**
     * Go to Posts index page
     *
     * @return void
     */
    public function index()
    {
        $posts = $this->model->getAll();

        $this->view('posts/index', compact('posts'));
    }

    /**
     * Go to Post details page
     *
     * @param  mixed $data
     * @return void
     */
    public function show($data = [])
    {
        try {
            if (empty($data) || !isset($data['id'])) {
                throw new Exception('Post ID is not specified');
            }

            extract($data);

            $post = $this->model->get($id);

            if (!$post) {
                throw new Exception('Post does not exist');
            } else {
                $this->view('posts/show', compact('post'));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Go to Post creation page
     *
     * @return void
     */
    public function create()
    {
        $this->view('posts/create');
    }

    /**
     * Go to Post edit page
     *
     * @param  mixed $data
     * @return void
     */
    public function edit($data = [])
    {
        try {
            if (empty($data) || !isset($data['id'])) {
                throw new Exception('Post ID is not specified');
            }

            extract($data);

            $post = $this->model->get($id);

            if (!$post) {
                throw new Exception('Post does not exist');
            } else {
                $this->view('posts/edit', compact('id', 'post'));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Store new Post record in database
     *
     * @param  mixed $data
     * @return void
     */
    public function store($data = [])
    {
        try {
            if (empty($data) || !isset($data['title'])) {
                throw new Exception('Post data is not specified');
            }

            if (!$this->model->add($data)) {
                throw new Exception('Arrgh! Something went wrong');
            } else {
                Router::redirect('/public/post?id=' . $this->model->getLastInsertedId());
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Update existing Post in database
     *
     * @param  mixed $data
     * @return void
     */
    public function update($data = [])
    {
        try {
            if (empty($data['id']) || !isset($data['id'])) {
                throw new Exception('Post ID is not specified');
            } else if (!isset($data['title']) || empty($data['title'])) {
                throw new Exception('Post title is not specified');
            }

            extract($data);

            if (!$this->model->update($id, compact('title'))) {
                throw new Exception('Arrgh! Something went wrong');
            } else {
                Router::redirect('/public/post?id=' . $id);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Delete Post from database
     *
     * @param  mixed $data
     * @return void
     */
    public function delete()
    {
        $this->view('posts/delete');
    }

    /**
     * Destroy Post from database
     *
     * @param  mixed $data
     * @return void
     */
    public function destroy($data = [])
    {
        try {
            if (empty($data) || !isset($data['id'])) {
                throw new Exception('Post ID is not specified');
            }

            extract($data);

            if (!$this->model->delete($id)) {
                throw new Exception('Arrgh! Something went wrong');
            } else {
                Router::redirect('/public/posts');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
