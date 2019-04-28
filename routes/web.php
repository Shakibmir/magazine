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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();


// Route::get('/home', 'HomeController@index')->name('home');
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
Route::match(['get', 'post'], 'contributions', 'AdminController@getContribution')->name('contributions');
// Route::get('contributions/{year}', 'AdminController@getContributionsByYear')->name('contributions-year');
Route::match(['get', 'post'], 'approved-contributions', 'AdminController@getApprovedContributions')->name('approved-contributions');
Route::match(['get', 'post'],'commented-contributions', 'AdminController@getCommentedContribution')->name('commented-contributions');
Route::match(['get', 'post'], 'pending-contributions', 'AdminController@getPendingContribution')->name('pending-contributions');
Route::post('/add-contribution', 'AdminController@postContribution')->name('post-contribution');
Route::get('edit-contribution/{id}', 'AdminController@editContribution')->name('edit-contribution');
Route::post('edit-contribution/{id}', 'AdminController@updateContribution')->name('update-contribution');

Route::get('contribution/{id}', 'AdminController@getSingleContribution')->name('single-contribution');
Route::post('contribution/{id}', 'AdminController@addComment')->name('add-comment');

/*
* Contribution Approval routes
*/

Route::post('approve-contributions', 'AdminController@postApproveContributions')->name('approve-contributions');
Route::get('approve-contribution/{id}', 'AdminController@getApproveContribution')->name('approve-contribution');
Route::post('download-approved', 'AdminController@postDownloadApproved')->name('download-approved');



/*
* Reports routes
*/
Route::match(['get', 'post'], 'con-report', 'AdminController@getContributionReport')->name('con-report');
Route::match(['get', 'post'], 'con-percentage', 'AdminController@getContributionPercentage')->name('con-percentage');
Route::get('contributor-number', 'AdminController@getContributorNumberPage')->name('contributor-number');
Route::post('contributor-number', 'AdminController@getContributorNumber')->name('post-contributor-number');
Route::match(['get', 'post'], 'contributor-without-comment', 'AdminController@getContributorWithoutComment')->name('contributor-without-comment');

/*
* User routes
*/
Route::get('users', 'AdminController@getUsers')->name('users');
Route::get('add-user', 'AdminController@getAddUser')->name('add-user');
Route::post('add-user', 'AdminController@postAddUser')->name('post-add-user');
Route::get('edit-user/{id}', 'AdminController@getEditUser')->name('edit-user');
Route::post('edit-user/{id}', 'AdminController@postUpdateUser')->name('post-update-user');
Route::get('change-password', 'AdminController@getChangePassword')->name('change-password');
Route::post('change-password', 'AdminController@postChangePassword')->name('change-user-password');

/*
* Zip Download routes
*/
Route::get('zip', 'AdminController@zip')->name('zip');

});





Route::group(['prefix' => 'manager'], function() {

/*
*Contribution routes
*/
Route::match(['get', 'post'], 'contributions', 'ManagerController@getContribution')->name('man-contributions');
Route::match(['get', 'post'], 'approved-contributions', 'ManagerController@getApprovedContributions')->name('man-approved-contributions');
Route::match(['get', 'post'],'commented-contributions', 'ManagerController@getCommentedContribution')->name('man-commented-contributions');
Route::match(['get', 'post'], 'pending-contributions', 'ManagerController@getPendingContribution')->name('man-pending-contributions');


Route::get('contribution/{id}', 'ManagerController@getSingleContribution')->name('man-single-contribution');


/*
* Zip Download routes
*/
Route::get('zip', 'ManagerController@zip')->name('man-zip');
Route::post('download-approved', 'ManagerController@postDownloadApproved')->name('man-download-approved');


/*
* Marketing Manager Reports routes
*/
Route::match(['get', 'post'], 'con-report', 'ManagerController@getContributionReport')->name('man-con-report');
Route::match(['get', 'post'], 'con-percentage', 'ManagerController@getContributionPercentage')->name('man-con-percentage');
Route::get('contributor-number', 'ManagerController@getContributorNumberPage')->name('man-contributor-number');
Route::post('contributor-number', 'ManagerController@getContributorNumber')->name('man-post-contributor-number');
Route::match(['get', 'post'], 'contributor-without-comment', 'ManagerController@getContributorWithoutComment')->name('man-contributor-without-comment');


Route::get('academic-years', 'ManagerController@getAcademicYear')->name('man-academic-years');



});

