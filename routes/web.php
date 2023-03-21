<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TestPreview;

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

Route::post('previews', [UserController::class, 'preview']);

Route::post('import', function () {
    $fileName = time().'_'.request()->file->getClientOriginalName();
    request()->file('file')->storeAs('reports', $fileName, 'public');


    // $test = Excel::import(new UsersImport, request()->file('file'));
    $test = Excel::toArray(new UsersImport, request()->file('file'));

    $path = request()->file('file')->getRealPath();
    $data = Excel::toArray('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];
    $header = $data[0];
    var_dump(count($header));

    for($i = 1; $i < count($data); $i++){
        $content[$i]['name'] = $data[$i][0];
        $content[$i]['email'] = $data[$i][1];
        $content[$i]['password'] = $data[$i][2];
    }

    dd($content);

    die();
    return redirect()->back()->with('success','Data Imported Successfully');
});

// Route::get('export-csv', function () {
//     return Excel::download(new UsersExport, 'users.csv');
// });

Route::get('/pdf', [UserController::class, 'exportPDF']);

Route::post('import.preview', [TestPreview::class, 'preview'])->name('import.preview');
