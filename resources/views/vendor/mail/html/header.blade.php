@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img src="{{ config('app.url') }}/images/icon.png" alt="TraKerja" class="logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
    <div style="display: none; font-size: 24px; font-weight: bold; color: #6b46c1;">TraKerja</div>
</a>
</td>
</tr>
