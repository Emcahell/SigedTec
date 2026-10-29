<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'nombre')->whereNull('deleted_at'),
            ],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.string' => 'El nombre de la categoría debe ser un texto.',
            'nombre.max' => 'El nombre de la categoría no debe superar los 100 caracteres.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres.',
        ];
    }

    /**
     * Attribute names used by the validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'descripcion' => 'descripción',
        ];
    }
}
