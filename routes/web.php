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
    Route::group(['prefix' => '/tags'], function () {
        Route::get('/', 'TagController@index')->name('tags');
        Route::any('/create', 'TagController@create')->name('tag_create');
        Route::any('/edit/{id}', 'TagController@edit')->name('tag_edit');
        Route::get('/delete/{id}', 'TagController@delete')->name('tag_delete');
    });
});

