<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;

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


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/mypage/edit', [ProfileController::class, 'edit'])->name('profile_edit');
    Route::post('/recipe/{id}/like', [RecipeController::class, 'toggle'])->name('like');
});