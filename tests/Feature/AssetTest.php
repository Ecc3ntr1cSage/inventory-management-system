<?php

use App\Livewire\Asset\Listing;
use App\Livewire\Asset\Record;
use App\Livewire\Asset\Request;
use App\Livewire\Asset\Submission;
use App\Models\Application;
use App\Models\Asset;
use App\Models\User;
use Livewire\Livewire;

test('approve an application', function () {
    $user = User::factory()->staff()->create();
    $this->actingAs($user);

    $application = Application::factory()->create(['status' => 0]);
    $asset = Asset::factory()->create(['is_available' => true]);

    Livewire::test(Submission::class)
        ->set('asset_id', $asset->id)
        ->call('approve', $application->id)
        ->assertRedirect(route('asset.submission'))
        ->assertSessionHas('message', 'Request Approved');

    $this->assertDatabaseHas('applications', [
        'id' => $application->id,
        'asset_id' => $asset->id,
        'status' => 1,
        'handler' => $user->name,
    ]);

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'is_available' => false,
    ]);
});

test('revert an application', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    $asset = Asset::factory()->create(['is_available' => false]);
    $application = Application::factory()->create([
        'status' => 1,
        'asset_id' => $asset->id,
        'date_issued' => now(),
        'handler' => $user->name,
    ]);

    Livewire::test(Submission::class)
        ->call('revert', $application->id)
        ->assertRedirect(route('asset.submission'))
        ->assertSessionHas('message', 'Application Reverted');

    $this->assertDatabaseHas('applications', [
        'id' => $application->id,
        'asset_id' => null,
        'date_issued' => null,
        'handler' => null,
        'status' => 0,
    ]);

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'is_available' => true,
    ]);
});

test('receive an asset', function () {
    $user = User::factory()->staff()->create();
    $this->actingAs($user);

    $asset = Asset::factory()->create(['is_available' => false]);
    $application = Application::factory()->create([
        'status' => 1,
        'asset_id' => $asset->id,
    ]);

    Livewire::test(Submission::class)
        ->call('receive', $application->id)
        ->assertRedirect(route('asset.submission'))
        ->assertSessionHas('message', 'Asset Returned');

    $this->assertDatabaseHas('applications', [
        'id' => $application->id,
        'status' => 3,
        'receiver' => $user->name,
    ]);

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'is_available' => true,
    ]);
});

test('create a new application', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Request::class)
        ->set('description', 'Test Description')
        ->set('reason', 'Test Reason')
        ->set('position', 'Test Position')
        ->set('department', 'Test Department')
        ->set('location', 'Test Location')
        ->call('application')
        ->assertHasNoErrors()
        ->assertSessionHas('message', 'Application Success');

    $this->assertDatabaseHas('applications', [
        'user_id' => $user->id,
        'description' => 'Test Description',
        'reason' => 'Test Reason',
        'position' => 'Test Position',
        'department' => 'Test Department',
        'location' => 'Test Location',
    ]);
});

test('add a new asset', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    Livewire::test(Listing::class)
        ->set('asset_name', 'New Asset')
        ->set('asset_model', 'Model X')
        ->set('registration_no', 'ABC-123')
        ->call('addAsset')
        ->assertHasNoErrors()
        ->assertSessionHas('message', 'New Asset Added')
        ->assertRedirect(route('asset.listing'));

    $this->assertDatabaseHas('assets', [
        'name' => 'New Asset',
        'model' => 'Model X',
        'registration_no' => 'ABC-123'
    ]);
});

test('delete asset', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    // Create a stock for testing
    $asset = Asset::factory()->create();

    // Call the deleteStock method using Livewire
    Livewire::test(Record::class, ['id' => $asset->id]) // Pass the required parameter
        ->call('deleteAsset', $asset->id)
        ->assertRedirect(route('asset.listing'))
        ->assertSessionHas('message', 'Asset Removed');

    // Assert the stock is deleted
    $this->assertDatabaseMissing('stocks', ['id' => $asset->id]);
});
