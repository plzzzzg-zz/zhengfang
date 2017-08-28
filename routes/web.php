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
Route::get('/courses/auth','CoursesController@yiban_auth');
Route::get('/api/courses/{id?}','CoursesController@courses');
Route::get('/courses/index','CoursesController@index');
Route::get('/courses/login','CoursesController@login');
Route::post('/courses/login','CoursesController@login_post');
Route::get('/courses/{id?}','CoursesController@show');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
