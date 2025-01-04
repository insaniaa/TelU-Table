<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\VerifyEmail;
use App\Models\lecturer;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();

            if ($request->role == 'lecturer') {
                $lecturer = Lecturer::where('nip', $request->nip)->first();
                if (!$lecturer) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'NIP tidak ditemukan.',
                        'errors' => ['nip' => 'NIP tidak ditemukan.'],
                    ], 404);
                }

                if ($lecturer?->user->email_verified_at ?? false) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'NIP sudah terdaftar.',
                        'errors' => ['nip' => 'NIP sudah mendaftar.'],
                    ], 400);
                }

                $user = User::where('email', $request->email_lecturer)->first();
                if ($user?->email_verified_at ?? false) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email sudah terdaftar.',
                        'errors' => ['email_lecturer' => 'Email sudah terdaftar.'],
                    ], 400);
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
            } else if ($request->role == 'student') {
                $student = Student::where('nim', $request->nim)->first();
                if (!$student) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'NIM tidak ditemukan.',
                        'errors' => ['nim' => 'NIM tidak ditemukan.'],
                    ], 404);
                }

                if ($student?->user->email_verified_at ?? false) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'NIM sudah terdaftar.',
                        'errors' => ['nim' => 'NIM sudah terdaftar.'],
                    ], 400);
                }

                $user = User::where('email', $request->email_student)->first();
                if ($user?->email_verified_at ?? false) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email sudah terdaftar.',
                        'errors' => ['email_student' => 'Email sudah terdaftar.'],
                    ], 400);
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
            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil. Silakan cek email untuk aktivasi akun.',
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Registrasi gagal, mohon coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], Response::HTTP_OK);
    }
}
