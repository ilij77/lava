<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 19.02.2019
 * Time: 2:32
 */

namespace App\Entity\Adverts\Advert;


use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table='advert_advert_values';
    public $timestamps=false;
    protected $fillable=['attribute_id','value'];

}