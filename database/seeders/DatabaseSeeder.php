<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentClass;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PermissionSeeder::class
        ]);

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('adminadmin')
            ]
        )->assignRole("Super Admin");;

        // Membuat User Dosen
        $lecturerUser = User::firstOrCreate(
            ['email' => 'dosen@gmail.com'],
            [
                'name' => 'dosen',
                'password' => Hash::make('dosendosen'),
            ]
        );
        $lecturerUser->assignRole("Lecturer");

        // Menambahkan data dosen ke tabel lecturers
        $lecturer = Lecturer::firstOrCreate(['nip' => '123456789'] ,[
            'id_user' => $lecturerUser->id,
            'lecturer_name' => 'Dosen Pengajar',
            'lecturer_email' => 'dosen@gmail.com',
            'lecturer_code' => 'D001',
        ]);

        // Membuat User Mahasiswa
        $studentUser = User::firstOrCreate(
            ['email' => 'studentn@gmail.com'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('student12345'),
            ]
        );
        $studentUser->assignRole("Student");

        // Ambil kelas mahasiswa yang sudah ada atau buat kelas baru
        $studentClass = StudentClass::firstOrCreate([
            'student_class_name' => 'Class A',
        ]);

        // Menambahkan data mahasiswa ke tabel students
        $student = Student::create([
            'nim' => '1234567890',
            'id_user' => $studentUser->id,
            'id_student_class' => $studentClass->id_student_class,
            'guardian_lecturer' => $lecturer->lecturer_code,
            'student_name' => 'Student Name',
            'student_email' => 'studentn@gmail.com',
        ]);
    }
}
