<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use Auth;
use Hash;
use Input;
use Response;
use Validator;

class AuthController extends APIBaseController {

    /**
     * Define the response data to be returned should
     * the user fail to be logged in.
     *
     * @var array
     */
    protected static $FAILED_LOGIN_RESPONSE = ['email' => ['Invalid email or password.']];

    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create new AuthController instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct( UserRepository $userRepository )
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * Attempt to log the user into their account.
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth( LoginRequest $request )
    {
        // Define the login credentials.
        //
        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password
        ];

        // Attempt to the log the user in.
        //
        if( Auth::attempt($credentials, true))
        {
            return $this->response( Auth::user() );
        }

        // Return failed login error response.
        //
        return $this->setError( self::$FAILED_LOGIN_RESPONSE )->error(null, 401);
    }

    /**
     * Log the user out of their session.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return $this->response();
    }

    /**
     * Update the users online timestamp.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function online()
    {
        $this->userRepository->updateOnlineCheckAt( Auth::user() );

        return $this->response();
    }

    /**
     * Return data about the logged in user.
     *
     * @return null
     */
    public function info()
    {
        return Auth::check() ? Auth::user() : null;
    }

    /**
     * Update the users info
     *
     * @return \\Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $data = array_filter(
            Input::only('name', 'password'),
            'strlen'
        );

        $this->userRepository->update( Auth::user(), $data );

        return Auth::user();
    }
}
