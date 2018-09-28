<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Rotas para cadastro de Usuário
Route::post('login', 'Api\UserController@login')->name('login');
Route::post('register', 'Api\UserController@register');

//Rotas Clientes sem autenticação
Route::get('clientes', 'Api\ClienteController@index');
Route::get('clientes/{id}', 'Api\ClienteController@show');

//Rotas Dependentes sem autenticação
Route::get('dependentes', 'Api\DependenteController@index');
Route::get('dependentes/{id}', 'Api\DependenteController@show');

Route::group(['middleware' => 'auth:api'], function(){
//Rotas para cadastro de Usuário Autenticada
    Route::post('details', 'Api\UserController@details');

    //Rotas Clientes Autenticada
    Route::post('clientes', 'Api\ClienteController@store');
    Route::put('clientes/{id}', 'Api\ClienteController@update');
    Route::delete('clientes/{id}', 'Api\ClienteController@destroy');

    //Rotas Dependentes Autenticada
    Route::post('dependentes', 'Api\DependenteController@store');
    Route::put('dependentes/{id}', 'Api\DependenteController@update');
    Route::delete('dependentes/{id}', 'Api\DependenteController@destroy');
    
    
});

//Rota default - em caso de utilizador acessar um endpoint errado.
Route::fallback(function() {
    return response()->json([
        'error' => 'no route',
        'message'=> 'Voce acessou um endpoint inexistente. Por favor, verifique a documentacao.'
    ]);
});
