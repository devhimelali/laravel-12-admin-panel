<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('user') ? $this->route('user')->id : null;
        $passwordRules = $id ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'];
        $confirmPasswordRules = $id ? ['nullable', 'string', 'min:8', 'same:password'] : ['required', 'string', 'min:8', 'same:password'];
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => $passwordRules,
            'confirm_password' => $confirmPasswordRules,
            'role' => ['required', 'exists:roles,name'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
            'confirm_password' => 'confirm password',
            'role' => 'role',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email field must be a valid email address.',
            'email.unique' => 'The email address has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password and password must match.',
            'role.required' => 'The role field is required.',
            'role.exists' => 'The selected role does not exist.',
        ];
    }
}
