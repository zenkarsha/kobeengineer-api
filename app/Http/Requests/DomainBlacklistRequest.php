<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DomainBlacklistRequest extends Request
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
            'domain' => 'required|string|unique:domain_blacklist,domain',
        ];

        return $rules;
    }
}
