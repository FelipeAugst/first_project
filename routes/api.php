<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix("user")->group( function(){

Route::get('/index', [UserController::class, 'index']);
Route::post('/store', [UserController::class, 'store']);
Route::put('/{id}/update', [UserController::class, 'update']);
Route::get('/{id}/show', [UserController::class, 'show']);
Route::delete('/{id}/destroy', [UserController::class, 'destroy']);

}
);


Route::prefix("api")->group(function(){

Route::prefix("user");


});