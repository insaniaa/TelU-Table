<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\UpdateLecturerRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LecturerController extends Controller
{
    private $viewForm = 'admin.master_data.tabler.lecturer.form';
    private $routePrefix = 'admin.master_data.tabler.lecturer.';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $lecturers = Lecturer::latest()->get();

            return DataTables::of($lecturers)
                ->addIndexColumn()
                ->editColumn('lecturer_email' , function($row) {
                    return $row->lecturer_email == null ? 'Pengguna belum mendaftar' : $row->lecturer_email;
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <a href="' . route($this->routePrefix . 'edit', $row->nip) . '" class="btn btn-primary btn-sm">Edit</a>
                        <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row->nip) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn status-button btn-sm ' . ($row->lecturer_status ? 'btn-success' : 'btn-danger') . '">
                                ' . ($row->lecturer_status ? 'Aktif' : 'Non-Aktif') . '
                            </button>
                        </form>
                        <form class="delete-form" action="' . route($this->routePrefix . 'delete', $row->nip) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn delete-button btn-sm btn-danger">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions', 'lecturer_email'])
                ->make(true);
        }

        return view('admin.master_data.tabler.index', [
            'title' => 'Kelola Dosen & Mahasiswa',
            'routePrefix' => $this->routePrefix,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->viewForm, [
            'model' => new Lecturer(),
            'method' => 'POST',
            'route' => route($this->routePrefix . 'store'),
            'button' => 'Tambah Dosen',
            'title' => 'Kelola Dosen',
            'sub_title' => 'Tambah data dosen baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLecturerRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            Lecturer::create($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Dosen berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan dosen: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer)
    {
        return view($this->viewForm, [
            'model' => $lecturer,
            'method' => 'PUT',
            'route' => route($this->routePrefix . 'update', $lecturer->nip),
            'button' => 'Perbarui Dosen',
            'title' => 'Kelola Dosen',
            'sub_title' => 'Edit data dosen',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerRequest $request, Lecturer $lecturer)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $lecturer->update($data);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Data dosen berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data dosen: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Lecturer $lecturer)
    {
        DB::beginTransaction();

        try {
            $lecturer->update(['lecturer_status' => !$lecturer->lecturer_status]);

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Status dosen berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status dosen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer)
    {
        DB::beginTransaction();

        try {
            $lecturer->delete();

            DB::commit();
            return redirect()->route($this->routePrefix . 'index')->with('success', 'Dosen berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus dosen: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique NIP for the lecturer.
     */
    private function generateNIP($data)
    {
        $date = now()->format('Ymd');

        $nameParts = explode(' ', trim($data['lecturer_name']));
        $initials = '';

        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        $random = rand(1000, 9999);

        return 'LCT' . $data['lecturer_code'] . $initials . $date . $random;
    }
}
