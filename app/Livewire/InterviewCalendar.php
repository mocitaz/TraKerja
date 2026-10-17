<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class InterviewCalendar extends Component
{
    use WithPagination;

    public const STAGES = \App\Support\JobApplicationStatus::SCHEDULED_STAGES;
    #[Locked] public int $currentMonth;
    #[Locked] public int $currentYear;
    #[Locked] public ?string $selectedDate = null;
    #[Locked] public ?int $selectedInterviewId = null;
    public string $viewMode = 'month';
    public string $filterType = 'all';

    protected $listeners = ['job-saved' => 'refreshCalendar', 'status-updated' => 'refreshCalendar', 'interview-updated' => 'refreshCalendar'];

    public function mount(): void
    {
        abort_unless(auth()->check(), 401);
        $this->goToToday();
    }

    private function query(): Builder
    {
        return JobApplication::where('user_id', auth()->id())->whereNotNull('interview_date')
            ->whereIn('recruitment_stage', self::STAGES)->where('is_archived', false)
            ->when(in_array($this->filterType, self::STAGES, true), fn ($query) => $query->where('recruitment_stage', $this->filterType));
    }

    private function monthQuery(): Builder
    {
        $start = Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta');
        return $this->query()->whereBetween('interview_date', [$start, $start->copy()->endOfMonth()->endOfDay()])
            ->orderBy('interview_date')->orderBy('id');
    }

    private function changeMonth(int $offset): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonthsNoOverflow($offset);
        if ($date->year < 1900 || $date->year > 2100) return;
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = null;
        $this->resetPage('agendaPage');
    }

    public function previousMonth(): void { $this->changeMonth(-1); }
    public function nextMonth(): void { $this->changeMonth(1); }

    public function goToToday(): void
    {
        $today = now('Asia/Jakarta');
        $this->currentMonth = $today->month;
        $this->currentYear = $today->year;
        $this->selectedDate = null;
        $this->resetPage('agendaPage');
    }

    public function selectDate(string $date): void
    {
        validator(['date' => $date], ['date' => 'required|date_format:Y-m-d'])->validate();
        $parsed = Carbon::parse($date, 'Asia/Jakarta');
        abort_unless($parsed->year >= 1900 && $parsed->year <= 2100, 422);
        $this->currentMonth = $parsed->month;
        $this->currentYear = $parsed->year;
        $this->selectedDate = $date;
        $this->resetPage('agendaPage');
    }

    public function resetSelectedDate(): void { $this->selectedDate = null; }
    public function updatedFilterType(): void { $this->resetPage('agendaPage'); }
    public function updatedViewMode(): void { $this->validateOnly('viewMode', ['viewMode' => 'required|in:month,list']); }
    public function refreshCalendar(): void { $this->resetPage('agendaPage'); }
    public function toggleViewMode(): void { $this->viewMode = $this->viewMode === 'month' ? 'list' : 'month'; }

    public function viewInterviewDetails(int $jobId): void
    {
        $this->selectedInterviewId = $this->query()->findOrFail($jobId)->id;
    }

    public function closeModal(): void { $this->selectedInterviewId = null; }

    public function editInterview(int $jobId)
    {
        $job = $this->query()->findOrFail($jobId);
        return redirect()->route('tracker', ['edit' => $job->id]);
    }

    public function getUpcomingInterviewsProperty()
    {
        return $this->monthQuery()->where('interview_date', '>=', now('Asia/Jakarta'))->limit(5)->get();
    }

    public function getAllInterviewsListProperty()
    {
        return $this->monthQuery()->get();
    }

    public function render()
    {
        $jobs = $this->monthQuery()->get(['id', 'company_name', 'position', 'recruitment_stage', 'interview_date', 'interview_type']);
        $grouped = $jobs->groupBy(fn ($job) => $job->interview_date->timezone('Asia/Jakarta')->format('Y-m-d'));
        $month = Carbon::create($this->currentYear, $this->currentMonth, 1, 0, 0, 0, 'Asia/Jakarta');
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $days = [];
        for ($date = $month->copy()->startOfWeek(Carbon::MONDAY); $date->lte($end); $date->addDay()) {
            $schedule = $grouped->get($date->format('Y-m-d'), collect());
            $days[] = ['date' => $date->copy(), 'isCurrentMonth' => $date->month === $this->currentMonth,
                'isToday' => $date->isToday(), 'count' => $schedule->count(), 'interviews' => $schedule->take(2)];
        }
        $upcoming = $jobs->filter(fn ($job) => $job->interview_date->gte(now('Asia/Jakarta')));
        return view('livewire.interview-calendar', [
            'monthName' => $month->locale(app()->getLocale())->translatedFormat('F Y'), 'monthTotal' => $jobs->count(), 'upcomingTotal' => $upcoming->count(),
            'scheduledDays' => $grouped->count(), 'calendarDays' => $days,
            'daySchedule' => $this->selectedDate ? $grouped->get($this->selectedDate, collect()) : $upcoming->take(5),
            'agenda' => $this->viewMode === 'list' ? $this->monthQuery()->paginate(20, ['*'], 'agendaPage') : null,
            'selectedInterview' => $this->selectedInterviewId ? $this->query()->find($this->selectedInterviewId) : null,
        ]);
    }
}
