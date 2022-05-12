<?php

namespace App\Controllers;

use App\Models\Example;
use Core\{Controller, Router};
use Core\Helpers\Request;
use Core\Helpers\Response;
use Firebase\JWT\{JWT, Key};

/**
 * Auth Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Auth extends Controller
{
    /**
     * Set headers for JSON response
     *
     * @return void
     */
    public function __construct()
    {
        Response::headers();
        Response::code();
    }

    /**
     * Register a new user
     * 
     * @param array $data
     * @return void
     */
    public function registerHeader($data = [])
    {
        // Generate headerRef to data
        $data['headerRef'] = uniqid("example_");

        if (!$this->model('Example')->add($data)) {
            Router::abort(500, json_encode([
                'status' => 'error',
                'message' => 'Server error'
            ]));
        }

        $example = $this->model('Example')->get(
            $this->model('Example')->getLastInsertedId()
        );

        Response::send([
            'status' => 'success',
            'data' => $example
        ]);
    }

    /**
     * Login a user using headerRef
     * 
     * @param array $data
     * @return void
     */
    public function login($data = [])
    {
        $example = $this->model('Example')->getBy('headerRef', $data['headerRef']);

        if (!$example) {
            Router::abort(404, json_encode([
                'status' => 'error',
                'message' => 'example not found'
            ]));
        }

        Response::send([
            'status' => 'success',
            'data' => $example
        ]);
    }

    /**
     * Register new example using username and password with JWT
     * 
     * @param array $data
     * @return void
     */
    public function registerJWT($data = [])
    {
        $example = $this->model('Example')->getBy('username', $data['username']);

        if ($example) {
            Router::abort(400, json_encode([
                'status' => 'error',
                'message' => 'example already exists'
            ]));
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if (!$this->model('Example')->add($data)) {
            Router::abort(500, json_encode([
                'status' => 'error',
                'message' => 'Server error'
            ]));
        }

        unset($data['password']);

        Response::send([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Login an example using username and password with JWT
     * 
     * @param array $data
     * @return void
     */
    public function loginJWT($data = [])
    {
        $example = $this->model('Example')->getBy('username', $data['username']);

        if (!$example) {
            Router::abort(404, json_encode([
                'status' => 'error',
                'message' => 'example not found'
            ]));
        }

        if (!password_verify($data['password'], $example->password)) {
            Router::abort(401, json_encode([
                'status' => 'error',
                'message' => 'Invalid password'
            ]));
        }

        $secret_key = $_ENV['JWT_SECRET_KEY'];
        $issuer_claim = $_ENV['SERVER_ADDRESS']; // this can be the servername
        $audience_claim = $_ENV['CLIENT_ADDRESS'];
        $issuedat_claim = time(); // issued at
        // $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 600; // expire time in seconds (10 minutes)
        $payload = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            // "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "sub" => $example->username
        );

        $jwt = JWT::encode($payload, $secret_key, "HS256");

        // Set expirable cookie for JWT
        setcookie('jwt', $jwt, $expire_claim, "/", $_ENV['SERVER_ADDRESS'], false, true);

        Response::code();
        Response::send(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );
    }

    /**
     * Get current authenticated User
     * 
     * @return object
     */
    public static function user()
    {
        $jwt = Request::authorization();

        $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], "HS256"));

        return (new Example)->getBy('username', $token->sub);
    }
}
