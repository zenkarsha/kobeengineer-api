<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SettingGetPageTokenRequest extends Request
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
            'access_token' => 'required|string',
        ];

        return $rules;
    }
}
