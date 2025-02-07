<?php

use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return response()->json([
//        'message' => 'Welcome to Short URL API'
//    ]);
//});

Route::get('/', [ShortUrlController::class, 'create'])->name('short-url.create');
Route::get('/{shortUrl}', [ShortUrlController::class, 'redirect'])->name('short-url.show');
Route::post('/urls', [ShortUrlController::class, 'store'])->name('short-url.store');
Route::get('delete/{deleteCode}', [ShortUrlController::class, 'showDelete'])->name('short-url.show-delete');
Route::delete('delete/{deleteCode}', [ShortUrlController::class, 'delete'])->name('short-url.delete');

