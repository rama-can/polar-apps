<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsageLogbookRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'date_log' => 'required|date_format:d/m/Y',
            'total_duration' => 'required|date_format:H:i',
            'status' => 'required|in:MAHASISWA,DOSEN,PLP,PENELITI,LAINNYA',
            'note' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'temperature' => 'required|numeric',
            'rh' => 'required|numeric',
        ];
        // ($this->isMethod('put') ? '' : '|unique:usage_logbooks,product_id'),
    }
}
