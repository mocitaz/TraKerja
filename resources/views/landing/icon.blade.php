<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lp-icon">
    @switch($name ?? 'arrow')
        @case('tracker')<rect x="4" y="3" width="16" height="18" rx="3"/><path d="m8 8 1 1 2-2m2 1h3M8 13h8M8 17h5"/>@break
        @case('document')<path d="M13 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10L13 3Z"/><path d="M13 3v7h7M8 14h8M8 17h5"/>@break
        @case('analytics')<path d="M4 4v16h17M8 16v-4m5 4V8m5 8V5"/>@break
        @case('star')<path d="m12 2.5 2.9 5.9 6.5.9-4.7 4.6 1.1 6.5-5.8-3.1-5.8 3.1 1.1-6.5-4.7-4.6 6.5-.9Z"/>@break
        @case('play')<path d="m9 5 10 7-10 7V5Z"/>@break
        @case('pause')<path d="M8 5v14M16 5v14"/>@break
        @case('check')<path d="m5 12 4 4L19 6"/>@break
        @case('moon')<path d="M20.5 13A8.5 8.5 0 0 1 11 3.5 8.5 8.5 0 1 0 20.5 13Z"/>@break
        @case('plus')<path d="M12 5v14M5 12h14"/>@break
        @case('copy')<rect x="8" y="8" width="12" height="13" rx="2"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/>@break
        @case('calendar')<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 3v4M17 3v4M3 11h18M8 15h3"/>@break
        @case('up')<path d="M12 20V4m-6 6 6-6 6 6"/>@break
        @default<path d="M4 12h16m-6-6 6 6-6 6"/>
    @endswitch
</svg>
