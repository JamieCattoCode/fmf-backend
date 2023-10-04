<?php

use App\Services\FurnitureDetailsExtractor;
use App\Services\ProductPageCleaner;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clean', [ProductPageCleaner::class, 'cleanAllProductPages']);

Route::get('/extract', [FurnitureDetailsExtractor::class, 'testExtraction']);