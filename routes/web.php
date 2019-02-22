<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');


Auth::routes();
Route::get('/verify/{token}','Auth\RegisterController@verify')->name('register.verify');

Route::group([
    'prefix'=>'adverts',
    'as'=>'adverts.',
    'namespace'=>'Adverts',
],function (){
    Route::get('/show/{advert}','AdvertController@show')->name('show');
    Route::post('/show/{advert}/phone','AdvertController@phone')->name('phone');
    Route::get('/all/{category?}','AdvertController@index')->name('index.all');
    Route::get('/{region?}/{category?}','AdvertController@index')->name('index');
});


Route::group(
    [ 'prefix'=>'cabinet',
        'as'=>'cabinet.',
        'namespace'=>'Cabinet',
        'middleware'=>['auth'],
    ],function ()
{
    Route::get('/','HomeController@index')->name('home');
    Route::group(['prefix'=>'profile', 'as'=>'profile.'],function ()
    {
        Route::get('/','ProfileController@index')->name('home');
        Route::get('/edit','ProfileController@edit')->name('edit');
        Route::put('/update','ProfileController@update')->name('update');
        Route::post('/phone','PhoneController@request');
        Route::get('/phone','PhoneController@form')->name('phone');
        Route::put('/phone','PhoneController@verify')->name('phone.verify');
        Route::post('/phone/auth','PhoneController@auth')->name('phone.auth');
    });

    Route::group([
        'prefix'=>'adverts',
        'as'=>'adverts.',
        'namespace'=>'Adverts',
        'middleware'=>[\App\Http\Middleware\FilledProfile::class],
    ],function(){
        Route::get('/','AdvertController@index')->name('index');
        Route::get('/create','CreateController@category')->name('create');
        Route::get('/create/region/{category}/{region?}','CreateController@region')->name('create.region');
        Route::post('/create/advert/{category}/{region?}','CreateController@store')->name('create.advert.store');

        Route::get('/{advert}/edit','ManageController@edit')->name('edit');
        Route::put('/{advert}/edit','ManageController@update')->name('update');
        Route::get('/{advert}/photos','ManageController@photos')->name('photos');
        Route::post('/{advert}/photos','ManageController@photos');
        Route::post('/{advert}/send','ManageController@send')->name('send');
        Route::delete('/{advert}/send','ManageController@destroy')->name('destroy');
    });


});

Route::group(
    [ 'prefix'=>'admin',
        'as'=>'admin.',
        'namespace'=>'Admin',
        'middleware'=>['auth','can:admin-panel'],
    ],
    function (){
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users','UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
        Route::resource('regions','RegionController');

        Route::group(['prefix'=>'adverts','as'=>'adverts.','namespace'=>'adverts'],function ()
        {
            Route::resource('categories','CategoryController');

            Route::group(['prefix'=>'categories/{category}', 'as'=>'categories.'],function (){
                Route::post('/first','CategoryController@first')->name('first');
                Route::post('/up','CategoryController@up')->name('up');
                Route::post('/down','CategoryController@down')->name('down');
                Route::post('/last','CategoryController@last')->name('last');

                Route::resource('attributes','AttributeController')->except('index');
            });

        });
});


