<?php

use App\Livewire\Inventory\Entry;
use App\Livewire\Inventory\Record;
use App\Models\Stock;
use App\Models\Index;
use App\Models\User;
use Livewire\Livewire;

test('create a new stock', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    Livewire::test(Entry::class)
        ->set('stock_name', 'New Stock')
        ->set('stock_group', 'Test Group')
        ->set('stock_location', 'Test Location')
        ->call('addStock')
        ->assertHasNoErrors()
        ->assertSessionHas('message', 'Inventory Created');

    $this->assertDatabaseHas('stocks', [
        'name' => 'New Stock',
        'group' => 'Test Group',
        'location' => 'Test Location',
        'balance' => 0
    ]);
});

test('insert stock logs and updates stock balance', function () {
    $user = User::factory()->staff()->create();
    $this->actingAs($user);

    $stock = Stock::factory()->create(['balance' => 100]);

    Livewire::test(Entry::class)
        ->set('stock_id', $stock->id)
        ->set('reference_no', 'REF001')
        ->set('in_quantity', 50)
        ->set('date', now()->format('Y-m-d'))
        ->call('entry')
        ->assertHasNoErrors()
        ->assertSessionHas('message', 'Entry Created');

    $this->assertDatabaseCount('indexes', 1);

    $this->assertDatabaseHas('indexes', [
        'stock_id' => $stock->id,
        'reference_no' => 'REF001',
        'in_quantity' => 50,
        'balance' => 150,
        'name' => $user->name,
    ]);

    $this->assertEquals(150, $stock->fresh()->balance);
});

test('stock logs prevent issued quantity exceeds stock balance', function () {
    $user = User::factory()->staff()->create();
    $this->actingAs($user);

    $stock = Stock::factory()->create(['balance' => 100]);

    Livewire::test(Entry::class)
        ->set('stock_id', $stock->id)
        ->set('reference_no', 'REF002')
        ->set('out_quantity', 150)
        ->set('date', now()->format('Y-m-d'))
        ->call('entry')
        ->assertSessionHas('message', 'Insufficient Stock Balance');

    $this->assertDatabaseCount('indexes', 0);
    $this->assertEquals(100, $stock->fresh()->balance);
});

test('delete stock', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    // Create a stock for testing
    $stock = Stock::factory()->create();

    // Call the deleteStock method using Livewire
    Livewire::test(Record::class, ['id' => $stock->id]) // Pass the required parameter
        ->call('deleteStock', $stock->id)
        ->assertRedirect(route('inventory.listing'))
        ->assertSessionHas('message', 'Stock Removed');

    // Assert the stock is deleted
    $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
});

test('stock balance updated after index deletion', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);

    $stock = Stock::factory()->create(['balance' => 50]);
    $index = Index::factory()->create([
        'stock_id' => $stock->id,
        'in_quantity' => 20,
        'out_quantity' => null,
        'balance' => 70
    ]);

    Livewire::test(Record::class, ['id' => $stock->id])
        ->call('deleteIndex', $index->id)
        ->assertRedirect(route('inventory.record', [$stock->id]))
        ->assertSessionHas('message', 'Entry Deleted');

    $this->assertDatabaseMissing('indexes', ['id' => $index->id]);
    $this->assertEquals(30, $stock->fresh()->balance);
});

test('export data to pdf', function () {

    $stock = Stock::factory()->create();
    Index::factory()->count(5)->create(['stock_id' => $stock->id]);

    $response = Livewire::test(Record::class, ['id' => $stock->id])
        ->call('exportPDF');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/json');
    $pdfContent = $response->getContent();
    file_put_contents('test.pdf', $pdfContent);
});
