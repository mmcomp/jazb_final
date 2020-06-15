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
Route::get('/register', 'RegisterController@index')->name('register');
Route::post('/register', 'RegisterController@sendsms')->name('sendsms');
Route::post('/register/checksms', 'RegisterController@checksms')->name('checksms');
Route::post('/register/createuser', 'RegisterController@createuser')->name('createuser');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('home');

    Route::group(['prefix' => '/need_parent_tag_ones'], function () {
        Route::get('/', 'NeedParentTagOneController@index')->name('need_parent_tag_ones');
        Route::any('/create', 'NeedParentTagOneController@create')->name('need_parent_tag_one_create');
        Route::any('/edit/{id}', 'NeedParentTagOneController@edit')->name('need_parent_tag_one_edit');
        Route::get('/delete/{id}', 'NeedParentTagOneController@delete')->name('need_parent_tag_one_delete');
    });

    Route::group(['prefix' => '/need_parent_tag_twos'], function () {
        Route::get('/', 'NeedParentTagTwoController@index')->name('need_parent_tag_twos');
        Route::any('/create', 'NeedParentTagTwoController@create')->name('need_parent_tag_two_create');
        Route::any('/edit/{id}', 'NeedParentTagTwoController@edit')->name('need_parent_tag_two_edit');
        Route::get('/delete/{id}', 'NeedParentTagTwoController@delete')->name('need_parent_tag_two_delete');
    });

    Route::group(['prefix' => '/need_parent_tag_threes'], function () {
        Route::get('/', 'NeedParentTagThreeController@index')->name('need_parent_tag_threes');
        Route::any('/create', 'NeedParentTagThreeController@create')->name('need_parent_tag_three_create');
        Route::any('/edit/{id}', 'NeedParentTagThreeController@edit')->name('need_parent_tag_three_edit');
        Route::get('/delete/{id}', 'NeedParentTagThreeController@delete')->name('need_parent_tag_three_delete');
    });

    Route::group(['prefix' => '/need_parent_tag_fours'], function () {
        Route::get('/', 'NeedParentTagFourController@index')->name('need_parent_tag_fours');
        Route::any('/create', 'NeedParentTagFourController@create')->name('need_parent_tag_four_create');
        Route::any('/edit/{id}', 'NeedParentTagFourController@edit')->name('need_parent_tag_four_edit');
        Route::get('/delete/{id}', 'NeedParentTagFourController@delete')->name('need_parent_tag_four_delete');
    });

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

    Route::group(['prefix' => '/temperatures'], function () {
        Route::get('/', 'TemperatureController@index')->name('temperatures');
        Route::any('/create', 'TemperatureController@create')->name('temperature_create');
        Route::any('/edit/{id}', 'TemperatureController@edit')->name('temperature_edit');
        Route::get('/delete/{id}', 'TemperatureController@delete')->name('temperature_delete');
    });

    Route::group(['prefix' => '/students'], function () {
        Route::get('/', 'StudentController@index')->name('students');
        Route::any('/create', 'StudentController@create')->name('student_create');
        Route::any('/edit/{id}', 'StudentController@edit')->name('student_edit');
        Route::get('/delete/{id}', 'StudentController@delete')->name('student_delete');
    });

    Route::get('marketerprofile','MarketerController@profile')->name('marketerprofile');
    
    Route::group(['prefix' => '/sources'], function () {
        Route::get('/', 'SourceController@index')->name('sources');
        Route::any('/create', 'SourceController@create')->name('source_create');
        Route::any('/edit/{id}', 'SourceController@edit')->name('source_edit');
        Route::get('/delete/{id}', 'SourceController@delete')->name('source_delete');
    });
});

