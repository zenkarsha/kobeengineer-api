<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Response;

abstract class Request extends FormRequest
{
    // public function response(array $errors)
    // {
    //      return Response::json($errors);
    // }

    // protected function formatErrors(Validator $validator)
    // {
    //     return [
    //         'success' => false,
    //         'message' => $validator->messages(),
    //     ];
    // }
}
