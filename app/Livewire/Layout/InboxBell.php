<?php

namespace App\Livewire\Layout;

use App\Notifications\ActivityNotice;
use App\Notifications\SavedSearchAlert;
use App\Notifications\VacancyClosed;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

/** In-app notifications in the workspace header (TraKerja 2.0, Fase 6): job alerts and closed jobs. */
class InboxBell extends Component
{
    public const TYPES = [SavedSearchAlert::class, VacancyClosed::class, ActivityNotice::class];

    public bool $open = false;

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications()->whereIn('type', self::TYPES)->update(['read_at' => now()]);
    }

    public function visit(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->redirect(self::present($notification)['url'], navigate: true);
    }

    /** @return array{icon: string, tone: string, title: string, text: string, url: string} */
    public static function present(DatabaseNotification $notification): array
    {
        $d = $notification->data;
        if (($d['kind'] ?? null) === 'activity') {
            return [
                'icon' => $d['icon'] ?? 'ph-bell', 'tone' => $d['tone'] ?? 'plum',
                'title' => __($d['title'] ?? '', (array) ($d['title_params'] ?? [])),
                'text' => isset($d['text']) ? __($d['text'], (array) ($d['text_params'] ?? [])) : '',
                'url' => $d['url'] ?? route('jobs.explore'),
            ];
        }
        if (($d['kind'] ?? null) === 'vacancy_closed') {
            return [
                'icon' => 'ph-door', 'tone' => 'red',
                'title' => __('Job closed: :title', ['title' => $d['title'] ?? '']),
                'text' => ($d['relation'] ?? '') === 'applied'
                    ? __(':company stopped accepting applications. Your application stays in your tracker.', ['company' => $d['company'] ?: __('The company')])
                    : __(':company stopped accepting applications for a job you saved.', ['company' => $d['company'] ?: __('The company')]),
                'url' => ($d['relation'] ?? '') === 'applied' && ! empty($d['application_id'])
                    ? route('jobs.show', $d['application_id']) : route('jobs.explore', ['collection' => 'saved']),
            ];
        }
        $names = collect($d['searches'] ?? [])->pluck('name')->take(2)->implode(', ');

        return [
            'icon' => 'ph-bell-ringing', 'tone' => 'blue',
            'title' => trans_choice('{1} 1 new job for your saved searches|[2,*] :count new jobs for your saved searches', (int) ($d['total'] ?? 0), ['count' => (int) ($d['total'] ?? 0)]),
            'text' => $names,
            'url' => route('jobs.explore'),
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $types = self::TYPES;

        return view('livewire.layout.inbox-bell', [
            'unread' => $user->unreadNotifications()->whereIn('type', $types)->count(),
            'items' => $this->open ? $user->notifications()->whereIn('type', $types)->latest()->limit(12)->get() : collect(),
        ]);
    }
}
