<?php

namespace App\Livewire\Asset;

use App\Models\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Request extends Component
{
    use WithPagination;

    public $name;
    #[Validate('required', message: 'Masukkan butir permohonan.')]
    public $description;
    #[Validate('required', message: 'Masukkan tujuan.')]
    public $reason;
    #[Validate('required', message: 'Masukkan jawatan.')]
    public $position;
    #[Validate('required', message: 'Masukkan bahagian.')]
    public $department;
    #[Validate('required', message: 'Masukkan tempat digunakan.')]
    public $location;
    public $requestPending = false;
    public $requestApproved = false;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->requestPending = Application::where('user_id', Auth::id())->where('status', 0)->exists();
        $this->requestApproved = Application::where('user_id', Auth::id())->where('status', 1)->exists();
    }

    public function application()
    {
        $this->validate([
            'description' => 'required',
            'reason' => 'required',
            'position' => 'required',
            'department' => 'required',
            'location' => 'required',
        ]);

        try {
            Application::create([
                'user_id' => Auth::id(),
                'description' => $this->description,
                'reason' => $this->reason,
                'position' => $this->position,
                'department' => $this->department,
                'location' => $this->location,
                'application_date' => now(),
            ]);

            Session::flash('message', 'Application Success');
            $this->redirectIntended(default: route('asset.request', absolute: false), navigate: true);
        } catch (\Exception $e) {
            Log::error('Booking request error: ' . $e->getMessage());
            $this->redirectIntended(default: route('asset.request', absolute: false), navigate: true);
        }
    }

    public function render()
    {
        $applications = [];
        if ($this->requestPending || $this->requestApproved) {
            $applications = Application::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(8);
        }

        return view('livewire.asset.request', compact('applications'));
    }
}
