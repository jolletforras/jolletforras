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

Route::get('/profil/{id}/{name}/aktival/{code}', ['as' => 'activate', 'uses' => 'Auth\RegisterController@activate']);

Route::get('/jelszo/ideiglenes', 'Auth\ForgotPasswordController@newpassword');
Route::post('/jelszo/email', 'Auth\ForgotPasswordController@sendpassword');

Route::get('/', 'HomeController@index');
Route::get('/tortenesek', 'HomeController@lastweeks');
Route::get('/programok', 'HomeController@events');
Route::get('/kapcsolat', 'HomeController@connection');
Route::post('/kepfeltoltes', 'HomeController@uploadimage');
Route::get('/kozossegimegallapodas', 'HomeController@socialagreement');
Route::get('/tudnivalok', 'HomeController@aboutsite');
Route::get('/adatkezeles', 'HomeController@datahandling');

Route::get('/az-uj-vilag-hangjai', 'PodcastController@index');
Route::get('/az-uj-vilag-hangjai/{id}/{title}', 'PodcastController@show');
Route::get('/podcast/uj', 'PodcastController@create');
Route::post('/podcast/uj', 'PodcastController@store');
Route::get('/podcast/{id}/{title}/modosit', 'PodcastController@edit');
Route::post('/podcast/{id}/{title}/modosit', 'PodcastController@update');

Route::get('/terkep', 'MapController@members');
Route::get('/terkep/tarsak', 'MapController@members');
Route::get('/terkep/csoportok', 'MapController@groups');
Route::get('/terkep/tarsak/cimke/{id}/{tag}', 'MapController@user_skill_show');
Route::get('/terkep/csoportok/cimke/{id}/{tag}', 'MapController@group_tag_show');

Route::get('/tarsak', 'ProfilesController@index');
Route::post('/user/filter', 'ProfilesController@filter');
Route::get('/profil/{id}/{name?}', ['as' => 'profile.show', 'uses' => 'ProfilesController@show']);

Route::get('/profilom/modosit', ['as' => 'profil.edit', 'uses' => 'ProfilesController@edit']);
Route::post('/profil/{id}/modosit', ['as' => 'profil.update', 'uses' => 'ProfilesController@update']);

Route::get('/profilom/feltolt_profilkep', ['as' => 'profil.uploadimage', 'uses' => 'ProfilesController@uploadimage']);
Route::post('/profilom/feltolt_profilkep', ['as' => 'profil.saveimage', 'uses' => 'ProfilesController@saveimage']);

Route::get('/profilom/jelszocsere', 'ProfilesController@changepassword');
Route::post('/profilom/jelszocsere', ['as' => 'profil.savepassword', 'uses' => 'ProfilesController@savepassword']);

Route::get('/profilom/beallitasok',  ['as' => 'profil.editsettings', 'uses' =>'ProfilesController@editsettings']);
Route::post('/profilom/beallitasok', ['as' => 'profil.updatesettings', 'uses' => 'ProfilesController@updatesettings']);
Route::get('profilom/deaktival', 'ProfilesController@delete');

Route::get('jovahagyra_var', 'ProfilesController@waitingforapprove');
Route::get('jovahagy/{id}', 'ProfilesController@approve');
Route::get('elutasit/{id}', 'ProfilesController@decline');

Route::get('/tagok/ertes/{id}/{tag}', 'ProfileTagsController@profiles_show_by_skill');
Route::post('/skill/filter', 'ProfileTagsController@profiles_filter_by_skill');

Route::get('/tagok/erdeklodes/{id}/{tag}', 'ProfileTagsController@profiles_show_by_interest');
Route::post('/interest/filter', 'ProfileTagsController@profiles_filter_by_interest');

Route::get('/meghivo/uj', 'InviteController@create');
Route::post('/meghivo/uj', 'InviteController@store');
Route::get('meghivo/aktival/{code}', 'InviteController@activate');

//az éles adatbázisban a hiányzó notice-ok felvétele
//Route::get('/add_notice', 'GroupsController@add_notice');

