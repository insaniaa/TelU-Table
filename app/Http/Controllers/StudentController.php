<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Lecturer;
use App\Models\StudentClass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    private $viewForm = 'admin.master_data.tabler.student.form';
    private $routePrefix = 'admin.master_data.tabler.student.';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::with('studentClass')->latest()->get();

            return DataTables::of($students)
                ->addIndexColumn()
                ->editColumn('student_email', function ($row) {
                    return $row->student_email == null ? 'Pengguna belum mendaftar' : $row->student_email;
                })
                ->editColumn('class', function ($row) {
                    return $row->studentClass->student_class_name;
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <a href="' . route($this->routePrefix . 'edit', $row) . '" class="btn btn-primary btn-sm">Edit</a>
                        <form class="status-form" action="' . route('admin.master_data.tabler.student.update_status', $row) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn status-button btn-sm ' . ($row->student_status ? 'btn-success' : 'btn-danger') . '">
                                ' . ($row->student_status ? 'Aktif' : 'Non-Aktif') . '
                            </button>
                        </form>
                        <form class="delete-form" action="' . route('admin.master_data.tabler.student.delete', $row->nim) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn delete-button btn-sm btn-danger">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions', 'student_email', 'class'])
                ->make(true);
        }

        return view('admin.master_data.tabler.index', [
            'title' => 'Kelola Mahasiswa',
            'routePrefix' => $this->routePrefix,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->viewForm, [
            'model' => new Student(),
            'method' => 'POST',
            'classes' => StudentClass::latest()->where('student_class_status', 1)->get(),
            'lecturers' => Lecturer::latest()->where('lecturer_status', 1)->get(),
            'route' => route($this->routePrefix . 'store'),
            'button' => 'Tambah Mahasiswa',
            'title' => 'Kelola Mahasiswa',
            'sub_title' => 'Tambah data mahasiswa baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            Student::create($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Mahasiswa berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view($this->viewForm, [
            'model' => $student,
            'method' => 'PUT',
            'classes' => StudentClass::latest()->where('student_class_status', 1)->get(),
            'lecturers' => Lecturer::latest()->where('lecturer_status', 1)->get(),
            'route' => route($this->routePrefix . 'update', $student->nim),
            'button' => 'Perbarui Mahasiswa',
            'title' => 'Kelola Mahasiswa',
            'sub_title' => 'Edit data mahasiswa',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $student->update($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Student $student)
    {
        DB::beginTransaction();

        try {
            $student->update(['student_status' => !$student->student_status]);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Status mahasiswa berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            $student->delete();

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Mahasiswa berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique NIM for the student.
     */
    private function generateNIM($data)
    {
        $date = now()->format('Ymd');

        $nameParts = explode(' ', trim($data['student_name']));
        $initials = '';

        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        $random = rand(1000, 9999);

        return 'STU' . $data['id_student_class'] . $initials . $date . $random;
    }
}
