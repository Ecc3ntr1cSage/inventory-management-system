<?php

namespace App\Livewire\Asset;

use App\Models\Asset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Listing extends Component
{
    #[Validate('required', message: 'Masukkan perihal aset.')]
    #[Validate('unique:assets,name', message: 'Aset telah wujud.')]
    public $asset_name;
    #[Validate('required', message: 'Masukkan jenama dan model.')]
    public $asset_model;
    #[Validate('required', message: 'Masukkan no pendaftaran.')]
    public $registration_no;

    public $isGrid = true;

    public function toggleLayout()
    {
        $this->isGrid = !$this->isGrid;
    }

    public function addAsset()
    {
        $this->validate([
            'asset_name' => 'required|unique:assets,name',
            'asset_model' => 'required',
            'registration_no' => 'required'
        ]);

        try {
            Asset::create([
                'name' => $this->asset_name,
                'model' => $this->asset_model,
                'registration_no' => $this->registration_no
            ]);

            Session::flash('message', 'New Asset Added');
            $this->redirectIntended(default: route('asset.listing', absolute: false), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error adding asset: ' . $e->getMessage());
            Session::flash('message', 'Add Asset Failed');
            $this->redirectIntended(default: route('asset.listing', absolute: false), navigate: true);
        }
    }

    public function render()
    {
        $assets = Asset::all()->groupBy('is_available');
        $available_assets = $assets->get(true, collect());
        $unavailable_assets = $assets->get(false, collect());

        return view('livewire.asset.listing', compact('available_assets', 'unavailable_assets'));
    }
}
