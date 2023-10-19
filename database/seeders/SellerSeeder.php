<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sellers = array(
            array('institute_id' => '5','seller_id' => '145435','seller_package_id' => '1','name' => 'Sajid Hasan','phone' => '01683818354','email' => 'sajid@gmail.com','sms_balance' => '0','payment_method' => 'online','payment_status' => 'unpaid','image' => 'upload/seller/230320084816-308970036420_486742882147420_265157773182894080_n-removebg-preview.png','district' => '2','upazila' => '3','active_date' => '2023-06-14','expire_date' => '2023-06-28','password' => 'ddasdasdsadsadsa','status' => 'pending','created_at' => NULL,'updated_at' => NULL)
        );

        foreach ($sellers as $seller){
            Seller::create($seller);
        }
    }
}
