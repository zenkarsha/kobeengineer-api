<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IpBlacklistRequest extends Request
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
            'ip' => 'required|string|unique:ip_blacklist,ip',
            'forbidden' => 'required|integer',
        ];

        return $rules;
    }
}
