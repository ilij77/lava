<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 15.02.2019
 * Time: 2:16
 */

namespace App\Services\Sms;


class ArraySender implements SmsSender
{
    private  $messages=[];


    public function send($number, $text): void
    {
        $this->messages[]=[
            'to'=>'+'.trim($number,'+'),
            'text'=>$text,
        ];
    }
    public function  getMessages():array
    {
        return $this->messages;
    }
}