<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';

    protected $table='advert_attributes';
    public  $timestamps=false;
    protected  $fillable=['name','type','required','default','variants','sort'];
    protected  $casts=[
        'variants'=>'array',
    ];
    public static function typesList()
    {
        return [
            self:: TYPE_STRING =>'string',
            self:: TYPE_INTEGER =>'integer',
            self:: TYPE_FLOAT =>'float',
        ];
    }
    public function isString():bool
    {
        return $this->type===self::TYPE_STRING;
    }

    public function isInteger():bool
{
    return $this->type===self::TYPE_INTEGER;
}
    public function isFloat():bool
    {
        return $this->type===self::TYPE_FLOAT;
    }
    public function isSelect(): bool
    {
        return \count($this->variants)>0;
    }


}
