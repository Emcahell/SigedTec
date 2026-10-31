<?php

namespace App\Http\Requests;

use App\Enums\EquipmentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipmentRequest extends FormRequest
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
            'codigo_patrimonial' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipments', 'codigo_patrimonial'),
            ],
            'marca' => ['required', 'string', 'max:100'],
            'modelo' => ['required', 'string', 'max:100'],
            'cpu' => ['nullable', 'string', 'max:100'],
            'ram' => ['nullable', 'string', 'max:50'],
            'almacenamiento' => ['nullable', 'string', 'max:50'],
            'estado' => ['required', Rule::enum(EquipmentStatus::class)],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->whereNull('deleted_at'),
            ],
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
            'codigo_patrimonial.required' => 'El código patrimonial es obligatorio.',
            'codigo_patrimonial.max' => 'El código patrimonial no debe superar los 50 caracteres.',
            'codigo_patrimonial.unique' => 'El código patrimonial ya está registrado en el sistema.',
            'marca.required' => 'La marca es obligatoria.',
            'marca.max' => 'La marca no debe superar los 100 caracteres.',
            'modelo.required' => 'El modelo es obligatorio.',
            'modelo.max' => 'El modelo no debe superar los 100 caracteres.',
            'cpu.max' => 'El CPU no debe superar los 100 caracteres.',
            'ram.max' => 'La RAM no debe superar los 50 caracteres.',
            'almacenamiento.max' => 'El almacenamiento no debe superar los 50 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.enum' => 'El estado seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
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
            'codigo_patrimonial' => 'código patrimonial',
            'marca' => 'marca',
            'modelo' => 'modelo',
            'cpu' => 'CPU',
            'ram' => 'RAM',
            'almacenamiento' => 'almacenamiento',
            'estado' => 'estado',
            'category_id' => 'categoría',
        ];
    }
}
