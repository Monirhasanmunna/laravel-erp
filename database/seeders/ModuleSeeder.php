<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array('id' => '1','name' => 'Dashboard','url' => 'dashboard','route' => 'home','is_request' => 'dashboard','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '2','name' => 'Branch Management','url' => 'branch-management/branch/index','route' => 'branch-management.branch.index','is_request' => 'branch-management','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '3','name' => 'Academic Management','url' => 'academic/sessions','route' => 'session.index','is_request' => 'academic','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '4','name' => 'Teacher Management','url' => 'teacher','route' => 'teacher.index','is_request' => 'teacher','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '5','name' => 'Student Management','url' => 'student/dashboard','route' => 'student.dashboard','is_request' => 'student','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '6','name' => 'Question Management','url' => 'question/index','route' => 'question.index','is_request' => 'question','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '7','name' => 'Online Admisssion','url' => 'online-admission/index','route' => 'online-admission.index','is_request' => 'online-admission','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '8','name' => 'Routine Management','url' => 'routine/class/index','route' => 'classroutine.index','is_request' => 'routine','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '9','name' => 'Exam Management','url' => 'exam-management/dashboard/index','route' => 'exam-management.dashboard.index','is_request' => 'exam-management','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '10','name' => 'Online Exam','url' => 'online-exam/index','route' => 'online-exam.index','is_request' => 'online-exam','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '11','name' => 'Home Work','url' => 'homework','route' => 'homework.index','is_request' => 'homework','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '12','name' => 'Manage Attendance','url' => 'attendance/teacher/index','route' => 'attendance.teacher.index','is_request' => 'attendance','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '13','name' => 'SMS Management','url' => 'sms/portal','route' => 'sms.portal','is_request' => 'sms','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '14','name' => 'Push Notification','url' => 'push-notification/send-notification','route' => 'push-notification.send-notification.index','is_request' => 'push-notification','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '15','name' => 'Manage Transport','url' => 'transport/list-of-transport','route' => 'transport.list-of-transport.index','is_request' => 'transport','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '16','name' => 'Hostel Management','url' => 'hostel-management/student-list','route' => 'hostel-management.student-list.index','is_request' => 'hostel-management','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '17','name' => 'Library Management','url' => 'library/book-list','route' => 'library.book-list.index','is_request' => 'library','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '18','name' => 'Inventrory Management','url' => 'inventory/asset','route' => 'inventory.asset.index','is_request' => 'inventory','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '19','name' => 'Digital Classroom','url' => 'digital-class-room/class-room','route' => 'digital-class-room.class-room.index','is_request' => 'digital-class-room','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '20','name' => 'Report Management','url' => 'report-management/student-list','route' => 'report-management.student-list.index','is_request' => 'report-management','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '21','name' => 'Accounts Management','url' => 'accountsmanagement/dashboard','route' => 'accountsmanagement.dashboard','is_request' => 'accountsmanagement','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '22','name' => 'Website Management','url' => 'webadmin/banner','route' => 'banner.index','is_request' => 'webadmin','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '23','name' => 'Software Setting','url' => 'software-settings/theme/idcard','route' => 'software-settings.idcardtheme.index','is_request' => 'software-settings','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31'),
            array('id' => '24','name' => 'Role Management','url' => 'role-management/users/index','route' => 'role-management.users.index','is_request' => 'role-management','icon_class' => 'mdi mdi-speedometer','created_at' => '2023-08-01 09:52:31','updated_at' => '2023-08-01 09:52:31')
          );

          foreach($modules as $module){
            Module::create($module);
          }
    }
}
