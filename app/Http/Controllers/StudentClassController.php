<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentClassRequest;
use App\Http\Requests\UpdateStudentClassRequest;
use App\Models\StudentClass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentClassController extends Controller
{
    private $viewIndex = 'index';
    private $viewCreate = 'form';
    private $viewEdit = 'form';
    private $routePrefix = 'admin.master_data.student_class.';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $studentClass = StudentClass::latest()->get(); // Mengambil data Kelas terbaru

            // Menggunakan DataTables untuk merender data
            return DataTables::of($studentClass)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                    <a href="' . route($this->routePrefix . 'edit', $row) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="btn status-button btn-sm ' . ($row->student_class_status ? 'btn-success' : 'btn-danger') . '">
                            ' . ($row->student_class_status ? 'Aktif' : 'Non-Aktif') . '
                        </button>
                    </form>
                    <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <input type="hidden" name="method" value="DELETE"/>
                        <button type="submit" class="btn delete-button btn-sm btn-danger">
                            Hapus
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view($this->routePrefix . $this->viewIndex, [
            'routePrefix' => $this->routePrefix,
            'title' => 'Kelola Kelas',
            'add_button' => 'Tambah Kelas'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $data = [
                'model' => new StudentClass(),
                'method' => 'POST',
                'route' => route($this->routePrefix . 'store'),
                'button' => 'Buat Kelas',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Kelas',
                'sub_title' => 'Tambahkan data kelas yang akan dikelola',
            ];
            return view($this->routePrefix . $this->viewCreate, $data);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Kelas!");;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentClassRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            StudentClass::create($data);
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Membuat Kelas!");
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal Membuat Kelas!");;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentClass $studentClass)
    {
        try {
            $studentClass = StudentClass::findOrFail($studentClass->id_student_class);

            $data = [
                'model' => $studentClass,
                'method' => 'PUT',
                'route' => route($this->routePrefix . 'update', $studentClass->id_student_class),
                'button' => 'Perbarui Kelas',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Kelas',
                'sub_title' => 'Perbarui data kelas',
            ];
            return view($this->routePrefix . $this->viewEdit, $data);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Kelas!");;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentClassRequest $request, StudentClass $student_class)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            StudentClass::find($student_class->id_student_class)->update($data);
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Memperbarui Kelas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Memperbarui Kelas!");;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function updateStatus(StudentClass $student_class)
    {
        DB::beginTransaction();
        try {
            $student_class->update([
                'student_class_status' => !$student_class->student_class_status
            ]);
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Memperbarui Status Kelas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Memperbarui Status Kelas!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentClass $student_class)
    {
        DB::beginTransaction();
        try {
            $student_class->delete();
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Menghapus Status Kelas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menghapus Status Kelas!");
        }
    }
}