Route::get('/csoportok', 'GroupsController@index');
Route::get('/csoport/cimke/{id}/{tag}', 'TagsController@group_show');
Route::post('/group/filter', 'GroupsController@filter');
Route::get('/csoport/{id}/{name}', 'GroupsController@show');
Route::get('/csoport/uj', 'GroupsController@create');
Route::post('/csoport/uj', 'GroupsController@store');
Route::get('/csoport/{id}/{name}/modosit', 'GroupsController@edit');
Route::post('/csoport/{id}/{name}/modosit', 'GroupsController@update');

Route::get('/csoport/{id}/{name}/kepfeltoltes', 'GroupsController@uploadimage');
Route::post('/csoport/{id}/{name}/kepfeltoltes', 'GroupsController@saveimage');

//Route::get('/cron_teszt', 'GroupsController@cron_test');


Route::post('/csoport/{id}/{name}/csatlakozas', 'GroupsController@join');
Route::get('/csoport/{id}/{name}/kilepes', 'GroupsController@leave');
Route::post('/csoport/{id}/saveadmin', 'GroupsController@saveAdmin');
Route::post('/csoport/{id}/removemember', 'GroupsController@removeMember');
Route::post('/csoport/{id}/invite', 'GroupsController@invite');

Route::get('/csoport/{group_id}/{group_slug}/beszelgetesek', 'GroupThemesController@index');
Route::get('/csoport/{group_id}/{group_slug}/kozlemenyek', 'GroupThemesController@announcement');
Route::get('/csoport/{group_id}/{group_slug}/lezart-beszelgetesek', 'GroupThemesController@closedthemes');
Route::get('/csoport/{group_id}/{group_slug}/tema/{forum_id}/{forum_slug}', 'GroupThemesController@show');
Route::get('/csoport/{group_id}/{group_slug}/tema/uj', 'GroupThemesController@create');
Route::post('/csoport/{group_id}/{group_slug}/tema/uj', 'GroupThemesController@store');
Route::get('/csoport/{group_id}/{group_slug}/tema/{forum_id}/{forum_slug}/modosit', 'GroupThemesController@edit');
Route::post('/csoport/{group_id}/{group_slug}/tema/{forum_id}/{forum_slug}/modosit', 'GroupThemesController@update');

Route::get('/csoport/{group_id}/{group_slug}/lezar-beszelgetest/{forum_id}/{forum_slug}', 'GroupThemesController@closetheme');
Route::get('/csoport/{group_id}/{group_slug}/megnyit-beszelgetest/{forum_id}/{forum_slug}', 'GroupThemesController@opentheme');
Route::get('/csoport/{group_id}/{group_slug}/tema/cimke/{tag_id}/{tag}', 'TagsController@group_theme_show');

Route::get('/csoport/{id}/{slug}/tagok', 'GroupsController@members');

Route::get('/csoport/{id}/{slug}/esemenyek', 'GroupsController@events');
Route::get('/csoport/{id}/{slug}/esemeny/uj', 'GroupsController@eventcreate');

Route::get('/csoport/{id}/{group_slug}/hirek', 'GroupNewsController@index');
Route::get('/csoport/{group_id}/{group_slug}/hir/{news_id}/{news_slug}', 'GroupNewsController@show');
Route::get('/csoport/{group_id}/{group_slug}/hir/uj', 'GroupNewsController@create');
Route::post('/hir/uj', 'GroupNewsController@store');
Route::get('/hir/{id}/{title}/modosit', 'GroupNewsController@edit');
Route::post('/hir/{id}/{title}/modosit', 'GroupNewsController@update');


Route::get('/email/{code}/csoport/{group_id}/{group_slug}/tema/{forum_id}/{forum_slug}', 'NoticesController@email_theme_login');
Route::get('/email/{code}/esemeny/{id}/{slug}', 'NoticesController@email_event_login');
Route::post('/getNotices', 'NoticesController@get_noticies');


Route::get('/irasok', 'ArticlesController@index');
Route::get('/profil/{user_id}/{slug}/irasok', 'ArticlesController@show_user_articles');
Route::get('/iras/{id}/{title}', 'ArticlesController@show');
Route::get('/iras/uj', 'ArticlesController@create');
Route::post('/iras/uj', 'ArticlesController@store');
Route::get('/iras/{id}/{title}/modosit', 'ArticlesController@edit');
Route::post('/iras/{id}/{title}/modosit', 'ArticlesController@update');


