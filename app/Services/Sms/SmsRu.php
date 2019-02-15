<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 15.02.2019
 * Time: 1:06
 */

namespace App\Services\Sms;
use GuzzleHttp\Client;


class SmsRu implements SmsSender
{
    private  $appId;
    private  $url;
    private  $client;
    public function __construct($appId,$url='https:sms.ru/sms/send')
    {
        if (empty($appId)){
            throw  new \InvalidArgumentException('Sms ahhId must be set.');
        }
        $this->appId=$appId;
        $this->url=$url;
        $this->client=new Client();
    }

    public function send($number, $text): void
    {
        $this->client->post($this->url,[
            'form_params'=>[
                'api_id'=>$this->appId,
                'to'=>'+'.trim($number,'+'),
                'text'=>$text,
            ],
        ]);
    }
}