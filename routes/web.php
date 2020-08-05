<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use App\Models\GemTypes;
use App\Coefficient;
use App\Http\Middleware\CheckGroup;
use App\Http\Middleware\CheckRole;
use App\Http\Requests\EditCoeffsRequest;
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
})->name('main');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('about', function () {
    return view('about');
})->name('about');

Route::get('gemtypes/addgemtype', 'GemTypesController@addGemTypeForm')->name('addGemTypeFormView')->middleware('group:mgnome');
Route::post('gemtypes/addgemtype/submit', 'GemTypesController@addGemTypeFormSubmit')->name('addGemTypeFormSubmit')->middleware('group:mgnome');
Route::get('gemtypes', 'GemTypesController@showAllGems')->name('gemTypes')->middleware('group:mgnome');
Route::get('gemtypes/{id}', 'GemTypesController@showOneGem')->name('gemType')->middleware('group:mgnome')->middleware('group:mgnome');
Route::get('gemtypes/{id}/edit', 'GemTypesController@editGemTypeForm')->name('editGemTypeFormView')->middleware('group:mgnome');
Route::post('gemtypes/{id}/edit', 'GemTypesController@editGemTypeFormSubmit')->name('editGemTypeFormSubmit')->middleware('group:mgnome');
Route::get('gemtypes/{id}/delete', 'GemTypesController@deleteGemType')->name('deleteGemType')->middleware('group:mgnome');
Route::post('coeffs/submit', function (EditCoeffsRequest $req) {
    $coeffs = Coefficient::all()->first();
    $c1 = $req->input('coeff_1');
    $c2 = $req->input('coeff_2');
    $c3 = $req->input('coeff_3');

    if ($c1 + $c2 + $c3 != 1) throw new Exception('sum of c1,c2,c3 must be = 1 ');
    $coeffs->coeff_1 = $c1;
    $coeffs->coeff_2 = $c2;
    $coeffs->coeff_3 = $c3;
    $coeffs->save();
    return redirect()->route('gemTypes')->with('success', 'Coeffs has been edited successfully');
})->name('coeffsSubmit')->middleware('group:mgnome');

Route::get('users', 'UserController@showAllUsers')->name('users');
Route::get('users/{id}', 'UserController@showOneUser')->name('user')->middleware('yourselfOrMg');
Route::post('users/{id}/edit', 'UserController@editUser')->name('editUser')->middleware('yourselfOrMg');
Route::get('users/{id}/delete', 'UserController@deleteUser')->name('deleteUser')->middleware('group:mgnome');

Route::get('gems', 'GemController@showAllGems')->name('gems');
Route::get('gems/add', 'GemController@addGemForm')->name('addGemFormView')->middleware('group:gnome');
Route::post('gems/add/submit', 'GemController@addGemFormSubmit')->name('addGemFormSubmit')->middleware('group:gnome');
Route::get('gems/{id}/delete', 'GemController@deleteGem')->name('deleteGem')->middleware('group:mgnome');

Route::get('assigngems', 'GemController@assignGems')->name('assignGems')->middleware('group:mgnome');
Route::post('assigngems/submit', 'GemController@assignGemsSubmit')->name('assignGemsSubmit')->middleware('group:mgnome');
Route::get('gems/{id}/accept', 'GemController@acceptGem')->name('acceptGem')->middleware('group:elf');
