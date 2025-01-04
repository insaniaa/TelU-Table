<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'course_code' => [
                'required',
                'string',
                Rule::unique('courses', 'course_code')->ignore($this->course),
            ],
            'course_name' => [
                'required',
                'string',
                Rule::unique('courses', 'course_name')->ignore($this->course),
            ],
            'course_sks' => 'required|integer|min:1',
        ];
    }
}
