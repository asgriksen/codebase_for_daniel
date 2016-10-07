<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class StoreCartRequest extends Request
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
            'AdminAccount'  => 'required',
            'ApiPath'       => 'required|url',
            'ApiKey'        => 'required',
            'cart_id'       => 'required',
            'name'          => 'required',
            'store_key'     => 'required',
            'store_url'     => 'required|url',
            'user_id'       => 'required|exists:users,id',
            'verify'        => 'in:True,False',
        ];
    }
}
