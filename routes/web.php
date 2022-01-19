<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('route:clear');
    return 'CACHE CLEARED'; //Return anything
});

Auth::routes(['verify' => true]);


// FrontEnd Routes
Route::get('home', 'HomeController@redirectUser')->middleware('auth');
Route::namespace('Front')->group(function(){
    Route::any('/', 'HomeController@redirect')->name('home');
    Route::get('user/{id}', 'HomeController@index')->name('job_user');
    Route::post('save_job', 'HomeController@save_job')->name('save_job');
});


// Admin Dashboard Authenticatied Routes
Route::name('admin.')->namespace('Admin')->prefix('admin')->middleware('auth', 'admin')->group(function () {
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

    Route::name('work_category.')->prefix('work_category')->group(function () {
        Route::get('list', 'WorkCategoryController@index')->name('list');
        Route::get('add', 'WorkCategoryController@add')->name('add');
        Route::post('save', 'WorkCategoryController@save')->name('save');
        Route::get('edit', 'WorkCategoryController@edit')->name('edit');
        Route::get('delete', 'WorkCategoryController@delete')->name('delete');
    });

    Route::name('category.')->prefix('category')->group(function () {
        Route::get('list', 'CategoryController@index')->name('list');
        Route::get('add', 'CategoryController@add')->name('add');
        Route::post('save', 'CategoryController@save')->name('save');
        Route::get('edit', 'CategoryController@edit')->name('edit');
        Route::get('delete', 'CategoryController@delete')->name('delete');
    });

    Route::name('sub_category.')->prefix('sub_category')->group(function () {
        Route::get('list', 'SubCategoryController@index')->name('list');
        Route::get('add', 'SubCategoryController@add')->name('add');
        Route::post('save', 'SubCategoryController@save')->name('save');
        Route::get('edit', 'SubCategoryController@edit')->name('edit');
        Route::get('delete', 'SubCategoryController@delete')->name('delete');
    });

    Route::name('client.')->prefix('client')->group(function () {
        Route::get('list', 'ClientController@index')->name('list');
        Route::get('add', 'ClientController@add')->name('add');
        Route::post('save', 'ClientController@save')->name('save');
        Route::get('edit', 'ClientController@edit')->name('edit');
        Route::get('delete', 'ClientController@delete')->name('delete');
    });

    Route::name('source_job.')->prefix('source_job')->group(function () {
        Route::get('list', 'JobSourceController@index')->name('list');
        Route::get('add', 'JobSourceController@add')->name('add');
        Route::post('save', 'JobSourceController@save')->name('save');
        Route::get('edit', 'JobSourceController@edit')->name('edit');
        Route::get('delete', 'JobSourceController@delete')->name('delete');
    });

    Route::name('status_job.')->prefix('status_job')->group(function () {
        Route::get('list', 'JobStatusController@index')->name('list');
        Route::get('add', 'JobStatusController@add')->name('add');
        Route::post('save', 'JobStatusController@save')->name('save');
        Route::get('edit', 'JobStatusController@edit')->name('edit');
        Route::get('delete', 'JobStatusController@delete')->name('delete');
    });

    Route::name('user.')->prefix('user')->group(function () {
        Route::get('list', 'UserController@index')->name('list');
        Route::get('add', 'UserController@add')->name('add');
        Route::post('save', 'UserController@save')->name('save');
        Route::get('edit', 'UserController@edit')->name('edit');
        Route::get('delete', 'UserController@delete')->name('delete');
    });

    Route::name('job.')->prefix('job')->group(function () {
        Route::get('list', 'JobController@index')->name('list');
        Route::get('add', 'JobController@add')->name('add');
        Route::get('tasks', 'JobController@tasks')->name('tasks');
        Route::get('edit', 'JobController@edit')->name('edit');
        Route::get('delete', 'JobController@delete')->name('delete');
    });

    Route::name('ajax.')->prefix('ajax')->group(function () {
        Route::name('job.')->prefix('job')->group(function () {
            Route::get('subcategory_list', 'JobController@subcategory_list')->name('subcategory_list');
            Route::post('save', 'JobController@save')->name('save');
        });
        
    });

});


