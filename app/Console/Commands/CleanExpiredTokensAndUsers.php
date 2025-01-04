<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class CleanExpiredTokensAndUsers extends Command
{
    protected $signature = 'clean:expired-tokens-and-users';
    protected $description = 'Hapus token reset password yang expired dan hapus pengguna yang belum memverifikasi email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Menghapus token reset password yang expired
        DB::table('password_resets')
            ->where('created_at', '<', Carbon::now()->subMinutes(60)) // Sesuaikan dengan waktu expired token
            ->delete();

        // Menghapus pengguna yang belum memverifikasi email
        User::whereNull('email_verified_at')
            ->where('created_at', '<', Carbon::now()->subDays(30)) // Sesuaikan dengan batas waktu untuk penghapusan
            ->each(function($user) {
                // Menghapus password dan data terkait
                $user->delete();
            });

        $this->info('Expired tokens and unverified users have been cleaned.');
    }
}
