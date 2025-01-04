<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'id_student_class' => 'required|exists:student_classes,id_student_class',
            'course_code' => 'required|exists:courses,course_code',
            'nip' => 'required|exists:lecturers,nip',
            'schedule_day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i|after:schedule_start_time',
        ];
    }

    /**
     * Get the custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id_student_class.required' => 'Kelas Mahasiswa harus dipilih.',
            'id_student_class.exists' => 'Kelas Mahasiswa yang dipilih tidak valid.',
            'course_code.required' => 'Mata Kuliah harus dipilih.',
            'course_code.exists' => 'Mata Kuliah yang dipilih tidak valid.',
            'nip.required' => 'Dosen Pengajar harus dipilih.',
            'nip.exists' => 'Dosen Pengajar yang dipilih tidak valid.',
            'schedule_day.required' => 'Hari harus dipilih.',
            'schedule_day.in' => 'Hari yang dipilih tidak valid.',
            'schedule_start_time.required' => 'Waktu Mulai harus diisi.',
            'schedule_start_time.date_format' => 'Format Waktu Mulai tidak valid. Gunakan format HH:mm.',
            'schedule_end_time.required' => 'Waktu Selesai harus diisi.',
            'schedule_end_time.date_format' => 'Format Waktu Selesai tidak valid. Gunakan format HH:mm.',
            'schedule_end_time.after' => 'Waktu Selesai harus setelah Waktu Mulai.',
        ];
    }

    /**
     * Get custom attributes for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id_student_class' => 'Kelas Mahasiswa',
            'course_code' => 'Mata Kuliah',
            'nip' => 'Dosen Pengajar',
            'schedule_day' => 'Hari',
            'schedule_start_time' => 'Waktu Mulai',
            'schedule_end_time' => 'Waktu Selesai',
        ];
    }
}
