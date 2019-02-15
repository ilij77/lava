<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 14.02.2019
 * Time: 2:29
 */

namespace App\Services\Sms;


interface SmsSender
{
    public function send($number,$text):void;

}





