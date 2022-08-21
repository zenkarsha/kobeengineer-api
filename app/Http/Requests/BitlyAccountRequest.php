<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BitlyAccountRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'bitly_key' => 'required|string',
            'bitly_login' => 'required|string',
            'bitly_clientid' => 'required|string',
            'bitly_secret' => 'required|string',
            'bitly_access_token' => 'required|string',
            'usage' => 'required|integer',
        ];

        return $rules;
    }
}
