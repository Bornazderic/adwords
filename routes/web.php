<?php

use App\Http\Controllers\ExportsController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/download', 'HomeController@arrayCreate')->name('download');

Route::get('index' , 'HomeController@index')->name('index');
Route::delete('export/{id}' , 'HomeController@delete')->name('delete');

//Route::resources('save', 'ExportsController');

