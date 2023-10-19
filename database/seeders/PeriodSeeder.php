<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periods = ['1st Period','2nd Period','3rd Period'];

        foreach($periods as $period){
            Period::create([
                'name' => $period
            ]);
        }
    }
}
