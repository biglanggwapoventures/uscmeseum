<?php

Route::get('/', 'HomeController@welcome');

Auth::routes();

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::resource('categories', 'CategoriesController');
    Route::resource('items', 'ItemsController');
    Route::resource('users', 'UsersController');
    Route::get('item/{item}/logs', 'ItemLogsController@index');
    Route::post('item/{item}/logs', 'ItemLogsController@store');
    Route::get('most-favorited-items', 'MostFavoritedItemsController');
    Route::get('sales-report', 'SalesReportController');
});
Route::group(['prefix' => 'cart', 'namespace' => 'Cart', 'middleware' => 'non-admin'], function () {
    Route::post('/', 'UpdateCartController');
    Route::get('/', 'ShowCartController');
});

//show profile page
Route::get('profile', 'ProfileController');
//update profile page
Route::put('profile', 'ProfileController@update');

//mark item as favorite
Route::post('item/{item}/favorite', 'MarkFavoriteController');
//show favorite items of users
Route::get('my-favorites', 'MarkFavoriteController@index');

Route::resource('orders', 'OrderController');

Route::post('checkout', 'CheckoutController@store')->middleware('non-admin');
Route::get('checkout', 'CheckoutController@index')->middleware('non-admin');


Route::get('{item}/{slug}', 'ViewItemController');

Route::get('mail', 'CheckoutController@sendEmail');