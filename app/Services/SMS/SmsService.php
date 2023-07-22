<?php

namespace App\Services\SMS;



use App\Services\SMS\Kavenegar\KavenegarApi;

class SmsService
{
    private $apiKey;
    protected $api;
    private $sender1 = "10001001000444";
    private $sender2 = "30006703323323";
    public function __construct()
    {
        $this->apiKey="4B44686532774E383947506E4D6158776A6A6C4E444D773457642F424A74687A637337674A466A4B4456413D";
        $this->api = new KavenegarApi($this->apiKey);
    }

    public function send($phone,$text)
    {
        dd($this->api->VerifyLookup($phone,1111,"abzarsanat"));
    }

    public function OTP($phone,$code)
    {
        return $this->api->VerifyLookup($phone,$code,"abzarsanat");
    }


}
