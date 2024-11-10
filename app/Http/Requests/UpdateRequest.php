<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5',
            'username' => [
                'nullable',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'old_password' => 'nullable',
            'new_password' => 'nullable',
            'avatar' => 'mimes:png,jpg|max:2048',
            
        ];
    }
}
