<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Section;

use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = \App\Models\InsClass::with('shifts')->get()->take(3);

        foreach ($classes as $class){

            $shifts   = \App\Models\Shift::where('ins_class_id',$class->id)->get();

            foreach ($shifts as $shift){
                Section::updateOrCreate([
                    'institute_id'  => Helper::getInstituteId(),
                    'institute_branch_id'  => Helper::getBranchId(),
                    'ins_class_id'  => $class->id,
                    'shift_id'      => $shift->id,
                    'name'          => 'A'
                ]);
            }
        }

    }
}
