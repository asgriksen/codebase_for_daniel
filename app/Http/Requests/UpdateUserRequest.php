<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\UserRepository;
use App\Http\Requests\Request;

class UpdateUserRequest extends Request
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create new UpdateUserRequest instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->userRepository->byId( $this->route('users') );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->route('users');

        return [
            'email'     => 'required|email|exists:users,email,'.$user_id.'|max:255',
            'password'  => 'min:3|max:255'
        ];
    }
}
