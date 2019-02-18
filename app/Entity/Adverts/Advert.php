<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $region_id
 * @property string $title
 * @property string $content
 * @property int $price
 * @property string $address
 * @property string $status
 * @property string $reject_reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $published_at
 * @property Carbon $expires_at

 */

class Advert extends Model
{
    public const STATUS_DRAFT='draft';
    public const STATUS_MODERATION='moderation';
    public const STATUS_ACTIVE='active';
    public const STATUS_CLOSED='closed';


    protected $table='advert_adverts';
    protected  $guarded=['id'];
    protected  $casts=[
        'published_at'=>'datetime',
        'expires_at'=>'datetime',
    ];




    public function isDraft():bool
    {
        return$this->status===self::STATUS_DRAFT;
    }
    public function isActive():bool
    {
        return$this->status===self::STATUS_ACTIVE;
    }
    public function isClosed():bool
    {
        return$this->status===self::STATUS_CLOSED;
    }
    public function isModeration():bool
    {
        return$this->status===self::STATUS_MODERATION;
    }



}
