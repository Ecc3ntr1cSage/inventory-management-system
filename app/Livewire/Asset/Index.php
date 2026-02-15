<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use App\Models\Application;

class Index extends Component
{
	public $applications;

	public function mount()
	{
		$this->loadApplications();
	}

	public function loadApplications()
	{
		$this->applications = Application::with(['asset','user'])
			->where('status', 0)
			->orderBy('application_date', 'desc')
			->get();
	}

	public function render()
	{
		return view('livewire.asset.index', [
			'applications' => $this->applications,
		]);
	}
}
