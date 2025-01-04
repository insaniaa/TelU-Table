<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    private $viewIndex = 'index';
    private $viewCreate = 'form';
    private $viewEdit = 'form';
    private $routePrefix = 'admin.master_data.room.';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $room = Room::latest()->get();

            return DataTables::of($room)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                    <a href="' . route($this->routePrefix . 'edit', $row) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form class="status-form" action="' . route($this->routePrefix . 'update_status', $row) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="btn status-button btn-sm ' . ($row->room_status ? 'btn-success' : 'btn-danger') . '">
                            ' . ($row->room_status ? 'Aktif' : 'Non-Aktif') . '
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
            'add_button' => 'Tambah Ruangan'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $data = [
                'model' => new Room(),
                'method' => 'POST',
                'route' => route($this->routePrefix . 'store'),
                'button' => 'Buat Ruangan',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Ruangan',
                'sub_title' => 'Tambahkan data ruangan yang akan dikelola',
            ];
            return view($this->routePrefix . $this->viewCreate, $data);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Kelas!");;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            Room::create($data);
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Membuat Kelas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal Membuat Kelas!");;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        try {
            $room = Room::findOrFail($room->id_room);

            $data = [
                'model' => $room,
                'method' => 'PUT',
                'route' => route($this->routePrefix . 'update', $room->id_room),
                'button' => 'Perbarui Ruangan',
                'routePrefix' => $this->routePrefix,
                'title' => 'Kelola Ruangan',
                'sub_title' => 'Perbarui data ruangan',
            ];
            return view($this->routePrefix . $this->viewEdit, $data);
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menemukan Kelas!");;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            Room::find($room->id_room)->update($data);
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
    public function updateStatus(Room $room)
    {
        DB::beginTransaction();
        try {
            $room->update([
                'room_status' => !$room->room_status
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
    public function destroy(Room $room)
    {
        DB::beginTransaction();
        try {
            $room->delete();
            DB::commit();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('success', "Berhasil Menghapus Status Kelas!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($this->routePrefix . $this->viewIndex)->with('error', "Gagal Menghapus Status Kelas!");
        }
    }}
