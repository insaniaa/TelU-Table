<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true untuk mengizinkan permintaan
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string|unique:courses,course_name',
            'course_sks' => 'required|integer|min:1',
        ];
    }
}
