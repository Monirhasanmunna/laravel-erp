<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;



class ShurjoPayService
{

    public function getToken(){

        $data = [
            "username" => env('MERCHANT_USERNAME'),
            "password" => env('MERCHANT_PASSWORD')
        ];



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://engine.shurjopayment.com/api/get_token", // your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
                "Authorization: Basic ZHVVc2VyMjAxNDpkdVVzZXJQYXltZW50MjAxNA=="
            ),
        ));


        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            // print_r(json_decode($response));
            $data = json_decode($response);
            $array = json_decode(json_encode($data), true);
//            return $array;
            return $this->createSecretPayment($array);
        }
    }


    public function createSecretPayment($response){


        $httpHost = $_SERVER['HTTP_HOST'];
        $institute = Institution::find(Helper::getInstituteId());

        //set token on session
        Session::put('shurjo-token',$response['token']);

        $data = Helper::getCustomerInfo();
        $data['amount']         = $institute->package->price;
        $data['customer_name']  = $institute->title;
        $data['customer_phone'] = $institute->phone;
        $data['customer_email'] = $institute->email;
        $data['order_id']       = '1250';
        $data['token']          = $response['token'];
        $data['store_id']       = $response['store_id'];
        $data['prefix']         = env('MERCHANT_PREFIX');
        $data['return_url']     = "https://".$httpHost.env('MERCHANT_RETURN_URL');
        $data['cancel_url']     = "https://".$httpHost.env('MERCHANT_CANCEL_URL');
        $data['client_ip']      = "192.168.0.1";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $response['execute_url'], // your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
                "Authorization: Basic ZHVVc2VyMjAxNDpkdVVzZXJQYXltZW50MjAxNA=="
            ),
        ));


        $response = curl_exec($curl);
        $err      = curl_error($curl);


        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response);
            $array = json_decode(json_encode($data), true);
            return redirect($array['checkout_url']);
        }
    }


    public function verifyPayment($orderId){


        $data = [
            'order_id' => $orderId,
            'token'    => Session::get('shurjo-token')
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://engine.shurjopayment.com/api/verification", // your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
                "Authorization: Basic ZHVVc2VyMjAxNDpkdVVzZXJQYXltZW50MjAxNA=="
            ),
        ));


        $response = curl_exec($curl);
        $err      = curl_error($curl);


        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response);
            $array = json_decode(json_encode($data), true);
            return $array;
        }
    }


}
