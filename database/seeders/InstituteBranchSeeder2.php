<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstituteBranchSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = ["aboutschools", "academic_settings", "admin_menus", "admission_grades", "admissions", "ataglances",
                    "banners", "book_lists", "branches","chartof_accounts",
                    "colors", "combineds", "contact_us", "designations",
                    "events","exam_routines", "exams", "expenses", "experiences","fees_types", "footers",
                    "front_landings", "front_sections", "general_grades", "general_ledgers", "getintouches","holidays", "homework",
                    "ins_classes", "institute_menus", "institutephotos", "leave_templates", "mcq_chapters","menu_items",
                    "merit_students", "messages","news", "notices","optional_combineds", "pages",
                    "period_times", "qualifications", "question_chapters", "religions","school_infos",
                    "seat_plans", "sections", "sessions", "setoffdays", "shifts", "sliders", "sociallinks", "stock_sms",
                    "student_archives", "student_attendances",
                    "student_fees",
                    "student_migrates","student_timesettings", "student_users",
                    "students","sub_marks_dist_types", "sub_marks_dists", "subject_types", "subjects",
                    "teacher_attendances","teacher_timesettings", "teacher_users",
                    "teachers", "test_grades","transfer_certificates", "upload_admissions", "videos"];

        $institutes = \App\Models\Institution::all();

        foreach ($institutes as $institute){

            $branch = \App\Models\InstituteBranch::create([
                        'name' => "Main Branch",
                        'institute_id' => $institute->id,
                        'principal_name' =>  $institute->principal_name,
                        'phone' =>  $institute->phone ?? null,
                        'address' =>  $institute->address ?? null,
                    ]);

            foreach ($tables as $table){
                DB::table($table)->where('institute_id',$institute->id)->update([
                    'institute_branch_id' => $branch->id
                ]);
            }

        }
    }
}
