<?php

namespace Database\Seeders;

use App\Models\InsTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsTemplate::create([
           'name' => "Theme 1",
           'path_name' => "theme1",
           'thumbnail' => "/assets/images/theme/theme-1.png"
        ]);
        InsTemplate::create([
           'name' => "Theme 2",
           'path_name' => "theme2",
           'thumbnail' => "/assets/images/theme/theme-2.png"
        ]);
    }
}
