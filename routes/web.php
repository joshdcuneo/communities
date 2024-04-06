<?php

use App\Http\Controllers\WelcomeController;
use App\Livewire\CommunityCreatePage;
use App\Livewire\CommunityListPage;
use App\Livewire\CommunityShowPage;
use App\Livewire\DashboardPage;
use App\Models\Community;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::name('community.')
        ->prefix('/community')
        ->group(function () {
            Route::get('/', CommunityListPage::class)->name('index');
            Route::get('/create', CommunityCreatePage::class)->name('create')->can('create', Community::class);
            Route::get('/{community}', CommunityShowPage::class)->name('show')->can('view', 'community');
        });
});
