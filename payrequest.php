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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        $request_id = uniqid(time(), true);


        $token = $data->token;
        $otp = $data->otp;

        $api_key = $_ENV['API_KEY'];
        $api_auth = $_ENV['API_AUTH'];

        $client = new GuzzleHttp\Client();
        // Define array of request body.
        $request_body = array(
                'app_key'=> $api_key ,
                'auth_key'=> $api_auth,
                'key_type'=> 'business',
                'request_id'=> $request_id,
                'token'=> $token,
                'otp'=> $otp,    
                'method'=> 'mobile_money',
        );

        try {
            $response = $client->request('POST',BASE_URL.'ra_mmpayrequest', array(
                'json' => $request_body
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


function get_uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

?>