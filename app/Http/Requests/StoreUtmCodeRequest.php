<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class StoreUtmCodeRequest extends Request
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
            'ad_id'         => 'required|exists:ads,id',
            'url'           => 'required|url',
            'utm_campaign'  => 'required',
            'utm_content'   => 'required',
            'utm_medium'    => 'required',
            'utm_source'    => 'required',
            'utm_term'      => 'required',
        ];
    }
}
