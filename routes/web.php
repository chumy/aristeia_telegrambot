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


Route::get('/activity', 'TelegramController@updatedActivity');

Route::get('/message/{txt}', 'TelegramController@sendMessage')
        ->name('mensaje');


Route::post('845501750:AAGoWNC6_UKsZtKq6WIzZZ58iRWt_eTO9p4/webhook', 'TelegramController@updatedActivity');

Route::get('/setWebhook', 'TelegramController@setWebhook');

Route::get('/unsetWebhook', 'TelegramController@unsetWebhook');    

Route::get('/ngrok/{txt}', 'TelegramController@ngrok');
        
