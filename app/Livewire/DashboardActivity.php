<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardActivity extends Component
{
    use WithPagination;

    #[Locked]
    public int $months = 12;

    #[Locked]
    public ?string $selectedDate = null;

    public bool $showDay = false;

    #[Locked]
    public string $calendarMonth;

    public function mount(): void
    {
        $this->calendarMonth = today()->format('Y-m');
    }

    public function moveMonth(int $direction): void
    {
        abort_unless(in_array($direction, [-1, 1], true), 422);
        $month = Carbon::parse($this->calendarMonth.'-01')->addMonths($direction);
        abort_unless($month->betweenIncluded(today()->subMonthsNoOverflow($this->months)->startOfWeek()->startOfMonth(), today()->startOfMonth()), 422);
        $this->calendarMonth = $month->format('Y-m');
    }

    public function setRange(int $months): void
    {
        abort_unless(in_array($months, [3, 6, 12], true), 422);
        $this->months = $months;
        $this->showDay = false;
        $this->selectedDate = null;
        $this->calendarMonth = today()->format('Y-m');
        $this->resetPage('activityDayPage');
    }

    public function selectDate(string $date): void
    {
        $this->validate(['months' => 'integer|in:3,6,12']);
        abort_unless(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date), 422);
        try {
            $parsed = Carbon::createFromFormat('!Y-m-d', $date);
        } catch (\Throwable $e) {
            abort(422);
        }
        abort_unless($parsed && $parsed->format('Y-m-d') === $date && $parsed->betweenIncluded(today()->subMonthsNoOverflow($this->months)->startOfWeek(), today()), 422);
        $this->selectedDate = $date;
        $this->showDay = true;
        $this->resetPage('activityDayPage');
    }

    public function render()
    {
        abort_unless(auth()->check(), 401);
        $months = in_array($this->months, [3, 6, 12], true) ? $this->months : 12;
        $start = today()->subMonthsNoOverflow($months)->startOfWeek();
        $end = today();
        $counts = JobApplication::where('user_id', auth()->id())->whereBetween('application_date', [$start->toDateString(), $end->copy()->endOfDay()])->selectRaw('DATE(application_date) as date, COUNT(*) as count')->groupBy('date')->pluck('count', 'date');
        $heatmapWeeks = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $heatmapWeeks[$date->copy()->startOfWeek()->format('Y-m-d')][$date->dayOfWeek] = ['date' => $date->format('Y-m-d'), 'count' => $counts[$date->format('Y-m-d')] ?? 0, 'formattedDate' => $date->format('d M Y')];
        }
        $totalHeatmapApps = $counts->sum();
        $dailyAverage = $totalHeatmapApps / ($start->diffInDays($end) + 1);
        $dayApplications = $this->showDay && $this->selectedDate ? JobApplication::where('user_id', auth()->id())->whereDate('application_date', $this->selectedDate)->orderByDesc('id')->paginate(8, ['*'], 'activityDayPage') : null;
        $selectedLabel = $this->selectedDate ? Carbon::parse($this->selectedDate)->format('l, d M Y') : '';

        $monthStart = Carbon::parse($this->calendarMonth.'-01');
        $calendarDays = [];
        for ($date = $monthStart->copy()->startOfWeek(); $date->lte($monthStart->copy()->endOfMonth()->endOfWeek()); $date->addDay()) {
            $calendarDays[] = ['date' => $date->toDateString(), 'day' => $date->day,
                'inMonth' => $date->month === $monthStart->month,
                'enabled' => $date->month === $monthStart->month && $date->betweenIncluded($start, $end),
                'count' => (int) ($counts[$date->toDateString()] ?? 0), 'today' => $date->isToday()];
        }
        $monthLabel = $monthStart->format('F Y');
        $canPreviousMonth = $monthStart->gt($start->copy()->startOfMonth());
        $canNextMonth = $monthStart->lt(today()->startOfMonth());

        return view('livewire.dashboard-activity', compact('heatmapWeeks', 'totalHeatmapApps', 'dailyAverage', 'dayApplications', 'selectedLabel', 'calendarDays', 'monthLabel', 'canPreviousMonth', 'canNextMonth'));
    }
}
