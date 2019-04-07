<?php

Route::get('/', 'HomeController@welcome');

Auth::routes();

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::resource('categories', 'CategoriesController');
    Route::resource('items', 'ItemsController');
    Route::resource('users', 'UsersController');

});
Route::group(['prefix' => 'cart', 'namespace' => 'Cart'], function () {
    Route::post('/', 'UpdateCartController');
    Route::get('/', 'ShowCartController');
});

Route::resource('orders', 'OrderController');

Route::post('checkout', 'CheckoutController');

Route::get('{item}/{slug}', 'ViewItemController');

Route::get('mail', 'CheckoutController@sendEmail');