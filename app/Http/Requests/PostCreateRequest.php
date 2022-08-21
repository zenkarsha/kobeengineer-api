<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostCreateRequest extends Request
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
            'type' => 'required|string|in:text,image,code',
            'hashtag' => 'string',
            'reply_to' => 'string|exists:post,true_id',
            'content' => 'string|max:1024',
            'code' => 'string|max:65535',
        ];

        return $rules;
    }
}
