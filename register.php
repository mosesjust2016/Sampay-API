<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'vendor/autoload.php';
require 'functions.php';

use function GuzzleHttp\json_encode;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); //Notice the Namespace and Class name
$dotenv->load();


$order_id = get_uuid();
$request_id = uniqid(time(), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        $phonenumber = "+26".$data->phone_number;
        $mno = $data->services;
        $order_name = $data->order;
        $amount = $data->amount;

        $api_key = $_ENV['API_KEY'];
        $api_auth = $_ENV['API_AUTH'];

        $client = new GuzzleHttp\Client();
        // Define array of request body.
        $request_body = array(
            'app_key'=> $api_key ,
                'auth_key'=> $api_auth,
                'key_type'=> 'business',

                'request_id'=> $request_id,
                'order_id'=> $order_id,
                'order_details'=> $order_name,
                'method'=> 'mobile_money',
                'amount'=> $amount,
                'currency'=> 'ZMW',
                'chargetype'=> 'cc',
                'account'=> $phonenumber,
                'holder_mail'=> 'NULL',
                'service'=> $mno,
        );

        try {
            $response = $client->request('POST', BASE_URL.'ra_register', array(
                'json' => $request_body,
            )
            );
            print_r($response->getBody()->getContents());

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
    }else{

        echo json_encode(array("message" => "Request is not accepted"));
    }





?>