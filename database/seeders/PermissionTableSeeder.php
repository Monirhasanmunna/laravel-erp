<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'academic-management',
            'teacher-management',
            'student-management',
            'routine-management',
            'exam-management',
            'attendance-management',
            'accounts-management',
            'website-management',
            'role-management',
            'software-setting',
         ];


    }
}
