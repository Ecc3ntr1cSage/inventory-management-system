<?php

use App\Livewire\Asset\Listing as AssetListing;
use App\Livewire\Asset\Record as AssetRecord;
use App\Livewire\Asset\Request as AssetRequest;
use App\Livewire\Asset\Guest as AssetGuest;
use App\Livewire\Asset\Index as AssetIndex;
use App\Livewire\Asset\Submission as AssetSubmission;
use App\Livewire\Inventory\Entry as InventoryEntry;
use App\Livewire\Inventory\Listing as InventoryListing;
use App\Livewire\Inventory\Record as InventoryRecord;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified', 'check.role'])->group(function () {
    // Routes that require authentication and email verification
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('inventory/entry', InventoryEntry::class)->name('inventory.entry');
    Route::get('inventory/listing', InventoryListing::class)->name('inventory.listing');
    Route::get('inventory/records/{id}', InventoryRecord::class)->name('inventory.record');
    Route::get('asset/submission', AssetSubmission::class)->name('asset.submission');
    Route::get('asset/listing', AssetListing::class)->name('asset.listing');
    Route::get('asset/records/{id}', AssetRecord::class)->name('asset.record');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('asset/request', AssetRequest::class)->name('asset.request');
    Route::view('profile', 'profile')->name('profile');
});

Route::get('guest/request', AssetGuest::class)->name('guest.request');
Route::get('guest/index', AssetIndex::class)->name('guest.index');

require __DIR__.'/auth.php';

