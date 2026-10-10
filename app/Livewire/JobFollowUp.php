<?php

namespace App\Livewire;

use App\Livewire\Concerns\DraftsApplicationFollowUp;
use App\Models\JobApplication;
use Livewire\Attributes\Locked;
use Livewire\Component;

class JobFollowUp extends Component
{
    use DraftsApplicationFollowUp;

    #[Locked]
    public int $jobId;

    public function render()
    {
        return view('livewire.job-follow-up', [
            'job' => JobApplication::where('user_id', auth()->id())->findOrFail($this->jobId),
        ]);
    }
}
