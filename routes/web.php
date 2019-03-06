<?php

Route::get('/', 'HomeController@welcome');

Auth::routes();

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::resource('categories', 'CategoriesController');
    Route::resource('items', 'ItemsController');
    Route::resource('users', 'UsersController');
});