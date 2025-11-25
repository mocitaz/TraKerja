<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class InterviewCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $selectedDate;
    public $interviews = [];
    public $calendarDays = [];
    
    // View mode: 'month' or 'list'
    public $viewMode = 'month';
    
    // Filter by recruitment stage (HR or User Interview)
    public $filterType = 'all';
    
    // Modal state
    public $showModal = false;
    public $selectedInterview = null;
    
    protected $listeners = [
        'job-saved' => 'refreshCalendar',
        'status-updated' => 'refreshCalendar',
        'interview-updated' => 'refreshCalendar',
    ];
    
    public function mount()
    {
        $this->currentMonth = Carbon::now('Asia/Jakarta')->month;
        $this->currentYear = Carbon::now('Asia/Jakarta')->year;
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function loadInterviews()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta')->startOfDay();
        $endOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth()->endOfDay();
        
        $query = JobApplication::where('user_id', Auth::id())
            ->whereNotNull('interview_date')
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('is_archived', false)
            ->whereBetween('interview_date', [$startOfMonth, $endOfMonth])
            ->orderBy('interview_date', 'asc');
            
        if ($this->filterType !== 'all') {
            $query->where('recruitment_stage', $this->filterType);
        }
        
        $this->interviews = $query->get()->groupBy(function ($interview) {
            return $interview->interview_date->format('Y-m-d');
        })->toArray();
    }
    
    public function generateCalendar()
    {
        $firstDayOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta');
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
        
        // Start from Sunday of the week containing the 1st
        $startDate = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        // End on Saturday of the week containing the last day
        $endDate = $lastDayOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        $this->calendarDays = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            $this->calendarDays[] = [
                'date' => $currentDate->copy(),
                'isCurrentMonth' => $currentDate->month == $this->currentMonth,
                'isToday' => $currentDate->isToday(),
                'interviews' => $this->interviews[$dateKey] ?? [],
            ];
            $currentDate->addDay();
        }
    }
    
    public function previousMonth()
    {
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function goToToday()
    {
        $this->currentMonth = Carbon::now('Asia/Jakarta')->month;
        $this->currentYear = Carbon::now('Asia/Jakarta')->year;
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }
    
    public function updatedFilterType()
    {
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function refreshCalendar()
    {
        $this->loadInterviews();
        $this->generateCalendar();
    }
    
    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'month' ? 'list' : 'month';
    }
    
    public function viewInterviewDetails($jobId)
    {
        $this->selectedInterview = JobApplication::where('id', $jobId)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($this->selectedInterview) {
            $this->showModal = true;
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedInterview = null;
    }
    
    public function editInterview($jobId)
    {
        $this->closeModal();
        $this->dispatch('edit-job', jobId: $jobId);
    }
    
    public function getUpcomingInterviewsProperty()
    {
        return JobApplication::where('user_id', Auth::id())
            ->whereNotNull('interview_date')
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('is_archived', false)
            ->where('interview_date', '>=', Carbon::now('Asia/Jakarta'))
            ->orderBy('interview_date', 'asc')
            ->limit(5)
            ->get();
    }
    
    public function getAllInterviewsListProperty()
    {
        $query = JobApplication::where('user_id', Auth::id())
            ->whereNotNull('interview_date')
            ->whereIn('recruitment_stage', ['HR - Interview', 'User - Interview'])
            ->where('is_archived', false)
            ->orderBy('interview_date', 'desc');
            
        if ($this->filterType !== 'all') {
            $query->where('recruitment_stage', $this->filterType);
        }
        
        return $query->get();
    }
    
    public function render()
    {
        return view('livewire.interview-calendar', [
            'monthName' => Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta')->format('F Y'),
        ]);
    }
}
