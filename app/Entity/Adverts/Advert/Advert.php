<?php

namespace App\Entity\Adverts\Advert;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use Illuminate\Database\Eloquent\Builder;
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
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','id');
    }
    public function values()
    {
        return $this->hasMany(Value::class,'advert_id','id');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class,'advert_id','id');
    }

public function sendToModeration()
{
    if (!$this->isDraft()){
        throw new \DomainException('Advert is not draft.');
    }
    if (!$this->photos()->count()){
        throw new \DomainException('Fill attributes and upload photos.');
    }

    $this->update([
        'status'=>self::STATUS_MODERATION,
    ]);

}

public function moderate(Carbon $data)
{
    if ($this->status!==self::STATUS_MODERATION){
        throw new \DomainException('Advert is not sent to moderation.');
    }

    $this->update([
        'published_at'=>$data,
        'expires_at'=>$data->copy()->addDay(15),
        'status'=>self::STATUS_ACTIVE,

    ]);
}
public function reject($reason)
{
    $this->update([
        'status'=>self::STATUS_DRAFT,
        'reject_reason'=>$reason,
    ]);
}

    public function expire()
    {
        $this->update([
            'status'=>self::STATUS_CLOSED,
        ]);
    }
public function scopeForUser(Builder $query,User $user)
{
    return $query->where('user_id',$user->id);
}

    public function scopeForCategory(Builder $query,Category $category)
    {
        return $query->where('category_id',array_merge(
            [ $category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }

    public function scopeForRegion(Builder $query,Region $region)
    {
        $ids=[$region->id];
        $childrenIds=$ids;
            while($childrenIds= Region::where(['parent_id'=>$childrenIds])->pluck('id')->toArray()){
                $ids=array_merge($ids,$childrenIds);
            };
            return $query->where('region_id',$ids);
    }
    public function getValue($id)
    {
        foreach($this->values as $value){
            if ($value->attribute_id===$id){
                return $value->value;
            }
        }
        return null;
    }
    public function scopeActive(Builder $query)
    {
        return $query->where('status',self::STATUS_ACTIVE);
    }

}
