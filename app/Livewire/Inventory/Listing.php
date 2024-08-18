<?php

namespace App\Livewire\Inventory;

use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $isGrid = true;
    public $perPage = 30;
    public $direction = 'asc';
    public $search = '';

    public function toggleLayout()
    {
        $this->isGrid = !$this->isGrid;
    }

    public function sort($direction)
    {
        $this->direction = $direction;
    }

    public function render()
    {
        $stocks = Stock::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', $this->direction)
            ->paginate($this->perPage);

        return view('livewire.inventory.listing', compact('stocks'));
    }
}
