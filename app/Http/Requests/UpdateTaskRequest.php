<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan true jika validasi dibolehkan
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
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'course_code' => 'required|exists:courses,course_code',
            'id_student_class' => 'required|exists:student_classes,id_student_class',
            'task_deadline' => 'required|date',
            'task_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,ppt,pptx', // Menambahkan validasi untuk task_file
            'task_module' => 'nullable|file|mimes:pdf,doc,docx,xlsx,ppt,pptx', // Menambahkan validasi untuk task_module
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'task_name.required' => 'Nama tugas harus diisi.',
            'task_description.string' => 'Deskripsi tugas harus berupa teks.',
            'course_code.required' => 'Mata kuliah harus dipilih.',
            'id_student_class.required' => 'Kelas harus dipilih.',
            'nip.required' => 'Dosen pengajar harus dipilih.',
            'task_deadline.required' => 'Tenggat waktu harus diisi.',
            'task_status.required' => 'Status tugas harus dipilih.',
            'task_file.mimes' => 'File tugas harus berupa PDF, DOC, DOCX, XLSX, PPT, atau PPTX.',
            'task_module.mimes' => 'File module harus berupa PDF, DOC, DOCX, XLSX, PPT, atau PPTX.',
        ];
    }
}
