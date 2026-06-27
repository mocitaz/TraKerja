<?php

namespace App\Services;

use Carbon\Carbon;

class CalendarEventService
{
    /**
     * Generate Google Calendar Event Link
     */
    public static function googleCalendarUrl(string $title, string $startDateTime, string $details = '', string $location = ''): string
    {
        $start = Carbon::parse($startDateTime)->format('Ymd\THis');
        $end = Carbon::parse($startDateTime)->addHour()->format('Ymd\THis');

        $params = http_build_query([
            'action' => 'TEMPLATE',
            'text' => $title,
            'dates' => $start . '/' . $end,
            'details' => $details,
            'location' => $location,
        ]);

        return 'https://calendar.google.com/calendar/render?' . $params;
    }

    /**
     * Generate Outlook Web Calendar Event Link
     */
    public static function outlookCalendarUrl(string $title, string $startDateTime, string $details = '', string $location = ''): string
    {
        $start = Carbon::parse($startDateTime)->toIso8601String();
        $end = Carbon::parse($startDateTime)->addHour()->toIso8601String();

        $params = http_build_query([
            'path' => '/calendar/action/compose',
            'rru' => 'addevent',
            'subject' => $title,
            'startdt' => $start,
            'enddt' => $end,
            'body' => $details,
            'location' => $location,
        ]);

        return 'https://outlook.live.com/calendar/0/deeplink/compose?' . $params;
    }
}
