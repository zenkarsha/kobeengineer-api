<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AutobotUpdateRequest extends Request
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
            'name' => 'required|string|unique:autobot,name,'.$this->request->get('id'),
            'access_token' => 'required|string',
            'job' => 'required|string',
            'frequency' => 'required|integer',
        ];

        return $rules;
    }
}
