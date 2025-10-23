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
    // Filter by interview type
    public $filterType = 'all';
    protected $listeners = [
        'job-saved' => 'refreshCalendar',
        'status-updated' => 'refreshCalendar',
    ];
    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadInterviews();
        $this->generateCalendar();
    }
    public function loadInterviews()
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfDay();
        $query = JobApplication::where('user_id', Auth::id())
            ->whereNotNull('interview_date')
            ->whereBetween('interview_date', [$startOfMonth, $endOfMonth])
            ->orderBy('interview_date', 'asc');
            
        if ($this->filterType !== 'all') {
            $query->where('interview_type', $this->filterType);
        }
        $this->interviews = $query->get()->groupBy(function ($interview) {
            return $interview->interview_date->format('Y-m-d');
        })->toArray();
    public function generateCalendar()
        $firstDayOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
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
    public function previousMonth()
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
    public function nextMonth()
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
            $this->currentMonth++;
    public function goToToday()
    public function selectDate($date)
        $this->selectedDate = $date;
    public function updatedFilterType()
    public function refreshCalendar()
    public function toggleViewMode()
        $this->viewMode = $this->viewMode === 'month' ? 'list' : 'month';
    public function editInterview($jobId)
        $this->dispatch('edit-job', jobId: $jobId);
    public function getUpcomingInterviewsProperty()
        return JobApplication::where('user_id', Auth::id())
            ->where('interview_date', '>=', now())
            ->orderBy('interview_date', 'asc')
            ->limit(5)
            ->get();
    public function render()
        return view('livewire.interview-calendar', [
            'monthName' => Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y'),
            'upcomingInterviews' => $this->upcomingInterviews,
        ]);
}
