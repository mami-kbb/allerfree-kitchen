<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use PharIo\Manifest\AuthorCollection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/', [RecipeController::class,'index'])->name('recipes.list');
Route::get('/recipe/{recipe_id}', [RecipeController::class, 'show'])->name('recipe.show');
Route::get('/user/{user_id}', [ProfileController::class, 'show'])->name('profile');


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/mypage/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/recipe/{id}/like', [RecipeController::class, 'toggle'])->name('like');
    Route::get('/post', [RecipeController::class, 'create'])->name('recipe.create');
});