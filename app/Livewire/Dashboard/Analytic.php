<?php

namespace App\Livewire\Dashboard;

use App\Models\Application;
use App\Models\Asset;
use App\Models\Index;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Analytic extends Component
{
    public $days = -1;

    public function sort($days)
    {
        $this->days = $days;
    }

    private function date()
    {
        return $this->days == -1
        ? Carbon::create(1970, 1, 1)->startOfDay()
        : Carbon::now()->subDays($this->days)->startOfDay();
    }

    public function render()
    {
        $dateScope = $this->date();

        $stocks = Stock::select('name', 'balance')->get();
        $assets = Asset::withCount('applications')->get();
        $stock_received = Index::where('date', '>=', $dateScope)->sum('in_quantity');
        $stock_issued = Index::where('date', '>=', $dateScope)->sum('out_quantity');
        $application_count = Application::where('created_at', '>=', $dateScope)->count();

        return view('livewire.dashboard.analytic', compact('stocks', 'assets', 'stock_received', 'stock_issued', 'application_count'));
    }
}
