<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
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
        return [
//            'v_name' => "required|string",
//            'bName' => "required|string",
//            'company' => "required|string",
//            'department' => "required|string",
            'user_type' => "required|string|in:visitor,trainee,buyer,manufacturer",
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => ['required', 'confirmed', Password::min(6)],
            'terms' => 'required',
        ];
    }
}
