<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SettingCreateRequest extends Request
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
            'key' => 'required|string|unique:setting,key|max:60|regex:/^[a-zA-Z0-9_-]+$/',
            'value' => 'string',
            'type' => 'required|string|in:text,textarea,dropdown,slider',
            'group' => 'required|string',
            'custom_config' => 'string',
        ];

        return $rules;
    }
}
