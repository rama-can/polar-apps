<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkInstructionRequest extends FormRequest
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
            'file' => 'file|max:20480|mimes:pdf',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
