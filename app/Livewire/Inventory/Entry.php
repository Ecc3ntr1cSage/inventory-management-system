<?php

namespace App\Livewire\Inventory;

use App\Models\Index;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Entry extends Component
{
    public $addStockModal = false;
    public $inModal = false;
    public $outModal = false;

    public $stocks;

    #[Validate('required', message: 'Masukkan perihal stok.')]
    #[Validate('unique:stocks,name', message: 'Stok telah wujud.')]
    public $stock_name;
    public $stock_group;
    public $stock_location;

    #[Validate('required', message: 'Pilih stok.')]
    public $stock_id;
    #[Validate('required', message: 'Masukkan tarikh.')]
    public $date;
    #[Validate('required', message: 'Masukkan no rujukan.')]
    public $reference_no;
    #[Validate('required_without:out_quantity', message: 'Masukkan kuantiti terima.')]
    #[Validate('integer', message: 'Kuantiti terima mesti nombor.')]
    public $in_quantity;
    public $unit_price;
    #[Validate('required_without:in_quantity', message: 'Masukkan kuantiti keluar')]
    #[Validate('integer', message: 'Kuantiti keluar mesti nombor.')]
    public $out_quantity;

    public function addStock()
    {
        $this->validate([
            'stock_name' => 'required|unique:stocks,name',
        ]);

        try {
            Stock::create([
                'name' => $this->stock_name,
                'group' => $this->stock_group,
                'location' => $this->stock_location,
                'balance' => 0
            ]);

            Session::flash('message', 'Inventory Created');
            $this->redirectIntended(default: route('inventory.entry', absolute: false), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error adding stock: ' . $e->getMessage());
            Session::flash('message', 'Add Stock Failed');
            $this->redirectIntended(default: route('inventory.entry', absolute: false), navigate: true);
        }
    }

    public function entry()
    {
        $this->validate([
            'stock_id' => 'required',
            'reference_no' => 'required',
            'in_quantity' => 'nullable|numeric|required_without:out_quantity',
            'out_quantity' => 'nullable|numeric|required_without:in_quantity',
            'date' => 'required|date'
        ]);

        try {
            $stock = Stock::findOrFail($this->stock_id);
            $balance = $stock->balance;

            if ($this->out_quantity !== null && $balance < $this->out_quantity) {
                Session::flash('message', 'Insufficient Stock Balance');
                throw new \Exception('Insufficient stock balance: ' . $stock->name);
            }

            if ($this->in_quantity !== null) {
                $balance += $this->in_quantity;
            } elseif ($this->out_quantity !== null) {
                $balance -= $this->out_quantity;
            }

            $stock->update([
                'balance' => $balance,
            ]);

            Index::create([
                'stock_id' => $this->stock_id,
                'date' => $this->date,
                'reference_no' => $this->reference_no,
                'in_quantity' => $this->in_quantity,
                'out_quantity' => $this->out_quantity,
                'balance' => $balance,
                'name' => Auth::user()->name,
            ]);

            Session::flash('message', 'Entry Created');
            $this->redirectIntended(default: route('inventory.record', [$stock->id], absolute: false), navigate: true);
        } catch (\Exception $e) {
            Log::error('Inventory entry error: ' . $e->getMessage());
            $this->redirectIntended(default: route('inventory.entry', absolute: false), navigate: true);
        }
    }

    public function render()
    {
        $stocks = Stock::get();
        $this->stocks = $stocks->pluck('name', 'id')->toJson();

        return view('livewire.inventory.entry', compact('stocks'));
    }
}

