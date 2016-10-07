<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\AccountRepository;
use App\Http\Requests\Request;
use Auth;

class UpdateAccountRequest extends Request
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * Create new UpdateAccountRequest instance.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct( AccountRepository $accountRepository )
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Bail if the user isn't at least logged in.
        //
        if( ! Auth::check()) return false;

        // Fetch the account.
        //
        return $this->accountRepository
                    ->byIdAndUser($this->route('id'), Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_selected'   => 'required',
            'revenue'       => 'numeric',
            'roi'           => 'numeric',
            'transactions'  => 'integer'
        ];
    }
}
