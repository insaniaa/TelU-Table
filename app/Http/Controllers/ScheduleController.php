<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\StudentClass;
use App\Models\Lecturer;
use App\Models\Course;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class ScheduleController extends Controller
{
    private $viewForm = 'admin.master_data.schedule.form';
    private $routePrefix = 'admin.master_data.schedule.';

    /**
     * Display a listing of the schedules.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schedules = Schedule::with(['studentClass', 'course', 'lecturer'])->latest()->get();

            return DataTables::of($schedules)
                ->addIndexColumn()
                ->editColumn('student_class.student_class_name', function ($row) {
                    return $row->studentClass->student_class_name;
                })
                ->editColumn('course.course_name', function ($row) {
                    return $row->course->course_name;
                })
                ->editColumn('lecturer.lecturer_code', function ($row) {
                    return $row->lecturer->lecturer_code;
                })
                ->editColumn('schedule_day', function ($row) {
                    return ucfirst($row->schedule_day); // Capitalize first letter of day
                })
                ->editColumn('schedule_start_time', function ($row) {
                    // Menggunakan Carbon untuk mengubah format waktu
                    return Carbon::parse($row->schedule_start_time)->format('H:i'); // Format time
                })
                ->editColumn('schedule_end_time', function ($row) {
                    // Menggunakan Carbon untuk mengubah format waktu
                    return Carbon::parse($row->schedule_end_time)->format('H:i'); // Format time
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <a href="' . route($this->routePrefix . 'edit', $row) . '" class="btn btn-primary btn-sm">Edit</a>
                         <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="btn status-button btn-sm ' . ($row->schedule_status ? 'btn-success' : 'btn-danger') . '">
                            ' . ($row->schedule_status ? 'Aktif' : 'Non-Aktif') . '
                        </button>
                    </form>
                        <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn delete-button btn-sm btn-danger">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.master_data.schedule.index', [
            'title' => 'Kelola Jadwal',
            'routePrefix' => $this->routePrefix,
        ]);
    }
    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        return view($this->viewForm, [
            'model' => new Schedule(),
            'method' => 'POST',
            'studentClasses' => StudentClass::latest()->where('student_class_status', 1)->get(),
            'courses' => Course::latest()->get(),
            'lecturers' => Lecturer::latest()->where('lecturer_status', 1)->get(),
            'route' => route($this->routePrefix . 'store'),
            'button' => 'Tambah Jadwal',
            'title' => 'Kelola Jadwal',
            'sub_title' => 'Tambah jadwal kuliah baru',
        ]);
    }

    /**
     * Store a newly created schedule.
     */
    public function store(StoreScheduleRequest $request)
    {
        DB::beginTransaction();

        try {
            // Validate if the schedule for the selected class and time already exists
            $existingSchedule = Schedule::where('id_student_class', $request->id_student_class)
                ->where('schedule_day', $request->schedule_day)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('schedule_start_time', [$request->schedule_start_time, $request->schedule_end_time])
                        ->orWhereBetween('schedule_end_time', [$request->schedule_start_time, $request->schedule_end_time]);
                })
                ->exists();

            if ($existingSchedule) {
                return redirect()->back()->with('error', 'Jadwal pada waktu yang dipilih sudah ada.');
            }

            // If no conflict, proceed with schedule creation
            $data = $request->all();
            Schedule::create($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        return view($this->viewForm, [
            'model' => $schedule,
            'method' => 'PUT',
            'studentClasses' => StudentClass::latest()->where('student_class_status', 1)->get(),
            'courses' => Course::latest()->get(),
            'lecturers' => Lecturer::latest()->where('lecturer_status', 1)->get(),
            'route' => route($this->routePrefix . 'update', $schedule->id_schedule),
            'button' => 'Perbarui Jadwal',
            'title' => 'Kelola Jadwal',
            'sub_title' => 'Edit data jadwal',
        ]);
    }

    /**
     * Update the specified schedule.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        DB::beginTransaction();

        try {
            // Validate if the schedule for the selected class and time already exists, excluding the current schedule
            $existingSchedule = Schedule::where('id_student_class', $request->id_student_class)
                ->where('schedule_day', $request->schedule_day)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('schedule_start_time', [$request->schedule_start_time, $request->schedule_end_time])
                        ->orWhereBetween('schedule_end_time', [$request->schedule_start_time, $request->schedule_end_time]);
                })
                ->where('id_schedule', '!=', $schedule->id_schedule) // Exclude the current schedule being updated
                ->exists();

            if ($existingSchedule) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Jadwal pada waktu yang dipilih sudah ada.');
            }

            // If no conflict, proceed with schedule update
            $data = $request->validated();
            $schedule->update($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())->withInput()->withError();
        }
    }

    public function updateStatus(Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $schedule->update([
                'schedule_status' => !$schedule->schedule_status
            ]);
            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', "Berhasil Memperbarui Status Jadwal!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . 'index')->with('error', "Gagal Memperbarui Status Jadwal!");
        }
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        DB::beginTransaction();

        try {
            $schedule->delete();

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
