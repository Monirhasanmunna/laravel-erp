<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InstituteBranchSeeder extends Seeder
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
                    "events","exam_routines", "exams","experiences", "fees_types", "footers",
                    "front_landings", "front_sections", "general_ledgers", "getintouches", "holidays", "homework",
                    "ins_classes", "institute_menus", "institutephotos", "leave_templates", "mcq_chapters","menu_items",
                    "merit_students", "messages","news", "notices","optional_combineds", "pages",
                    "period_times", "qualifications", "question_chapters", "religions","school_infos",
                    "seat_plans", "sections", "sessions", "setoffdays", "shifts", "sliders", "sociallinks", "stock_sms",
                    "student_archives", "student_attendances",
                    "student_fee_received_details", "student_fee_receiveds",
                    "student_migrates", "student_timesettings", "student_users",
                    "students","sub_marks_dist_types","subject_types", "subjects",
                    "teacher_attendances","teacher_timesettings", "teacher_users",
                    "teachers", "test_grades","transfer_certificates", "upload_admissions", "videos"];

        //field added
        foreach ($tables as $table){
            Schema::table($table, function($table) {
                $table->unsignedBigInteger('institute_branch_id')->after('id')->nullable();
            });
        }



    }
}
