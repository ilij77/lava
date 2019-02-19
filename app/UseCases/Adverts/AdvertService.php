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
use App\Http\Requests\Adverts\CreateRequest;

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

}