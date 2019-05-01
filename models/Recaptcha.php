<?php

namespace app\models;

class Recaptcha{

    private static $secret = '6LfjRyIUAAAAAECwXMF-fYD8yiLgo3f4OQosHEzJ';

    public static function verifyCaptcha($gresponse){
        $verified = false;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $myvars = 'secret=' . self::$secret . '&response=' . $gresponse;

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec( $ch ));

        //\Kint::dump($response->success)

        return $response->success;
    }

}