Route::get('/hirek', 'NewsController@index');
Route::get('/hir/{id}/{title}', 'NewsController@show');
//Route::get('/hir/uj', 'NewsController@create');
//Route::post('/hir/uj', 'NewsController@store');
//Route::get('/hir/{id}/{title}/modosit', 'NewsController@edit');
//Route::post('/hir/{id}/{title}/modosit', 'NewsController@update');
Route::get('/hir/cimke/{id}/{tag}', 'TagsController@news_show');



Route::get('/esemenyek', 'EventsController@index');
Route::get('/esemeny/{id}/{title}', 'EventsController@show');
Route::get('/esemeny/uj', 'EventsController@create');
Route::post('esemeny/uj', 'EventsController@store');
Route::get('/esemeny/{id}/{title}/modosit', 'EventsController@edit');
Route::post('/esemeny/{id}/{title}/modosit', 'EventsController@update');
Route::post('/esemeny/{id}/invite', 'EventsController@invite');
Route::post('/events_expired', 'EventsController@events_expired');

Route::get('/kezdemenyezesek', 'ProjectsController@index');
Route::get('/kezdemenyezes/{id}/{title}', 'ProjectsController@show');
Route::get('/kezdemenyezes/uj', 'ProjectsController@create');
Route::post('kezdemenyezes/uj', 'ProjectsController@store');
Route::get('/kezdemenyezes/{id}/{title}/modosit', 'ProjectsController@edit');
Route::post('/kezdemenyezes/{id}/{title}/modosit', 'ProjectsController@update');
Route::get('/kezdemenyezes/{id}/{title}/torol', 'ProjectsController@delete');
Route::get('/kezdemenyezes/{id}/{title}/kilep', 'ProjectsController@leave');
Route::post('/kezdemenyezes/{id}/saveadmin', 'ProjectsController@saveAdmin');
Route::get('/kezdemenyezes/ertes/{id}/{tag}', 'TagsController@projects_show');

Route::get('/hirlevelek', 'NewslettersController@index');
Route::get('/hirlevel/{id}/{title}', 'NewslettersController@show');
Route::get('/hirlevel/uj', 'NewslettersController@create');
Route::post('/hirlevel/uj', 'NewslettersController@store');
Route::get('/hirlevel/{id}/{title}/modosit', 'NewslettersController@edit');
Route::post('/hirlevel/{id}/{title}/modosit', 'NewslettersController@update');

Route::get('/forum', 'ForumsController@index');
Route::get('/forum/{id}/{title}', 'ForumsController@show');
Route::get('/forum/uj', 'ForumsController@create');
Route::post('/forum/uj', 'ForumsController@store');
Route::get('/forum/{id}/{title}/modosit', 'ForumsController@edit');
Route::post('/forum/{id}/{title}/modosit', 'ForumsController@update');
Route::get('/forum/cimke/{id}/{tag}', 'TagsController@forum_show');

Route::get('/ajanlo', 'CommendationsController@index');
Route::get('/ajanlo/{id}/{title}', 'CommendationsController@show');
Route::get('/ajanlo/uj', 'CommendationsController@create');
Route::post('ajanlo/uj', 'CommendationsController@store');
Route::get('/ajanlo/{id}/{title}/modosit', 'CommendationsController@edit');
Route::post('/ajanlo/{id}/{title}/modosit', 'CommendationsController@update');

Route::post('/comment', 'CommentsController@comment');
Route::post('/comment_delete', 'CommentsController@comment_delete');
Route::post('/ask_comment_notice', 'CommentsController@ask_comment_notice');
Route::post('/comment_update', 'CommentsController@comment_update');

//Route::get('/set_shorted_text', 'CommentsController@set_shorted_text');
//Route::get('/set_shorted_text', 'ForumsController@set_shorted_text');
//Route::get('/set_body', 'ForumsController@set_body');

Route::get('/set_body', 'EventsController@set_body');
Route::get('/set_image', 'EventsController@set_image');
Route::get('/set_shorted_text', 'EventsController@set_shorted_text');

Route::post('/send_message', 'MessageController@send_message');

