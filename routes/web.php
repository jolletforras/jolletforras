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

Route::get('/terkep/tarsak', 'MapController@members');
Route::get('/terkep/csoportok', 'MapController@groups');

Route::get('/tarsak', 'ProfilesController@index');
Route::post('/user/filter', 'ProfilesController@filter');
Route::get('/profil/{id}/{name?}', ['as' => 'profile.show', 'uses' => 'ProfilesController@show']);

Route::get('/profilom/modosit', 'ProfilesController@edit');
Route::post('/profil/{id}/modosit', 'ProfilesController@update');

Route::get('/profilom/feltolt_profilkep', ['as' => 'profil.uploadimage', 'uses' => 'ProfilesController@uploadimage']);
Route::post('/profilom/feltolt_profilkep', ['as' => 'profil.saveimage', 'uses' => 'ProfilesController@saveimage']);

Route::get('/profilom/jelszocsere', 'ProfilesController@changepassword');
Route::post('/profilom/jelszocsere', ['as' => 'profil.savepassword', 'uses' => 'ProfilesController@savepassword']);

Route::get('/profilom/beallitasok',
    ['as' => 'profil.editsettings', 'uses' =>'ProfilesController@editsettings']);
Route::post('/profilom/beallitasok',
    ['as' => 'profil.updatesettings', 'uses' => 'ProfilesController@updatesettings']);
Route::get('profilom/deaktival', 'ProfilesController@delete');

Route::get('jovahagyra_var', 'ProfilesController@waitingforapprove');
Route::get('jovahagy/{id}', 'ProfilesController@approve');
Route::get('elutasit/{id}', 'ProfilesController@decline');

Route::get('/tagok/ertes/{id}/{tag}', 'SkillsController@profiles_show');
Route::post('/skill/filter', 'SkillsController@profiles_filter');

Route::get('/meghivo/uj', 'InviteController@create');
Route::post('/meghivo/uj', 'InviteController@store');
Route::get('meghivo/aktival/{code}', 'InviteController@activate');


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
Route::post('/esemeny/{id}/{title}/modosit', 'EventsController@update');


Route::get('/kezdemenyezesek', 'ProjectsController@index');
Route::get('/kezdemenyezes/{id}/{title}', 'ProjectsController@show');
Route::get('/kezdemenyezes/uj', 'ProjectsController@create');
Route::post('kezdemenyezes/uj', 'ProjectsController@store');
Route::get('/kezdemenyezes/{id}/{title}/modosit', 'ProjectsController@edit');
Route::post('/kezdemenyezes/{id}/{title}/modosit', 'ProjectsController@update');
Route::get('/kezdemenyezes/{id}/{title}/torol', 'ProjectsController@delete');
Route::get('/kezdemenyezes/ertes/{id}/{tag}', 'SkillsController@projects_show');

Route::get('/forum', 'ForumsController@index');
Route::get('/forum/{id}/{title}', 'ForumsController@show');
Route::get('/forum/uj', 'ForumsController@create');
Route::post('forum/uj', 'ForumsController@store');
Route::get('/forum/{id}/{title}/modosit', 'ForumsController@edit');
Route::post('/forum/{id}/{title}/modosit', 'ForumsController@update');
Route::get('/forum/cimke/{id}/{tag}', 'ForumTagsController@show');

Route::post('/comment', 'CommentsController@comment');
Route::post('/comment_delete', 'CommentsController@comment_delete');
Route::post('/ask_comment_notice', 'CommentsController@ask_comment_notice');
