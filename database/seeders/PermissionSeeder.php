<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission['Super Admin'] = [
            'user.view',
            'user.manage',
            'student_class.view',
            'student_class.manage',
            'schedule.view',
            'schedule.manage',
            'room.view',
            'room.manage',
            'course.view',
            'course.manage',
        ];
        $permission['Lecturer'] = [];
        $permission['Student'] = [];

        foreach ($permission as $key => $value) {
            foreach ($value as $p) {
                Permission::findOrCreate($p, 'web');
            }
            $role = Role::findOrCreate($key, 'web');
            $role->syncPermissions($value);
        }

        $superAdmin = User::firstOrCreate(
            ['email' => strtolower('superadmin@gmail.com')], // Email lowercase untuk menghindari case sensitivity
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin1234')
            ]
        );

        $superAdmin->assignRole('Super Admin');
    }
}
