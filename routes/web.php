<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@login')->name('dologin');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('home');

    Route::group(['prefix' => '/parent_tag_ones'], function () {
        Route::get('/', 'ParentTagOneController@index')->name('parent_tag_ones');
        Route::any('/create', 'ParentTagOneController@create')->name('parent_tag_one_create');
        Route::any('/edit/{id}', 'ParentTagOneController@edit')->name('parent_tag_one_edit');
        Route::get('/delete/{id}', 'ParentTagOneController@delete')->name('parent_tag_one_delete');
    });

    Route::group(['prefix' => '/parent_tag_twos'], function () {
        Route::get('/', 'ParentTagTwoController@index')->name('parent_tag_twos');
        Route::any('/create', 'ParentTagTwoController@create')->name('parent_tag_two_create');
        Route::any('/edit/{id}', 'ParentTagTwoController@edit')->name('parent_tag_two_edit');
        Route::get('/delete/{id}', 'ParentTagTwoController@delete')->name('parent_tag_two_delete');
    });

    Route::group(['prefix' => '/parent_tag_threes'], function () {
        Route::get('/', 'ParentTagThreeController@index')->name('parent_tag_threes');
        Route::any('/create', 'ParentTagThreeController@create')->name('parent_tag_three_create');
        Route::any('/edit/{id}', 'ParentTagThreeController@edit')->name('parent_tag_three_edit');
        Route::get('/delete/{id}', 'ParentTagThreeController@delete')->name('parent_tag_three_delete');
    });

    Route::group(['prefix' => '/parent_tag_fours'], function () {
        Route::get('/', 'ParentTagFourController@index')->name('parent_tag_fours');
        Route::any('/create', 'ParentTagFourController@create')->name('parent_tag_four_create');
        Route::any('/edit/{id}', 'ParentTagFourController@edit')->name('parent_tag_four_edit');
        Route::get('/delete/{id}', 'ParentTagFourController@delete')->name('parent_tag_four_delete');
    });

    Route::group(['prefix' => '/tags'], function () {
        Route::get('/', 'TagController@index')->name('tags');
        Route::any('/create', 'TagController@create')->name('tag_create');
        Route::any('/edit/{id}', 'TagController@edit')->name('tag_edit');
        Route::get('/delete/{id}', 'TagController@delete')->name('tag_delete');
    });

    Route::group(['prefix' => '/need_tags'], function () {
        Route::get('/', 'NeedTagController@index')->name('need_tags');
        Route::any('/create', 'NeedTagController@create')->name('need_tag_create');
        Route::any('/edit/{id}', 'NeedTagController@edit')->name('need_tag_edit');
        Route::get('/delete/{id}', 'NeedTagController@delete')->name('need_tag_delete');
    });

    Route::group(['prefix' => '/collections'], function () {
        Route::get('/', 'CollectionController@index')->name('collections');
        Route::any('/create', 'CollectionController@create')->name('collection_create');
        Route::any('/edit/{id}', 'CollectionController@edit')->name('collection_edit');
        Route::get('/delete/{id}', 'CollectionController@delete')->name('collection_delete');
    });

    Route::group(['prefix' => '/products'], function () {
        Route::get('/', 'ProductController@index')->name('products');
        Route::any('/create', 'ProductController@create')->name('product_create');
        Route::any('/edit/{id}', 'ProductController@edit')->name('product_edit');
        Route::get('/delete/{id}', 'ProductController@delete')->name('product_delete');
    });
});

