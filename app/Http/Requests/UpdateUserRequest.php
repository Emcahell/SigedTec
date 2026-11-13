<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:150"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                Rule::unique("users", "email")->ignore($this->route("user")),
            ],
            "password" => ["nullable", "confirmed", Password::min(8)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "name.required" => "El nombre del solicitante es obligatorio.",
            "name.max" => "El nombre no debe superar los 150 caracteres.",
            "email.required" => "El correo electrónico es obligatorio.",
            "email.email" => "El correo electrónico no es válido.",
            "email.unique" =>
                "Ya existe un usuario registrado con ese correo electrónico.",
            "password.confirmed" =>
                "La confirmación de la contraseña no coincide.",
            "password.min" => "La contraseña debe tener al menos 8 caracteres.",
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            "name" => "nombre",
            "email" => "correo electrónico",
            "password" => "contraseña",
        ];
    }
}
