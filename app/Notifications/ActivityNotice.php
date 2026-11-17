<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * An in-app record of something that happened in the user's workspace (application saved, stage changed,
 * payment received, export ready...). Stores translation keys, not text, so it renders in the user's language.
 */
class ActivityNotice extends Notification
{
    /**
     * @param  'plum'|'green'|'blue'|'amber'|'red'|'grey'  $tone
     */
    public function __construct(
        public string $event,
        public string $icon,
        public string $tone,
        public string $title,
        public array $titleParams = [],
        public ?string $text = null,
        public array $textParams = [],
        public ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'activity', 'event' => $this->event, 'icon' => $this->icon, 'tone' => $this->tone,
            'title' => $this->title, 'title_params' => $this->titleParams,
            'text' => $this->text, 'text_params' => $this->textParams, 'url' => $this->url,
        ];
    }

    /** Send to the signed-in user only when they caused it themselves (not seeders, imports or admins). */
    public static function toOwner(?int $userId, self $notice): void
    {
        if (! $userId || app()->runningInConsole() && ! app()->runningUnitTests() || auth()->id() !== $userId) {
            return;
        }
        rescue(fn () => auth()->user()->notify($notice), null, false);
    }
}
