@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img src="{{ asset('images/icon.png') }}" alt="{{ __('TraKerja') }}" class="logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
    <div style="display: none; font-size: 24px; font-weight: bold; color: #6b46c1;">{{ __('TraKerja') }}</div>
</a>
</td>
</tr>
