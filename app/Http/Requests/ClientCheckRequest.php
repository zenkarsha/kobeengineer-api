<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClientCheckRequest extends Request
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
            'identification' => 'required|string|exists:client_identification,identification',
        ];

        return $rules;
    }
}
