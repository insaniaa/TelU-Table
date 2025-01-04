<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    private $routePrefix = 'admin.master_data.course.';
    private $viewIndex = 'index';
    private $viewCreate = 'form';
    private $viewEdit = 'form';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::latest()->get();

            return DataTables::of($courses)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                    <a href="' . route($this->routePrefix . 'edit', $row) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="btn status-button btn-sm ' . ($row->course_status ? 'btn-success' : 'btn-danger') . '">
                            ' . ($row->course_status ? 'Aktif' : 'Non-Aktif') . '
                        </button>
                    </form>
                    <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
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
            'title' => 'Kelola Mata Kuliah',
            'add_button' => 'Tambah Mata Kuliah'
        ]);
    }

    public function create()
    {
        $data = [
            'model' => new Course(),
            'method' => 'POST',
            'route' => route($this->routePrefix . 'store'),
            'button' => 'Buat Mata Kuliah',
            'routePrefix' => $this->routePrefix,
            'title' => 'Kelola Mata Kuliah',
            'sub_title' => 'Tambahkan data mata kuliah yang akan dikelola',
        ];
        return view($this->routePrefix . $this->viewCreate, $data);
    }

    public function store(StoreCourseRequest $request)
    {
        DB::beginTransaction();
        try {
            Course::create($request->validated());
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Berhasil Membuat Mata Kuliah!');
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Membuat Mata Kuliah!');
        }
    }

    public function edit(Course $course)
    {
        $data = [
            'model' => $course,
            'method' => 'PUT',
            'route' => route($this->routePrefix . 'update', $course),
            'button' => 'Perbarui Mata Kuliah',
            'routePrefix' => $this->routePrefix,
            'title' => 'Kelola Mata Kuliah',
            'sub_title' => 'Perbarui data mata kuliah',
        ];
        return view($this->routePrefix . $this->viewEdit, $data);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        DB::beginTransaction();
        try {
            $course->update($request->validated());
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Berhasil Memperbarui Mata Kuliah!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Memperbarui Mata Kuliah!');
        }
    }

    public function updateStatus(Course $course)
    {
        DB::beginTransaction();
        try {
            $course->update(['course_status' => !$course->course_status]);
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Berhasil Memperbarui Status Mata Kuliah!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Memperbarui Status Mata Kuliah!');
        }
    }

    public function destroy(Course $course)
    {
        DB::beginTransaction();
        try {
            $course->delete();
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Berhasil Menghapus Mata Kuliah!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Menghapus Mata Kuliah!');
        }
    }
}
