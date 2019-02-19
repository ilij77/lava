<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 19.02.2019
 * Time: 2:42
 */

namespace App\Entity\Adverts\Advert;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table='advert_advert_photos';
    public $timestamps=false;
    protected $fillable=['file'];

}