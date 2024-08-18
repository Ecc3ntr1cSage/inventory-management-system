<?php

namespace App\Livewire\Inventory;

use App\Models\Stock;
use App\Models\Index;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Record extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $direction = 'desc';
    public $search = '';
    public $stock;

    public function mount($id)
    {
        $this->stock = Stock::findOrFail($id);
    }

    public function sort($direction)
    {
        $this->direction = $direction;
    }

    public function deleteStock($id)
    {
        Stock::destroy($id);

        Session::flash('message', 'Stock Removed');
        $this->redirectIntended(default: route('inventory.listing', absolute: false), navigate: true);
    }

    private function updateDataAfterDeletion($id, $value, $balance)
    {
        $this->stock->update([
            'balance' => $balance,
        ]);

        $indexes = Index::where('stock_id', $this->stock->id)
            ->where('id', '>', $id)
            ->get();

        foreach ($indexes as $index) {
            $index->update([
                'balance' => $index->balance + $value
            ]);
        }
    }

    public function deleteIndex($index_id)
    {
        $index = Index::findOrFail($index_id);
        $balance = $index->out_quantity !== null ? $this->stock->balance + $index->out_quantity : $this->stock->balance - $index->in_quantity;
        $value = $index->out_quantity !== null ? $index->out_quantity : -$index->in_quantity;
        $id = $index->id;

        $this->updateDataAfterDeletion($id, $value, $balance);
        $index->delete();

        Session::flash('message', 'Entry Deleted');
        $this->redirectIntended(default: route('inventory.record', [$this->stock->id], absolute: false), navigate: true);
    }

    public function exportPDF()
    {
        $indexes = Index::where('stock_id', $this->stock->id)->get();
        $pdf = Pdf::loadView('livewire.inventory.export-pdf', [
            'indexes' => $indexes,
            'stock' => $this->stock,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $this->stock->name . '.pdf');
    }

    public function render()
    {
        $indexes = Index::where('stock_id', $this->stock->id) // Find entries based on stock id
            ->where('reference_no', 'like', '%' . $this->search . '%') // Filter by 'no_rujukan'
            ->orderBy('date', $this->direction)
            ->orderBy('id', $this->direction)
            ->paginate($this->perPage);

        return view('livewire.inventory.record', compact('indexes'));
    }
}
