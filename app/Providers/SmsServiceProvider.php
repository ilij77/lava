<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 15.02.2019
 * Time: 2:27
 */

namespace App\Providers;
use App\Services\Sms\ArraySender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;


class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsSender::class,function (Application $app){
            $config=$app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'sms.ru':
                    $params = $config['drivers']['sms.ru'];
                    if (!empty($config['url'])) {
                        return new SmsRu($config['app.id'], $config['url']);
                    }
                    return new SmsRu($config['app.id']);
                case 'array':
                    return new ArraySender();
                default:
                    throw new \InvalidArgumentException('Undefined SMS driver' . $config['driver']);
            }
        });



    }
}