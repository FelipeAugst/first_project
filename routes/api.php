<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::prefix("user")->group( function(){

Route::get('/index', [UserController::class, 'index']);
Route::post('/store', [UserController::class, 'store']);
Route::put('/{id}/update', [UserController::class, 'update']);
Route::get('/{id}/show', [UserController::class, 'show']);
Route::delete('/{id}/destroy', [UserController::class, 'destroy']);

}
);


Route::prefix("profile")->group( function(){

    Route::get('/index', [ProfileController::class, 'index']);
    Route::post('/store', [ProfileController::class, 'store']);
    Route::put('/{id}/update', [ProfileController::class, 'update']);
    Route::get('/{id}/show', [ProfileController::class, 'show']);
    Route::delete('/{id}/destroy', [ProfileController::class, 'destroy']);
    
    }
    );



Route::prefix("api")->group(function(){

Route::prefix("user");
Route::prefix("profile");


});