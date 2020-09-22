<?php

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

Auth::routes(['register' => false, 'verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'verified']], function(){
    Route::get('/password','UserController@alterarSenha')->name('password.change');
    Route::patch('/password','UserController@guardarSenha')->name('update.password');
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::patch('/users/{user}/ativar', 'UserController@ativar')->name('users.ativar');
    Route::patch('/config/{config}/variavel', 'ConfigController@variavel')->name('config.variavel');
    Route::get('/moisture', 'MoistureController@index')->name('moistures.index');
    Route::get('/agua', 'AguaController@index')->name('agua.index');
    Route::get('/luz', 'LuzController@index')->name('luz.index');
    Route::get('/config', 'ConfigController@index')->name('config.index');
    Route::get('/image', 'ImageController@index')->name('images.index');
    Route::get('/tempehumid', 'TempehumidController@index')->name('tempehumid.index');
    Route::get('/atuadore', 'AtuadoreController@index')->name('atuadore.index');
    Route::patch('/atuadore/{atuadore}/ativar', 'AtuadoreController@ativar')->name('atuadores.ativar');
}
);
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
