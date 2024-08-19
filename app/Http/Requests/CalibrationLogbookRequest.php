<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalibrationLogbookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // format date 14/08/2024
            'date_log' => 'required|date_format:d/m/Y',
            'technician' => 'required|string',
            'institution' => 'required|string',
            'document' => 'file|mimes:pdf|max:2048',
        ];
    }
}
