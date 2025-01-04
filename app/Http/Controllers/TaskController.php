<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\StudentClass;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    private $viewIndex = 'index';
    private $viewCreate = 'form';
    private $viewEdit = 'form';
    private $routePrefix = 'lecturer.task.';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::with(['course', 'studentClass', 'lecturer'])->latest();

            $classes = Schedule::where('nip', auth()->user()->lecturer->nip)
                ->distinct()
                ->pluck('id_student_class');

            // Filter tasks by the student classes associated with the lecturer
            $tasks = $tasks->whereIn('id_student_class', $classes);

            return DataTables::of($tasks->get())
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $statusButton = $row->task_status ? 'btn-success' : 'btn-danger';
                    $statusText = $row->task_status ? 'Aktif' : 'Non-Aktif';
                    return '
                    <a href="' . route($this->routePrefix . 'edit', $row->id_task) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row->id_task) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="status-button btn ' . $statusButton . ' btn-sm">
                            ' . $statusText . '
                        </button>
                    </form>
                    <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row->id_task) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <input type="hidden" name="method" value="DELETE"/>
                        <button type="submit" class="btn delete-button btn-sm btn-danger">
                            Hapus
                        </button>
                    </form>
                ';
                })
                ->addColumn('student_class_name', function ($task) {
                    return $task->studentClass->student_class_name ?? 'N/A';
                })
                ->addColumn('course_name', function ($task) {
                    return $task->course->course_name ?? 'N/A';
                })
                ->addColumn('task_deadline', function ($task) {
                    return Carbon::parse($task->task_deadline)->format('d F Y, h:i A');
                })
                ->addColumn('task_module', function ($task) {
                    return $task->task_module ? "<a href='" . asset('storage/' . $task->task_module) . "' target='_blank'>Download</a>" : 'No File';
                })
                ->addColumn('task_file', function ($task) {
                    return $task->task_file ? "<a href='" . asset('storage/' . $task->task_file) . "' target='_blank'>Download</a>" : 'No File';
                })
                ->rawColumns(['actions', 'task_file'])
                ->make(true);
        }

        return view($this->routePrefix . $this->viewIndex, [
            'routePrefix' => $this->routePrefix,
            'title' => 'Kelola Tugas',
            'add_button' => 'Tambah Tugas'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $courses = Course::all();
            $studentClasses = StudentClass::all();

            return view($this->routePrefix . $this->viewCreate, [
                'model' => new Task(),
                'courses' => $courses,
                'studentClasses' => $studentClasses,
                'method' => 'POST',
                'route' => route($this->routePrefix . 'store'),
                'button' => 'Buat Tugas',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Tugas',
                'sub_title' => 'Tambahkan data tugas yang akan dikelola',
            ]);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Data Tugas!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        DB::beginTransaction();
        try {
            // Validasi request
            $data = $request->validated();

            // Menyimpan file task_file jika ada
            if ($request->hasFile('task_file')) {
                $data['task_file'] = $request->file('task_file')->store('task_files');
            }

            // Menyimpan file task_module jika ada
            if ($request->hasFile('task_module')) {
                $data['task_module'] = $request->file('task_module')->store('task_modules');
            }

            $data['nip'] = auth()->user()->lecturer->nip;
            // Membuat entri task baru
            Task::create($data);

            // Commit transaksi
            DB::commit();

            return redirect()->route($this->routePrefix . $this->viewIndex)
                ->with('success', "Berhasil Membuat Tugas!");
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal Membuat Tugas!");
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        try {
            $courses = Course::all();
            $studentClasses = StudentClass::all();

            return view($this->routePrefix . $this->viewEdit, [
                'model' => $task,
                'courses' => $courses,
                'studentClasses' => $studentClasses,
                'method' => 'PUT',
                'lecturers' => Lecturer::latest()->where('lecturer_status', 1)->get(),
                'route' => route($this->routePrefix . 'update', $task->id_task),
                'button' => 'Perbarui Tugas',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Tugas',
                'sub_title' => 'Perbarui data tugas',
            ]);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Data Tugas!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        DB::beginTransaction();
        try {
            // Validasi request
            $data = $request->validated();

            // Jika ada file task_file yang diupload
            if ($request->hasFile('task_file')) {
                // Hapus file lama jika ada
                if ($task->task_file) {
                    Storage::delete($task->task_file);
                }
                // Simpan file baru
                $data['task_file'] = $request->file('task_file')->store('task_files');
            }

            // Jika ada file task_module yang diupload
            if ($request->hasFile('task_module')) {
                // Hapus file lama jika ada
                if ($task->task_module) {
                    Storage::delete($task->task_module);
                }
                // Simpan file baru
                $data['task_module'] = $request->file('task_module')->store('task_modules');
            }

            $data['nip'] = auth()->user()->lecturer->nip;
            // Update data task dengan data yang baru
            $task->update($data);

            // Commit transaksi
            DB::commit();

            return redirect()->route($this->routePrefix . $this->viewIndex)
                ->with('success', "Berhasil Memperbarui Tugas!");
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)
                ->with('error', "Gagal Memperbarui Tugas!");
        }
    }

    public function updateStatus(Task $task)
    {
        DB::beginTransaction();
        try {
            $task->update([
                'task_status' => !$task->task_status
            ]);
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', "Berhasil Memperbarui Status Jadwal!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . 'index')->with('error', "Gagal Memperbarui Status Jadwal!");
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        DB::beginTransaction();
        try {
            // Delete the file if exists
            if ($task->task_file) {
                Storage::delete($task->task_file);
            }

            $task->delete();
            DB::commit();

            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Menghapus Tugas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menghapus Tugas!");
        }
    }

    /**
     * Display tasks for today.
     */
    public function tasksToday(Request $request)
    {
        $today = now()->format('Y-m-d');
        $tasks = Task::with(['course', 'studentClass', 'lecturer'])->whereDate('task_deadline', $today)->latest();

        $user = auth()->user();
        $title = '';

        $dayMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        // Get today's day in English
        $today = Carbon::now()->format('l');

        // Map it to Indonesian
        $todayInIndonesian = $dayMapping[$today];

        // Query the schedules
        $schedulesTodayQuery = Schedule::with('course', 'lecturer')
            ->where('schedule_day', $todayInIndonesian) // Use the mapped day
            ->where('schedule_status', 1);

        if ($user->hasRole('Student')) {
            // Filter tasks by the student's class
            $tasks = $tasks->where('id_student_class', $user->student->studentClass->id_student_class);
            $title = 'Daftar Tugas';
            $schedulesTodayQuery = $schedulesTodayQuery->where('id_student_class', $user->student->studentClass->id_student_class);
        } elseif ($user->hasRole('Lecturer')) {
            // Get distinct student classes from schedules associated with the lecturer
            $classes = Schedule::where('nip', $user->nip)
                ->distinct()
                ->pluck('id_student_class');

            // Filter tasks by the student classes associated with the lecturer
            $tasks = $tasks->whereIn('id_student_class', $classes);
            $title = 'Kelola Tugas';
        }

        $schedulesToday = $schedulesTodayQuery->get();

        if ($request->ajax()) {
            return DataTables::of($tasks->get())
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $statusButton = $row->task_status ? 'btn-success' : 'btn-danger';
                    $statusText = $row->task_status ? 'Aktif' : 'Non-Aktif';
                    return '
                    <a href="' . route($this->routePrefix . 'edit', $row->id_task) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row->id_task) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="status-button btn ' . $statusButton . ' btn-sm">
                            ' . $statusText . '
                        </button>
                    </form>
                    <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row->id_task) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <input type="hidden" name="method" value="DELETE"/>
                        <button type="submit" class="btn delete-button btn-sm btn-danger">
                            Hapus
                        </button>
                    </form>
                ';
                })
                ->addColumn('student_class_name', function ($task) {
                    return $task->studentClass->student_class_name ?? 'N/A';
                })
                ->addColumn('course_name', function ($task) {
                    return $task->course->course_name ?? 'N/A';
                })
                ->addColumn('task_deadline', function ($task) {
                    return Carbon::parse($task->task_deadline)->format('d F Y, h:i A');
                })
                ->addColumn('task_module', function ($task) {
                    return $task->task_module ? "<a href='" . asset('storage/' . $task->task_module) . "' target='_blank'>Download</a>" : 'No File';
                })
                ->addColumn('task_file', function ($task) {
                    return $task->task_file ? "<a href='" . asset('storage/' . $task->task_file) . "' target='_blank'>Download</a>" : 'No File';
                })
                ->rawColumns(['actions', 'task_file'])
                ->make(true);
        }

        return view($this->routePrefix . 'home', [
            'routePrefix' => $this->routePrefix,
            'title' => $title,
            'add_button' => 'Tambah Tugas',
            'task' => $tasks->get(),
            'schedulesToday' => $schedulesToday,
        ]);
    }
}