Route::group(['prefix' => 'coordinator'], function() {



/*
*Contribution routes
*/
Route::match(['get', 'post'], 'contributions', 'CoordinatorController@getContribution')->name('coor-contributions');
Route::match(['get', 'post'], 'approved-contributions', 'CoordinatorController@getApprovedContributions')->name('coor-approved-contributions');
Route::match(['get', 'post'],'commented-contributions', 'CoordinatorController@getCommentedContribution')->name('coor-commented-contributions');
Route::match(['get', 'post'], 'pending-contributions', 'CoordinatorController@getPendingContribution')->name('coor-pending-contributions');

Route::get('contribution/{id}', 'CoordinatorController@getSingleContribution')->name('coor-single-contribution');
Route::post('contribution/{id}', 'CoordinatorController@addComment')->name('coor-add-comment');


/*
* Contribution Approval routes
*/

Route::post('approve-contributions', 'CoordinatorController@postApproveContributions')->name('coor-approve-contributions');
Route::get('approve-contribution/{id}', 'CoordinatorController@getApproveContribution')->name('coor-approve-contribution');
Route::post('download-approved', 'CoordinatorController@postDownloadApproved')->name('coor-download-approved');


/*
* Marketing Coordinator Reports routes
*/
Route::match(['get', 'post'], 'con-report', 'CoordinatorController@getContributionReport')->name('coor-con-report');
Route::match(['get', 'post'], 'con-percentage', 'CoordinatorController@getContributionPercentage')->name('coor-con-percentage');
Route::get('contributor-number', 'CoordinatorController@getContributorNumberPage')->name('coor-contributor-number');
Route::post('contributor-number', 'CoordinatorController@getContributorNumber')->name('coor-post-contributor-number');
Route::match(['get', 'post'], 'contributor-without-comment', 'CoordinatorController@getContributorWithoutComment')->name('coor-contributor-without-comment');


Route::get('academic-years', 'CoordinatorController@getAcademicYear')->name('coor-academic-years');


});


Route::group(['prefix' => 'student'], function() {


Route::match(['get', 'post'], 'contributions', 'StudentController@getContribution')->name('stdcontributions');
// Route::get('contributions/{year}', 'StudentController@getContributionsByYear')->name('stdcontributions-year');
Route::post('add-contribution', 'StudentController@postContribution')->name('post-stdcontribution');
Route::get('edit-contribution/{id}', 'StudentController@editContribution')->name('edit-stdcontribution');
Route::post('edit-contribution/{id}', 'StudentController@updateContribution')->name('update-stdcontribution');


Route::get('contribution/{id}', 'StudentController@getSingleContribution')->name('single-stdcontribution');
Route::post('contribution/{id}', 'StudentController@addComment')->name('add-stdcomment');

Route::get('academic-years', 'StudentController@getAcademicYear')->name('stdacademic-years');
Route::get('delete', 'StudentController@getDeleteCheck')->name('delete-check');

});


Route::group(['prefix' => 'guest'], function() {

/*
* Marketing Manager Reports routes
*/
Route::match(['get', 'post'], 'con-report', 'FacultyController@getContributionReport')->name('guest-con-report');
Route::match(['get', 'post'], 'con-percentage', 'FacultyController@getContributionPercentage')->name('guest-con-percentage');
Route::get('contributor-number', 'FacultyController@getContributorNumberPage')->name('guest-contributor-number');
Route::post('contributor-number', 'FacultyController@getContributorNumber')->name('guest-post-contributor-number');
Route::match(['get', 'post'], 'contributor-without-comment', 'FacultyController@getContributorWithoutComment')->name('guest-contributor-without-comment');


Route::get('academic-years', 'FacultyController@getAcademicYear')->name('guest-academic-years');



});

Route::get('register', 'AdminController@getAddUser')->name('register');
Route::post('register', 'AdminController@postAddUser')->name('register');


Route::get('change-password', 'HomeController@getChangePassword')->name('change-password');
Route::post('change-password', 'HomeController@postChangePassword')->name('change-user-password');
