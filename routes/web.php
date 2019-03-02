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


Route::group(['prefix' => 'admin'], function() {

/*
*Academic routes
*/

Route::get('academic-years', 'AdminController@getAcademicYear')->name('academic-years');
Route::post('add-academic-year', 'AdminController@postAcademicYear')->name('post-academic-year');
Route::get('edit-academic-year/{id}', 'AdminController@editAcademicYear')->name('edit-academic-year');
Route::post('edit-academic-year/{id}', 'AdminController@updateAcademicYear')->name('update-academic-year');

/*
*Departmental routes
*/
Route::get('departments', 'AdminController@getDepartment')->name('departments');
Route::post('add-department', 'AdminController@postDepartment')->name('post-department');
Route::get('edit-department/{id}', 'AdminController@editDepartment')->name('edit-department');
Route::post('edit-department/{id}', 'AdminController@updateDepartment')->name('update-department');



/*
*Contribution routes
*/
Route::get('contributions', 'AdminController@getContribution')->name('contributions');
Route::post('/add-contribution', 'AdminController@postContribution')->name('post-contribution');
Route::get('edit-contribution/{id}', 'AdminController@editContribution')->name('edit-contribution');
Route::post('edit-contribution/{id}', 'AdminController@updateContribution')->name('update-contribution');





});



Route::group(['prefix' => 'student'], function() {


Route::get('contributions', 'StudentController@getContribution')->name('stdcontributions');
Route::post('/add-contribution', 'StudentController@postContribution')->name('post-stdcontribution');
Route::get('edit-contribution/{id}', 'StudentController@editContribution')->name('edit-stdcontribution');
Route::post('edit-contribution/{id}', 'StudentController@updateContribution')->name('update-stdcontribution');



});


