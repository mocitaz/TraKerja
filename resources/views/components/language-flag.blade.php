@props(['code'])
                    @if($code === 'en')
                        <svg viewBox="0 0 60 60" focusable="false"><path fill="#234075" d="M0 0h60v60H0z"/><path stroke="#fff" stroke-width="13" d="m0 0 60 60M60 0 0 60"/><path stroke="#c63d4b" stroke-width="5" d="m0 0 60 60M60 0 0 60"/><path stroke="#fff" stroke-width="21" d="M30 0v60M0 30h60"/><path stroke="#c63d4b" stroke-width="12" d="M30 0v60M0 30h60"/></svg>
                    @else
                        <svg viewBox="0 0 60 60" focusable="false"><path fill="#e34b50" d="M0 0h60v30H0z"/><path fill="#fff" d="M0 30h60v30H0z"/></svg>
                    @endif
