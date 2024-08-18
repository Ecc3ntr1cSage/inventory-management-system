<?php

use App\Livewire\Asset\Listing as AssetListing;
use App\Livewire\Asset\Record as AssetRecord;
use App\Livewire\Asset\Request;
use App\Livewire\Asset\Submission;
use App\Livewire\Inventory\Entry;
use App\Livewire\Inventory\Listing as InventoryListing;
use App\Livewire\Inventory\Record as InventoryRecord;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::to('/login');
});

Route::middleware(['auth', 'verified', 'check.role'])->group(function () {
    // Routes that require authentication and email verification
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('inventory/entry', Entry::class)->name('inventory.entry');
    Route::get('inventory/listing', InventoryListing::class)->name('inventory.listing');
    Route::get('inventory/records/{id}', InventoryRecord::class)->name('inventory.record');
    Route::get('asset/submission', Submission::class)->name('asset.submission');
    Route::get('asset/listing', AssetListing::class)->name('asset.listing');
    Route::get('asset/records/{id}', AssetRecord::class)->name('asset.record');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('asset/request', Request::class)->name('asset.request');
    Route::view('profile', 'profile')->name('profile');
});


require __DIR__.'/auth.php';

