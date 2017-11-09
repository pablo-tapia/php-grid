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

Route::get('/', 'Home@index');
Route::get('/async', 'Home@asyncForSelects');
Route::get('/search', 'Home@search');


Auth::routes();

Route::get('/asyncAdmin', 'Admin@asyncIndex');
Route::get('/admin', 'Admin@index')->name('admin');
Route::get('/admin/add/{id?}', 'Admin@eventsForm')->name('add');
Route::post('/admin/save', 'Admin@save')->name('save');
Route::get('/admin/delete', 'Admin@delete')->name('delete');
