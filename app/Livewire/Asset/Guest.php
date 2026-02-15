<?php

namespace App\Livewire\Asset;

use App\Models\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\GuestApplicationSubmitted;
use Livewire\Component;

class Guest extends Component
{
    public $guest_name;
    public $guest_email;
    public $website; // honeypot
    public $description;
    public $reason;
    public $position;
    public $department;
    public $location;

    public function rules()
    {
        return [
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'description' => 'required',
            'reason' => 'required',
            'position' => 'required',
            'department' => 'required',
            'location' => 'required',
        ];
    }

    public function application()
    {
        // simple honeypot spam check
        if (! empty($this->website)) {
            return; // silently ignore bots
        }

        $this->validate();

        try {
            $application = Application::create([
                'user_id' => null,
                'guest_name' => $this->guest_name,
                'guest_email' => $this->guest_email,
                'description' => $this->description,
                'reason' => $this->reason,
                'position' => $this->position,
                'department' => $this->department,
                'location' => $this->location,
                'application_date' => now(),
            ]);

            // notify admins
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new GuestApplicationSubmitted($application));
            }

            Session::flash('message', 'Application submitted.');
            return redirect()->route('guest.request');
        } catch (\Exception $e) {
            Log::error('Guest booking request error: ' . $e->getMessage());
            Session::flash('message', 'There was an error submitting your application.');
            return redirect()->route('guest.request');
        }
    }

    public function render()
    {
        return view('livewire.asset.guest');
    }
}

