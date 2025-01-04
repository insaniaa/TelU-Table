<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\VerifyEmail;
use App\Models\lecturer;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validate = ['role' => 'required|in:lecturer,student'];

            if ($request->role == 'lecturer') {
                $validate['nip'] = 'required|string|exists:lecturers,nip';
            } else if ($request->role == 'student') {
                $validate['nim'] = 'required|string|exists:student,nim';
            }

            $validator = Validator::make($request->all(), $validate, [
                'role.required' => 'Pilih role terlebih dahulu',
                'role.in' => 'Role tidak valid',
                'nim.required' => 'NIM harus di isi',
                'nim.string' => 'NIM harus text',
                'nim.exists' => 'NIM tidak ditemukan, hubungi Customer Service untuk info lebih lanjut',
                'nip.required' => 'NIP harus di isi',
                'nip.string' => 'NIP harus text',
                'nip.exists' => 'NIP tidak ditemukan, hubungi Customer Service info lebih lanjut',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            if ($request->role == 'lecturer') {
                $lecturer = Lecturer::where('nip', $request->nip)->first();
                if (!$lecturer) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['nip' => 'NIP tidak ditemukan.'])
                    ->withInput()
                    ->with('error', 'NIP tidak ditemukan.');
                }

                if ($lecturer?->user->email_verified_at ?? false) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['nip' => "NIP sudah mendaftar ."])
                    ->withInput()
                    ->with('error', 'Nip sudah terdaftar');
                }

                $user = User::where('email', $request->email_lecturer)->first();
                if ($user?->email_verified_at ?? false) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['email_lecture' => "Email sudah terdaftar ."])
                    ->withInput()
                    ->with('error', 'Email sudah terdaftar');
                }

                $code = Str::random(60);
                $passwordResetToken = DB::table('password_reset_tokens')->where('email', $request->email_lecturer)->first();
                if ($passwordResetToken) {
                    DB::table('password_reset_tokens')->where('email', $request->email_lecturer)->delete();
                }

                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email_lecturer,
                    'token' => $code,
                    'created_at' => now(),
                ]);

                if (!$user) {
                    // dd($lecturer);
                    $user = User::create([
                        'name' => $lecturer->lecturer_name,
                        'email' => $request->email_lecturer,
                        'password' => Hash::make(Str::random(12)),
                    ])->assignRole('Lecturer');

                    $lecturer->id_user = $user->id_user;
                    $lecturer->save();
                } else {
                    $user->update(['email' => $request->email_lecturer]);
                }

                $data['url'] = route('password.reset', ['token' => $code]);
                $data['name'] = $lecturer->lecturer_name;
                $data['message'] = 'Aktivasi akun anda berhasil! Silakan atur password anda';

                dispatch(new SendMailJob($request->email_lecturer, new VerifyEmail($data)));
            } else if ($request->roleregister == 'user') {
                $student = Student::where('nim', $request->nim)->first();
                if (!$student) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['nip' => 'NIP tidak ditemukan.'])
                    ->withInput()
                    ->with('error', 'NIP tidak ditemukan.');
                }

                if ($student?->user->email_verified_at ?? false) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['nip' => "NIP sudah mendaftar ."])
                    ->withInput()
                    ->with('error', 'Nip sudah terdaftar');
                }

                $user = User::where('email', $request->email_student)->first();
                if ($user?->email_verified_at ?? false) {
                    DB::rollback();
                    session()->flash('role', $request->role);
                    return redirect()->back()
                    ->withErrors(['email_lecture' => "Email sudah terdaftar ."])
                    ->withInput()
                    ->with('error', 'Email sudah terdaftar');
                }

                $code = Str::random(60);
                $passwordResetToken = DB::table('password_reset_tokens')->where('email', $request->email_student)->first();
                if ($passwordResetToken) {
                    DB::table('password_reset_tokens')->where('email', $request->email_student)->delete();
                }

                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email_student,
                    'token' => $code,
                    'created_at' => now()->addMinutes(5),
                ]);

                $user = User::where('email', $student->student_email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $student->student_name,
                        'email' => $request->email_student,
                        'password' => Hash::make(Str::random(12)),
                    ])->assignRole('Student');

                    $student->id_user = $user->id;
                    $student->save();
                }

                $data['url'] = route('password.reset', ['token' => $code]);
                $data['name'] = $student->student_name;
                $data['message'] = 'Aktivasi akun anda berhasil! Silakan atur password anda';

                dispatch(new SendMailJob($student->student_email, new VerifyEmail($data)));
            }

            DB::commit();
            return redirect()->route('register.successed')->with('success', "Registrasi Akun Berhasil!");
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->back()->withErrors($e)->withInput()->with('error', 'Registrasi gagal mohon coba lagi');
        }
    }
}
