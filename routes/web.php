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
Route::get('/api/courses/{id?}','CoursesController@courses');
Route::get('/courses/index','CoursesController@index');
Route::get('/courses/login','CoursesController@login');
Route::post('/courses/login','CoursesController@login_post');
Route::get('/courses/{id?}','CoursesController@show');

// Route::get('/data/test','CoursesController@dataTest');
Route::get('/data/test','FileController@FileList');
Route::get('/group/test','FileController@getGroups');
Route::get('/data/files','FileController@getFiles');
Route::get('/blank','FileController@blank');
Route::get('/data/iframe','FileController@iframe');
Auth::routes();


Route::get('/grade/login','GradeController@login');
Route::post('/grade/loginpost','GradeController@login_post');
Route::get('/grade/show/{ddlXQ}{ddlXN}','GradeController@showGrades');
Route::get('/grade/{id?}','GradeController@getGrades');

Route::get('/home', 'HomeController@index')->name('home');
