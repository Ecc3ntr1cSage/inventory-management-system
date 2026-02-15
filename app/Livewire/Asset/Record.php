<?php

namespace App\Livewire\Asset;

use App\Models\Application;
use App\Models\Asset;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Record extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $asset;
    public $selectedApplications = [];
    public $selectAll = false;

    public function mount($id)
    {
        $this->asset = Asset::findOrFail($id);
    }

    public function __invoke(...$params)
    {
        if (method_exists($this, 'mount')) {
            $this->mount(...$params);
        }

        return $this->render();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedApplications = Application::where('asset_id', $this->asset->id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedApplications = [];
        }
    }

    public function updatedSelectedApplications()
    {
        $this->selectAll = count($this->selectedApplications) === Application::where('asset_id', $this->asset->id)
                ->where('status', 3)
                ->count();
    }

    public function exportPDF()
    {
        $applications = Application::whereIn('id', $this->selectedApplications)->get();
        $pdf = Pdf::loadView('livewire.asset.export-pdf', [
            'applications' => $applications,
            'asset' => $this->asset
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf){
            echo $pdf->stream();
        }, $this->asset->name . '.pdf');
    }

    public function deleteAsset($id)
    {
        Asset::destroy($id);

        Session::flash('message', 'Asset Removed');
        $this->redirectIntended(default: route('asset.listing', absolute: false), navigate: true);

    }

    public function render()
    {
        $applications = Application::where('asset_id', $this->asset->id)->where('status', 3)->orderBy('id','desc')->paginate(10 );
        return view('livewire.asset.record', compact('applications'));
    }
}

