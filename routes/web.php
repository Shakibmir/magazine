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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/coordinator', 'CoordinatorController@index')->name('coordinator');
Route::get('/manager', 'ManagerController@index')->name('manager');
Route::get('/student', 'StudentController@index')->name('student');
Route::get('/faculty', 'FacultyController@index')->name('faculty');




