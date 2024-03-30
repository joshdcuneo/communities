<?php

use App\Http\Controllers\WelcomeController;
use App\Livewire\CreateCommunity;
use App\Livewire\Dashboard;
use App\Livewire\ShowCommunity;
use App\Models\Community;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::name('community.')
        ->prefix('/community')
        ->group(function () {
            Route::get('/create', CreateCommunity::class)->name('create')->can('create', Community::class);
            Route::get('/{community}', ShowCommunity::class)->name('show')->can('view', 'community');
        });
});
