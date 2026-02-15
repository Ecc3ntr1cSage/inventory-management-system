<?php

namespace App\Livewire\Asset;

use App\Models\Asset;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Submission extends Component
{
    public $assets;

    #[Validate('required', message: 'Pilih aset.')]
    public $asset_id;

    public function approve($id)
    {
        $this->validate();

        $application = Application::findOrFail($id);

        DB::transaction(function () use ($application) {
            $application->update([
                'asset_id' => $this->asset_id,
                'date_issued' => now(),
                'handler' => Auth::user()->name,
                'status' => 1,
            ]);
            Asset::where('id', $this->asset_id)->update([
                'is_available' => false,
            ]);
        });

        $this->flashMessageAndRedirect('Request Approved');
    }

    public function receive($id)
    {
        $application = Application::findOrFail($id);

        DB::transaction(function () use ($application) {
            Asset::where('id', $application->asset_id)->update(['is_available' => true]);
            $application->update([
                'receiver' => Auth::user()->name,
                'status' => 3,
                'date_returned' => now(),
            ]);
        });

        $this->flashMessageAndRedirect('Asset Returned');
    }

    public function revert($id)
    {
        $application = Application::findOrFail($id);

        DB::transaction(function () use ($application) {
            Asset::where('id', $application->asset_id)->update(['is_available' => true]);
            $application->update([
                'asset_id' => null,
                'date_issued' => null,
                'handler' => null,
                'status' => 0,
            ]);
        });

        $this->flashMessageAndRedirect('Application Reverted');
    }

    private function flashMessageAndRedirect($message)
    {
        Session::flash('message', $message);
        $this->redirectIntended(default: route('asset.submission', absolute: false), navigate: true);
    }

    public function render()
    {
        $applications = Application::whereIn('status', [0, 1])->get()->groupBy('status');
        $pending_applications = $applications->get(0, collect());
        $approved_applications = $applications->get(1, collect());
        $this->assets = Asset::where('is_available', true)->pluck('name', 'id')->toJson();

        return view('livewire.asset.submission', compact('pending_applications', 'approved_applications'));
    }
}

