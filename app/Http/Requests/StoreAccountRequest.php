<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class StoreAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fb_account_id' => 'required|unique:accounts,fb_account_id',
            'fb_token'      => 'required',
            'revenue'       => 'numeric',
            'roi'           => 'numeric',
            'transactions'  => 'integer'
        ];
    }
}
