<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
    public string $focusRange = 'week';
    #[Locked] public bool $editingSchedule = false;
    #[Locked] public string $scheduleVersion = '';
    #[Locked] public string $feedback = '';
    public string $scheduleDate = '';
    public string $scheduleTime = '';
    public string $scheduleType = '';
    public string $schedulePlatform = '';
    public string $scheduleLocation = '';
    public string $scheduleNotes = '';

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
    public function updatedFilterType(): void { $this->resetPage('agendaPage'); $this->resetPage('focusPage'); $this->closeModal(); }
    public function updatedViewMode(): void { $this->validateOnly('viewMode', ['viewMode' => 'required|in:month,list']); }
    public function refreshCalendar(): void { $this->resetPage('agendaPage'); }
    public function toggleViewMode(): void { $this->viewMode = $this->viewMode === 'month' ? 'list' : 'month'; }

    public function viewInterviewDetails(int $jobId): void
    {
        $this->resetValidation();
        $this->editingSchedule = false;
        $this->selectedInterviewId = $this->query()->findOrFail($jobId)->id;
    }

    public function closeModal(): void
    {
        $this->selectedInterviewId = null;
        $this->editingSchedule = false;
        $this->scheduleVersion = '';
        $this->resetValidation();
    }

    private function scheduleFingerprint(JobApplication $job): string
    {
        return hash('sha256', json_encode($job->only(['interview_date', 'interview_type', 'interview_platform', 'interview_location', 'interview_notes', 'recruitment_stage', 'is_archived'])));
    }

    public function editInterview(int $jobId): void
    {
        $job = $this->query()->findOrFail($jobId);
        $this->resetValidation();
        $this->selectedInterviewId = $job->id;
        $this->editingSchedule = true;
        $date = $job->interview_date->timezone('Asia/Jakarta');
        $this->scheduleDate = $date->format('Y-m-d');
        $this->scheduleTime = $date->format('H:i');
        $this->scheduleType = $job->interview_type ?? '';
        $this->schedulePlatform = $job->interview_platform ?? '';
        $this->scheduleLocation = $job->interview_location ?? '';
        $this->scheduleNotes = $job->interview_notes ?? '';
        $this->scheduleVersion = $this->scheduleFingerprint($job);
    }

    public function cancelScheduleEdit(): void
    {
        $this->editingSchedule = false;
        $this->resetValidation();
    }

    public function setFocusRange(string $range): void
    {
        abort_unless(in_array($range, ['today', 'week'], true), 422);
        $this->focusRange = $range;
        $this->resetPage('focusPage');
    }

    public function dismissFeedback(): void { $this->feedback = ''; }

    public function saveSchedule(): void
    {
        abort_unless($this->editingSchedule && $this->selectedInterviewId, 422);
        $this->validate([
            'scheduleDate' => 'required|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:2100-12-31',
            'scheduleTime' => 'required|date_format:H:i',
            'scheduleType' => 'nullable|in:Online,Onsite,Phone,Video,In-person,Panel',
            'schedulePlatform' => ['nullable', \Illuminate\Validation\Rule::in(\App\Support\SelectionSchedule::PLATFORMS)],
            'scheduleLocation' => 'nullable|string|max:255',
            'scheduleNotes' => 'nullable|string|max:10000',
        ]);
        $dateChanged = false;
        $job = DB::transaction(function () use (&$dateChanged) {
            $job = $this->query()->lockForUpdate()->findOrFail($this->selectedInterviewId);
            if (!hash_equals($this->scheduleVersion, $this->scheduleFingerprint($job))) {
                $this->addError('scheduleDate', __('This schedule changed elsewhere. Close and reopen it before saving.'));
                return null;
            }
            $this->validate(['scheduleType' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(\App\Support\SelectionSchedule::methods($job->recruitment_stage, $job->interview_type)))]]);
            $date = Carbon::createFromFormat('Y-m-d H:i', $this->scheduleDate.' '.$this->scheduleTime, 'Asia/Jakarta')->second(0);
            $dateChanged = $job->interview_date->format('Y-m-d H:i') !== $date->format('Y-m-d H:i');
            $data = [
                'interview_date' => $date,
                'interview_type' => $this->scheduleType ?: null,
                'interview_location' => trim($this->scheduleLocation) ?: null,
                'interview_notes' => trim($this->scheduleNotes) ?: null,
            ];
            if (\App\Support\SelectionSchedule::platformAvailable()) $data['interview_platform'] = \App\Support\SelectionSchedule::online($this->scheduleType) ? ($this->schedulePlatform ?: null) : null;
            $job->update($data);
            return $job;
        });
        if (!$job) return;
        $this->editingSchedule = false;
        $this->scheduleVersion = '';
        $this->feedback = __('Schedule updated. Your calendar is up to date.');
        $this->dispatch('interview-updated');
        if ($dateChanged && $job->application_status !== 'Rejected' && $job->user?->wantsEmailNotification('interview')) {
            try {
                Mail::to($job->user)->send(new \App\Mail\InterviewScheduledMail($job));
            } catch (\Throwable $e) {
                Log::warning('Calendar reschedule notification failed', ['job_id' => $job->id]);
                $this->feedback = __('Schedule saved, but the confirmation email could not be sent.');
            }
        }
    }

    public function getUpcomingInterviewsProperty()
    {
        return $this->query()->where('interview_date', '>=', now('Asia/Jakarta'))->orderBy('interview_date')->orderBy('id')->limit(5)->get();
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
        $today = now('Asia/Jakarta')->startOfDay();
        $focusEnd = $this->focusRange === 'today' ? $today->copy()->endOfDay() : $today->copy()->addDays(6)->endOfDay();
        $focusQuery = $this->query()->whereBetween('interview_date', [$today, $focusEnd])->orderBy('interview_date')->orderBy('id');
        $upcoming = $jobs->filter(fn ($job) => $job->interview_date->gte(now('Asia/Jakarta')));
        return view('livewire.interview-calendar', [
            'monthName' => $month->locale(app()->getLocale())->translatedFormat('F Y'), 'monthTotal' => $jobs->count(), 'upcomingTotal' => $upcoming->count(),
            'scheduledDays' => $grouped->count(), 'calendarDays' => $days,
            'daySchedule' => $this->selectedDate ? $grouped->get($this->selectedDate, collect()) : $this->upcomingInterviews,
            'focusAgenda' => $focusQuery->paginate(5, ['*'], 'focusPage'),
            'agenda' => $this->viewMode === 'list' ? $this->monthQuery()->paginate(20, ['*'], 'agendaPage') : null,
            'selectedInterview' => $this->selectedInterviewId ? $this->query()->find($this->selectedInterviewId) : null,
        ]);
    }
}
