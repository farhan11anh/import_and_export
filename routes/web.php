<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

use App\Http\Controllers\UserController;

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

Route::resource('user', 'App\Http\Controllers\UserController');

Route::get('/', function () {
    return redirect('/user');
});

Route::post('import', function () {
    $fileName = time().'_'.request()->file->getClientOriginalName();
    request()->file('file')->storeAs('reports', $fileName, 'public');

    Excel::import(new UsersImport, request()->file('file'));
    return redirect()->back()->with('success','Data Imported Successfully');
});

// Route::get('export-csv', function () {
//     return Excel::download(new UsersExport, 'users.csv');
// });

Route::get('/pdf', [UserController::class, 'exportPDF']);
