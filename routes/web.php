<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/jelszo/ideiglenes', 'Auth\ForgotPasswordController@newpassword');
Route::post('/jelszo/email', 'Auth\ForgotPasswordController@sendpassword');

Route::get('/', 'HomeController@index');
Route::get('/programok', 'HomeController@events');
Route::get('/kapcsolat', 'HomeController@connection');
Route::post('/kepfeltoltes', 'HomeController@uploadimage');
Route::get('/kozossegimegallapodas', 'HomeController@socialagreement');
Route::get('/tudnivalok', 'HomeController@aboutsite');
Route::get('/adatkezeles', 'HomeController@datahandling');

//Route::get('/terkep/tarsak', 'MapController@members');
//Route::get('/terkep/csoportok', 'MapController@groups');


Route::get('/irasok', 'ArticlesController@index');
Route::get('/iras/{id}/{title}', 'ArticlesController@show');
Route::get('/iras/uj', 'ArticlesController@create');
Route::post('/iras/uj', 'ArticlesController@store');
Route::get('/iras/{id}/{title}/modosit', 'ArticlesController@edit');
Route::post('/iras/{id}/{title}/modosit', 'ArticlesController@update');


Route::get('/hirek', 'NewsController@index');
Route::get('/hir/uj', 'NewsController@create');
Route::post('/hir/uj', 'NewsController@store');
Route::get('/hir/{id}/modosit', 'NewsController@edit');
Route::post('/hir/{id}/modosit', 'NewsController@update');


Route::get('/esemenyek', 'EventsController@index');
Route::get('/esemeny/{id}/{title}', 'EventsController@show');
Route::get('/esemeny/uj', 'EventsController@create');
Route::post('esemeny/uj', 'EventsController@store');
Route::get('/esemeny/{id}/{title}/modosit', 'EventsController@edit');
Route::resource('esemeny', 'EventsController', ['only' => ['update']]);

