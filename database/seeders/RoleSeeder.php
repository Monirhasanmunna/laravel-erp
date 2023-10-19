<?php

namespace Database\Seeders;

use App\Models\InstituteBranch;
use App\Models\Institution;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutes = Institution::all();

        foreach($institutes as $institute){
            Role::create([
                'institute_id' => $institute->id,
                'name'         => "Super Admin",
                'deleteable'   => 0,
                'hidden'       => 1
            ]);
        }


        // $users = User::get();
        // foreach($users as $user){

        //     $branch = InstituteBranch::withoutGlobalScopes()->where('institute_id',$user->institute_id)->first();
        //     $role = Role::withoutGlobalScopes()->where('institute_id',$user->institute_id)->first();
           
        //     $user->update([
        //         'institute_branch_id' => $branch->id,
        //         'role_id' => $role->id 
        //     ]);
        // }
    }
}
