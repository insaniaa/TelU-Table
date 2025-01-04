<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ubah sesuai kebutuhan, misalnya hanya admin yang bisa mengubah jadwal
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
            'id_student_class' => 'required|exists:student_classes,id_student_class',
            'course_code' => 'required|exists:courses,course_code',
            'nip' => 'required|exists:lecturers,nip',  // Use 'nip' as it corresponds to the form field
            'schedule_day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i|after:schedule_start_time',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id_student_class.required' => 'Kelas harus dipilih.',
            'id_student_class.exists' => 'Kelas yang dipilih tidak valid.',
            'course_code.required' => 'Mata kuliah harus dipilih.',
            'course_code.exists' => 'Mata kuliah yang dipilih tidak valid.',
            'nip.required' => 'Dosen harus dipilih.',
            'nip.exists' => 'Dosen yang dipilih tidak valid.',
            'schedule_day.required' => 'Hari jadwal harus dipilih.',
            'schedule_day.in' => 'Hari yang dipilih tidak valid.',
            'schedule_start_time.required' => 'Jam mulai harus diisi.',
            'schedule_start_time.date_format' => 'Format jam mulai tidak valid.',
            'schedule_end_time.required' => 'Jam selesai harus diisi.',
            'schedule_end_time.date_format' => 'Format jam selesai tidak valid.',
            'schedule_end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];
    }
}
