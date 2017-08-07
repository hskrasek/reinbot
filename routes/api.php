<?php

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

Route::any('interactions', [
    'as'   => 'interactions',
    'uses' => 'InteractionsController',
]);

Route::any('events', [
    'as'   => 'events',
    'uses' => 'EventsController',
]);
