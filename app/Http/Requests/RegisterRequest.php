<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|confirmed|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&"*()\-_=+{};:,<.>]).{6,255}+$/',
        ];
    }
}
