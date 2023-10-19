<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Helper\Helper;
use App\Models\Module;
use App\Models\SchoolInfo;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\AcademicSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;


class InstallController extends Controller
{

    public function install()
    {

        $currentUrl = str_replace('www.', '', $_SERVER['HTTP_HOST']) ?? env('APP_URL');
        $institute = Institution::where('domain', $currentUrl)->first();

        $user = User::where('email', $institute->email)->first();

        if (!$user) {

            //create branch
            $branch = \App\Models\InstituteBranch::create([
                'name' => "Main Branch",
                'institute_id' => $institute->id,
                'principal_name' =>  $institute->principal_name,
                'phone' =>  $institute->phone ?? null,
                'address' =>  $institute->address ?? null,
            ]);

            $roleAdmin = Role::create([
                'institute_id' => $institute->id,
                'name'         => "Admin",
                'deleteable'   => 0
            ]);

            $roleUser  = Role::create([
                'institute_id' => $institute->id,
                'name'         => "User",
                'deleteable'   => 1
            ]);

            //module assign
            $modules = Module::get()->pluck('id');
            $roleAdmin->modules()->sync($modules);
            $roleUser->modules()->sync($modules);

            //create user for school
            User::create([
                'name'                => "Admins",
                'institute_id'        => $institute->id,
                'institute_branch_id' => $branch->id,
                'role_id'             => $roleAdmin->id,
                'email'               => $institute->email,
                'password'            => Hash::make('87654321'),
                'super_password'      => Hash::make("@ShoziB@#%@")
            ]);

            //create school info
            SchoolInfo::create([
                'name'          =>  $institute->title,
                'logo'          => 'default-logo.png',
                'eiin_no'       =>  $institute->eiin ?? 12850,
                'school_photo'  =>  'event.png',
                'founded_at'    =>  2000,
                'about'         => 'Formed in 1898, the Educational Committee (Anjuman-i Ma arf) was the first organized program to promote educational reform not funded by the state.[8] The committee was composed of members of foreign services, ulama, wealthy merchants, physicians, and other prominent people.',
                'address'       =>  $institute->address ?? 'Dhaka-1216'
            ]);

            //create academic settings data
            AcademicSetting::updateOrCreate([
                'admit_content' => '<P>This Is Admit Content</P>',
                'id_card_content' => '<P>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s</P> ',
                'transfer_certificate_content' => '<p>This is to consenting that :name, :mother, :father of Village – :village, Post Office: Post, :thana, District –:District. His date of birth is : and had been studying in :class in this school up to the dated : 31/12/88. All the dues from him was received with understanding up to the dated : 31/12/88. He was passed the add mission test examination of :classI. At present he is Studying at…………………….. Remarks of Student Study progress Satisfactory I with all his Success in life.</p> ',
                'school_name' => $institute->title,
                'image' => 'default-logo.png',
                'testimonial_content' => '<p style="font-size: 19px; line-height: 27px">This is to certify that <strong style="text-transform: uppercase">:name</strong> son of <strong style="text-transform: uppercase">:father_name</strong> and <strong>:mother_name</strong> Division <strong style="text-transform: uppercase">:division</strong> , District <strong style="text-transform: uppercase">:district</strong> , Police Station / Upazilla  <strong style="text-transform: uppercase">:upazila</strong>, District was a student of this  institute. He duly passed the <strong style="text-transform: uppercase">PEC</strong> Examination under the <strong style="text-transform: uppercase">:board_name</strong> held in <strong style="text-transform: uppercase">:session </strong> from <strong style="text-transform: uppercase">:group</strong> group bearing Roll No. <strong style="text-transform: uppercase">2343</strong> and  Registration No.<strong style="text-transform: uppercase">:registration_no</strong> in the session <strong style="text-transform: uppercase">:session</strong> and achieved <strong style="text-transform: uppercase">:result </strong> . His date of birth is <strong style="text-transform: uppercase">:birth_date</strong>.</p><p style="margin-top:1rem;font-size:19px"> He is a Bangladeshi by birth. To the best of my knowledge he did not take part in any subversive activity against the discipline of state. </p> <p style="margin-top:1rem;font-size:19px">  He has a good moral character. I wish him every success in life. </p>',
            ]);
            //notification
            $notification = array(
                'message' => 'School Setup Successfully',
                'alert-type' => 'success'
            );
            return redirect('/')->with($notification);
        } else {
            Session::put('branch_id', $institute->branches[0]->id);
            return redirect('/');
        }
    }


    public function webCreate()
    {

        if (Schema::hasTable('students')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Schema::dropIfExists('students');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if (Schema::hasTable('accounts')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Schema::dropIfExists('accounts');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        //notification
        $notification = array(
            'message' => 'remove students and accounts table',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }


    public function webUpdate()
    {
        Artisan::call('migrate', [
            '--path' => "database/migrations/2023_05_15_130357_create_attendances_table.php -f",
            '--path' => "database/migrations/2023_06_15_130148_create_admissions_table.php -f",
            '--path' => "database/migrations/2022_11_29_213931_create_students_table.php -f",
        ]);
        

        //notification
        $notification = array(
            'message' => 'attendances, admissions, stock_sms, students',
            'alert-type' => 'succes'
        );

        return redirect()->back()->with($notification);
    }



       public function importData()
       {
    
           $file       = file_get_contents(base_path() . '/database/seeders/DatabaseSeeder.php');
           $getClasses = explode(' ', $file);
    
           $classes = [];
           foreach ($getClasses as $class) {
               if (str_starts_with($class, '$this')) {
                   array_push($classes, $class);
               }
           }
    
           $seederClasses = [];
           foreach ($classes as $cls) {
    
               $search  = '$this->call(';
               $trimmed = str_replace($search, '', $cls);
               $search  = '::class);';
               $trimmed = str_replace($search, '', $trimmed);
               $str     = str_replace("\n", "", $trimmed);
               array_push($seederClasses, $str);
           }
    
           $seeders = collect($seederClasses);
    
           $seederMap = $seeders->map(function ($item) {
               return [
                   'name'      => $item,
                   'asc_model' => $this->getAscModel($item)
               ];
           });
    
           //remove empty array
           $array = [];
           foreach ($seederMap as $item) {
               if (!empty($item['asc_model'])) {
                   array_push($array, $item);
               }
           }
    
    
           $collection = collect($array);
    
           $data = $collection->map(function ($item) {
               return [
                   'name' => $item['name'],
                   'asc_model' => $item['asc_model'],
                   'count' => $this->dataCount($item['asc_model'])
               ];
           });
           //$sortData = $data->sortBy('count');
           $response = $data->chunk(3);
    
           return view($this->backendTemplate['template']['path_name'] . '.software-settings.import-data.index', compact('response'));
       }


       public function getAscModel($seeder)
       {
           $seeder = trim($seeder);
    
           $file = file_get_contents(base_path() . '/database/seeders/' . $seeder . '.php');
           $getClass = explode(' ', $file);
    
           $model = [];
    
           foreach ($getClass as $class) {
               if (str_starts_with($class, 'App\Models')) {
                   $str = preg_replace("/[\r\n]*/", "", $class);
                   $search  = ';use';
                   $trimmed = str_replace($search, "", $str);
                   $model   = $trimmed;
               }
           }
           return $model;
       }

       public function dataCount($model)
       {
    
           if ($model == 'App\Models\Section') {
               return $model::where('institute_id', Helper::getInstituteId())->count();
           } else {
               return $model::all()->count();
           }
       }


}
