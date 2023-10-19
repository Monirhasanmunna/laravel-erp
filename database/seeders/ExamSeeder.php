<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Session;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exams = ["1st Semister","2nd Semister","3rd Semister"];
        $session = Session::first();

        foreach ($exams as $exam){
            Exam::create([
                'session_id' => $session->id,
                'name'       => $exam,
                'start_date' => date("Y-m-d"),
                'end_date'   => date("Y-m-d"),
            ]);
        }


    }
}
