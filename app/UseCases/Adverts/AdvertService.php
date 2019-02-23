<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 19.02.2019
 * Time: 1:39
 */

namespace App\UseCases\Adverts;
use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    public function create($userId,$categoryId,$regionId,CreateRequest $request)
    {
        $user = User::findOrFail($userId);
        /**@var Category $category */
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return \DB::transaction(function () use ($request, $user, $category, $region) {
            /**@var Advert $advert */
            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);
            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            return $advert;

        });
    }
    public function addPhotos($id,PhotosRequest $request)
    {
        $advert=$this->getAdvert($id);
        DB::transaction(function ()use($request,$advert){
            foreach($request['files'] as $file){
                $advert->photos()->create([
                    'file'=>$file->store('adverts')
                ]);
            }
        });
    }
    public function  sendToModeration($id)
    {
        $advert=$this->getAdvert($id);
        $advert->sendToModeration();
    }

    private function getAdvert($id)
    {
        return Advert::findOrFail($id);
    }
    public function moderate($id)
    {
        $advert=$this->getAdvert($id);
        $advert->moderate(Carbon::now());

}

public function reject($id,RejectRequest $request)
{
    $advert=$this->getAdvert($id);
    $advert->reject($request['reason']);
}
public function editAttributes($id,AttributesRequest $request)
{
    $advert=$this->getAdvert($id);
    DB::transaction(function ()use ($request,$advert){
        foreach ($advert-values as $value){
         $advert->values()->delete();
            }
       foreach ($advert->category->allAttributes() as $attribute){
            $value=$request['attributes'][$attribute->id] ??  null;
            if (!empty($value)){
                $advert->values()->create([
                    'attribute_id'=>$attribute->id,
                    'value'=>$value,
                ]);
                }
            }
      });
    }
    public function  remove($id){
        $advert=$this->getAdvert($id);
        $advert->delete();
    }
    public function expire(Advert $advert)
    {
        $advert->expire();
    }


}