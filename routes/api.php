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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List all customers
Route::get('customers', 'CustomerController@index');
// list single customer
Route::get('customer/{id}', 'CustomerController@show');
// Create a new customer
Route::post('customer', 'CustomerController@store');
// Update a customer
Route::put('customer/{id}', 'CustomerController@update');
// Delete a customer
Route::delete('customer/{id}', 'CustomerController@destroy');

// List all dependents
Route::get('dependents', 'DependentController@index');
// list single dependent
Route::get('dependent/{id}', 'DependentController@show');
// Create a new dependent
Route::post('dependent', 'DependentController@store');
// Update a dependent
Route::put('dependent/{id}', 'DependentController@update');
// Delete a dependent
Route::delete('dependent/{id}', 'DependentController@destroy');
