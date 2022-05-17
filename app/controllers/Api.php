<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * API Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Api extends Controller
{
    private $model;
    /**
     * Set headers for JSON response
     *
     * @return void
     */
    public function __construct()
    {
        // Set default Model for this controller
        $this->model = $this->model('Example');

        Response::headers();
        Response::code();
    }

    /**
     * Get all Examples
     *
     * @return void
     */
    public function index()
    {
        $examples = $this->model->getAll();

        if ($examples === false) {
            Router::abort(500, json_encode([
                'message' => 'Server error'
            ]));
        }

        Response::send(
            $examples
        );
    }

    /**
     * Get an example
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        Response::send(
            $this->model->get($data['id'])
        );
    }

    /**
     * Store an example
     *
     * @param array $data
     * @return void
     */
    public function store($data = [])
    {
        // Generate exampleRef to data
        $data['exampleRef'] = uniqid("example_");

        if (!$this->model->add($data)) {
            Router::abort(500, json_encode([
                'message' => 'Server error'
            ]));
        }

        Response::send([
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Update an example
     *
     * @param array $data
     * @return void
     */
    public function update($data = [])
    {
        $id = $data['id'];
        unset($data['id']);

        if (!$this->model->update($id, $data)) {
            Router::abort(500, json_encode([
                'message' => 'Server error'
            ]));
        }

        Response::send([
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Delete an example
     *
     * @param array $data
     * @return void
     */
    public function delete($data = [])
    {
        if (!$this->model->delete($data['id'])) {
            Router::abort(500, json_encode([
                'message' => 'Server error'
            ]));
        }

        Response::send([
            'message' => 'Deleted successfully'
        ]);
    }
}
