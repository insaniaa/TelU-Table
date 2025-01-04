<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Menyesuaikan izin untuk membuat data mahasiswa
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nim' => 'required|unique:students,nim|max:20',
            'student_name' => 'required|string|max:255',
            'id_student_class' => 'required|exists:student_classes,id_student_class',
            'guardian_lecturer' => 'required|exists:lecturers,lecturer_code',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nim.required' => 'NIM mahasiswa harus diisi.',
            'nim.unique' => 'NIM mahasiswa sudah terdaftar.',
            'student_name.required' => 'Nama mahasiswa harus diisi.',
            'student_email.required' => 'Email mahasiswa harus diisi.',
            'student_email.email' => 'Format email tidak valid.',
            'student_email.unique' => 'Email mahasiswa sudah terdaftar.',
            'student_class.required' => 'Kelas mahasiswa harus dipilih.',
            'guardian_lecturer.required' => 'Dosen wali harus dipilih.',
        ];
    }
}
