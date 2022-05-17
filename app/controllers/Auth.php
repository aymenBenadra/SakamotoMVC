<?php

namespace App\Controllers;

use App\Models\Example;
use Core\{Controller, Router};
use Core\Helpers\Request;
use Core\Helpers\Response;
use Exception;
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
                'message' => 'Server error'
            ]));
        }

        Response::send([
            'message' => 'Registered successfully',
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

        Response::send(
            $example
        );
    }

    /**
     * Register new example using username and password with JWT
     * 
     * @param array $data
     * @return void
     */
    public function registerJWT($data = [])
    {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if (!$this->model('Example')->add($data)) {
            Router::abort(500, json_encode([
                'message' => 'Server error'
            ]));
        }

        unset($data['password']);

        Response::send([
            'message' => 'Registered successfully',
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

        if (!password_verify($data['password'], $example->password)) {
            Router::abort(401, json_encode([
                'message' => 'Invalid password'
            ]));
        }

        // Create Refresh Token
        $refreshToken = $this->createToken($example->username, $_ENV['JWT_REFRESH_EXP_DELTA_SECONDS']);

        setcookie(
            name: 'auth',
            value: $refreshToken,
            expires_or_options: time() + $_ENV['JWT_REFRESH_EXP_DELTA_SECONDS'],
            httponly: true
        );
        // Create Access Token
        $accessToken = $this->createToken($example->username, $_ENV['JWT_ACCESS_EXP_DELTA_SECONDS']);

        unset($example->password, $example->id);
        $example->avatar = file_get_contents(dirname(dirname(__DIR__)) . "/public/identicons/" . $example->avatar);
        $example->accessToken = $accessToken;

        Response::send(
            $example
        );
    }

    /**
     * Refresh Access Token
     * 
     * @param array $data
     * @return void
     */
    public function refresh()
    {
        $refreshToken = Request::refreshToken();

        // Check if refresh token is valid
        try {
            if (!$refreshToken) {
                throw new Exception('No refresh token found');
            }

            $token = JWT::decode($refreshToken, new Key($_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALGORITHM']));

            // Check if Example exists
            $example = (new Example())->getBy('username', $token->sub);
            if (!$example) {
                throw new Exception('Example not found');
            }

            Response::send([
                'accessToken' => $this->createToken($example->username, $_ENV['JWT_ACCESS_EXP_DELTA_SECONDS'])
            ]);
        } catch (Exception $e) {
            Router::abort(401, [
                'message' => 'Unauthorized: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Logout an User
     * 
     * @return void
     */
    public function logoutJWT()
    {
        setcookie(name: 'auth', value: '', expires_or_options: time() - 1, httponly: true);

        Response::send([
            'message' => 'Logged out successfully!'
        ]);
    }

    /**
     * Get current authenticated User
     * 
     * @return object
     */
    public static function userJWT()
    {
        $jwt = Request::authorization();

        if (!$jwt) {
            return null;
        }

        $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALGORITHM']));

        $example = (new Example)->getBy('username', $token->sub);

        unset($example->password,$example->id);

        return$example;
    }

    /**
     * Create token for user
     * 
     * @param string $sub
     * @param int $exp
     * @return string
     */
    public static function createToken($sub, $exp)
    {
        $secret_key = $_ENV['JWT_SECRET_KEY'];
        $issuer_claim = $_ENV['SERVER_ADDRESS']; // this can be the servername
        $audience_claim = $_ENV['CLIENT_ADDRESS'];
        $issuedat_claim = time(); // issued at
        $expire_claim = $issuedat_claim + $exp; // expire time in seconds (24 hours from now)
        $payload = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,
            "sub" => $sub
        );

        return JWT::encode($payload, $secret_key, $_ENV['JWT_ALGORITHM']);
    }
}
