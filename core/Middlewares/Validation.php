<?php

namespace Core\Middlewares;

use Core\Router;
use Core\Helpers\{Request, Response, Validator};

/**
 * Verification class
 * 
 * @package    Core
 * @author     Mohammed-Aymen Benadra
 */
class Validation
{
    /**
     * Rules to validate
     * 
     * @var array
     */
    private $rules = [];

    /**
     * Validation constructor
     */
    public function __construct()
    {
        Response::headers();
        $this->rules = require_once '../app/config/validation-rules.php';
    }

    /**
     * Validate the request data with the rules defined in the config file
     * 
     * @param  string $fields
     * @return void
     */
    public function handle(string $fields, string $scope = 'all')
    {
        $fields = explode('|', $fields);
        $data = self::sanitize(Request::data());
        $rulesToValidate = [];

        // Get the rules to validate 
        foreach ($fields as $field) {
            if (strpos($field, '/')) {
                $field = explode('/', $field);

                if (isset($data[$field[0]])) {
                    $rulesToValidate[$field[0]] = $this->rules[$scope][$field[0]];
                } else {
                    $rulesToValidate[$field[1]] = $this->rules[$scope][$field[1]];
                }
            } else {
                $rulesToValidate[$field] = $this->rules[$scope][$field];
            }
        }

        // if data fields count is not equal to the rules count, return false
        if (count($data) !== count($rulesToValidate)) {
            Router::abort(400, [
                'message' => 'The request data fields count is not equal to the rules count'
            ]);
        }

        // Chack data fields validity
        if (array_diff(array_keys($data), array_keys($rulesToValidate))) {
            Router::abort(400, [
                'message' => 'The request data fields are not valid'
            ]);
        }

        // Validate each field
        foreach ($rulesToValidate as $field => $rules) {
            $value = $data[$field];
            $rules = explode('|', $rules);

            $result = Validator::validate($value, $rules);

            if ($result !== true) {
                Router::abort(400, [
                    'message' => ucfirst($field) . ': ' . $result[0]
                ]);
            }
        }
    }


    /**
     * Sanitize data
     * 
     * @param  array $data
     * @return array
     */
    public static function sanitize($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::sanitize($value);
            } else {
                $data[$key] = htmlspecialchars($value);
            }
        }
        return $data;
    }
}
