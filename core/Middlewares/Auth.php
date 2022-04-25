<?php

namespace Core\Middlewares;

use Core\Router;
use Core\Helpers\Request;
use App\Models\Example;
use Core\Helpers\Response;
use Firebase\JWT\{JWT, Key};
use Exception;

/**
 * Auth class
 * - Handle the request
 * - Check if the user is authenticated
 * - Check if the user is authorized 
 * 
 * @package    Core
 * @author     Mohammed-Aymen Benadra
 * 
 */
class Auth
{
    /**
     * Setup headers
     * 
     * @return void
     */
    public function __construct()
    {
        Response::headers();
    }
    
    /**
     * Handle Authentication using session
     * 
     * @param  mixed $role
     * @return void
     */
    public function handle($role)
    {
        $exampleHeaderRef = Request::header('headerRef') ?? null;
        $exampleJWToken = Request::authorization() ?? null;

        switch ($role) {
            case 'guest':
                if (!$exampleHeaderRef && !$exampleJWToken) {
                    return;
                }
                break;

            case 'client':
                // Check if exampleHeaderRef exists
                if ($this->checkHeader($exampleHeaderRef)) {
                    return;
                }
                break;

            case 'admin':
                // Check if exampleJWToken exists
                if ($this->checkJWT($exampleJWToken)) {
                    return;
                }
                break;

            default:
                break;
        }

        // Redirect to login page if guest and to home page if user
        if (!$exampleHeaderRef && !$exampleJWToken) {
            Router::abort(401, json_encode([
                'status' => 'error',
                'message' => 'Unauthorized: You must be logged in'
            ]));
        } else {
            Router::abort(401, json_encode([
                'status' => 'error',
                'message' => 'Unauthorized: You\'re not allowed to access this page'
            ]));
        }
    }

    /**
     * Checks if exampleHeader exists and returns true if it does
     * 
     * @param  string $exampleHeader
     * @return boolean
     */
    public function checkHeader($exampleHeader)
    {
        if (!$exampleHeader) {
            return false;
        }

        $example = new Example();

        if ($example->getBy('exampleRef', $exampleHeader)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if JWT is valid and returns true if it does
     * 
     * @param  string $jwt
     * @return boolean
     */
    public function checkJWT($jwt)
    {
        if (!$jwt) {
            return false;
        }
        try {
            $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], "HS256"));

            // Check if Example exists
            $example = (new Example())->getBy('id', $token->sub);
            if (!$example) {
                throw new Exception('Admin not found');
            }

            return true;
        } catch (Exception $e) {
            Router::abort(401, json_encode([
                'status' => 'error',
                'message' => 'Unauthorized: ' . $e->getMessage()
            ]));
        }
    }
}
