<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::any('/fix-rates', [
    'uses' => 'UpdateRatesController@fixExchangeRates'
]);

Route::any('/get-currencies', [
    'uses' => 'MainController@getCurrencies'
]);

Route::any('/', [
    'uses' => 'MainController@showCurrencies'
]);
