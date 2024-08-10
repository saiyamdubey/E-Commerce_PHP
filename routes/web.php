<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/','welcome');
Auth::routes();

Route::get('/login/pigeon', 'Auth\LoginController@showPigeonLoginForm')->name('login.pigeon');
Route::get('/login/driver', 'Auth\LoginController@showDriverLoginForm')->name('login.driver');
Route::get('/login/restaurant', 'Auth\LoginController@showRestaurantLoginForm')->name('login.restaurant');;
Route::get('/register/pigeon', 'Auth\RegisterController@showPigeonRegisterForm')->name('register.pigeon');
Route::get('/register/driver', 'Auth\RegisterController@showDriverRegisterForm')->name('register.driver');
Route::get('/register/restaurant', 'Auth\RegisterController@showRestaurantRegisterForm')->name('register.restaurant');

Route::post('/login/pigeon', 'Auth\LoginController@pigeonLogin');
Route::post('/login/driver', 'Auth\LoginController@driverLogin');
Route::post('/login/restaurant', 'Auth\LoginController@restaurantLogin');
Route::post('/register/pigeon', 'Auth\RegisterController@createPigeon')->name('register.pigeon');
Route::post('/register/driver', 'Auth\RegisterController@createDriver')->name('register.driver');
Route::post('/register/restaurant', 'Auth\RegisterController@createrestaurant')->name('register.restaurant');
Route::view('/get-back-to-you', 'get-back-to-you');

Route::get('/home', 'HomeController@index')->name('home.index');
Route::post('/home', 'HomeController@address');
Route::get('/account/settings', 'HomeController@settings')->middleware('auth');
Route::get('/r/{restaurant}', 'HomeController@show')->name('home.show');

Route::post('/cart/{menu}', 'CartController@store')->name('cart.store');
Route::delete('/cart/{menu}', 'CartController@remove')->name('cart.remove');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/r/{restaurant}/checkout', 'CheckoutController@index')->name('checkout');
    Route::get('/u/orders', 'UserController@orders')->name('user.orders');
    Route::view('/order-complete', 'order-complete');

    Route::post('/u/orders/{order}/review', 'ReviewController@store');
    Route::post('/r/{restaurant}/favorite', 'HomeController@favorite')->name('home.favorite');
    Route::post('/r/{restaurant}/checkout', 'CheckoutController@store')->name('checkout.store');
    Route::post('/r/{restaurant}/checkout/tip','CheckoutController@tip')->name('checkout.tip');
});

Route::group(['middleware' => 'auth:driver'], function () {
    Route::get('/driver', 'DriverController@index')->name('driver.index');
    Route::get('/d/order/{order}', 'DriverController@order')->name('driver.order');
    Route::get('/driver/setup', 'DriverController@setup')->name('driver.setup');
    Route::get('/driver/setup/2', 'DriverController@vehicle')->name('driver.vehicle');
    Route::get('/trips', 'DriverController@trips')->name('driver.trips');

    Route::post('/driver', 'DriverController@profilePicture')->name('driver.image');
    Route::post('/driver/{order}', 'DriverController@reserve')->name('driver.reserve');
    Route::post('/driver/setup/1', 'DriverController@storeDriversLicense')->name('driver.storeDriversLicense');
    Route::post('/driver/setup/2', 'DriverController@storeVehicle')->name('driver.storeVehicle');
    Route::post('/d/order/{order}', 'DriverController@foodPickupComplete')->name('driver.foodPickupComplete');
    Route::post('/d/order/{order}/delivered', 'DriverController@foodDeliveryComplete')->name('driver.foodDeliveryComplete');
});

Route::group(['middleware' => 'auth:restaurant'], function () {
    Route::get('/restaurant', 'RestaurantController@index')->name('restaurant.index');
    Route::get('/menu', 'RestaurantController@menu')->name('restaurant.menu');
    Route::get('/menu/new', 'RestaurantController@newMenuItem')->name('restaurant.newMenuItem');
    Route::get('/menu/{menu}/edit', 'RestaurantController@editMenuItem')->name('restaurant.editMenuItem');
    Route::get('/management', 'RestaurantController@management')->name('restaurant.manage');
    Route::get('/orders', 'RestaurantController@orders')->name('restaurant.orders');
    Route::get('/orders/{order}', 'RestaurantController@orderDetails')->name('restaurant.orderDetails');
    Route::get('/reviews', 'RestaurantController@reviews')->name('restaurant.reviews');

    Route::post('/menu/new', 'RestaurantController@createMenuItem')->name('restaurant.createMenuItem');
    Route::patch('/menu/{menu}/edit', 'RestaurantController@updateMenuItem')->name('restaurant.updateMenuItem');
    Route::delete('/menu/{menu}/edit', 'RestaurantController@deleteMenuItem')->name('restaurant.deleteMenuItem');
    Route::post('/set-image', 'RestaurantController@setImage')->name('setImage');
    Route::post('/set-hours', 'RestaurantController@setOperatingHours')->name('setOperatingHours');
    Route::patch('/update-hours', 'RestaurantController@updateOperatingHours')->name('updateOperatingHours');
    Route::post('/add-category', 'RestaurantController@addCategory')->name('addCategory');
    Route::post('/orders/{order}', 'RestaurantController@completeOrder')->name('restaurant.completeOrder');
    Route::patch('/orders/{order}', 'RestaurantController@cancelOrder')->name('restaurant.cancelOrder');
});

Route::group(['middleware' => 'auth:pigeon'], function () {
    Route::get('/pigeon', 'PigeonController@index')->name('pigeon.index');
    Route::get('/users', 'PigeonController@users')->name('pigeon.users');
    Route::get('/users/{user}/details', 'PigeonController@userDetails')->name('pigeon.userDetails');
    Route::get('/drivers', 'PigeonController@drivers')->name('pigeon.drivers');
    Route::get('/drivers/{driver}/details', 'PigeonController@driverDetails')->name('pigeon.driverDetails');
    Route::get('/restaurants', 'PigeonController@restaurants')->name('pigeon.restaurants');
    Route::get('/restaurants/applications', 'PigeonController@applications')->name('pigeon.applications');
    Route::get('/restaurants/{restaurant}/details', 'PigeonController@restaurantDetails')->name('pigeon.restaurantDetails');
    Route::get('/all-orders', 'PigeonController@orders')->name('pigeon.orders');
    Route::get('/orders/{order}/details', 'PigeonController@orderDetails')->name('pigeon.orderDetails');
    Route::get('/account/settings', 'PigeonController@settings')->name('pigeon.settings');

    Route::post('/users/order/{order}', 'PigeonController@refundOrder')->name('pigeon.refundOrder');
    Route::patch('/users/order/{order}', 'PigeonController@cancelOrder')->name('pigeon.cancelOrder');
    Route::post('/restaurants/{restaurant}/details', 'PigeonController@setTempPassword')->name('pigeon.setTempPass');
    Route::patch('/restaurants/{restaurant}/details', 'PigeonController@activateRestaurant')->name('pigeon.activateRestaurant');
    Route::delete('/users/{user}/details', 'PigeonController@delUser')->name('pigeon.delUser');
    Route::delete('/restaurants/{restaurant}/details', 'PigeonController@delRestaurant')->name('pigeon.delRestaurant');
    Route::delete('/drivers/{driver}/details', 'PigeonController@delDriver')->name('pigeon.delDriver');
    Route::patch('/account/settings', 'PigeonController@updateAccount')->name('pigeon.updateAccount');
});

Route::view('/privacy', 'privacy')->name('privacy');

Route::fallback(function() {
    return view('fallback');
});
