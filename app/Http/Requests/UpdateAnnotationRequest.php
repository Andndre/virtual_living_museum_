<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'museum_id' => ['required', 'exists:virtual_museum,museum_id'],
            'label' => ['required', 'string', 'max:100'],
            'position_x' => ['required', 'numeric'],
            'position_y' => ['required', 'numeric'],
            'position_z' => ['required', 'numeric'],
            'is_visible' => ['boolean'],
            'display_order' => ['integer', 'min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'museum_id.required' => 'Museum harus dipilih.',
            'museum_id.exists' => 'Museum yang dipilih tidak valid.',
            'label.required' => 'Label harus diisi.',
            'label.max' => 'Label maksimal 100 karakter.',
            'position_x.required' => 'Position X harus diisi.',
            'position_y.required' => 'Position Y harus diisi.',
            'position_z.required' => 'Position Z harus diisi.',
        ];
    }
}
