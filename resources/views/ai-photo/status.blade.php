<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8" x-data="photoRequestStatus(@js(route('ai-photo.status', $aiPhoto->id)), @js($aiPhoto->status))">
        <h1 class="text-xl font-semibold text-zinc-900">{{ __('Pemrosesan foto') }}</h1>
        <p class="mt-2 text-sm text-zinc-600">{{ __('Permintaan #:value1 tersimpan. Anda dapat meninggalkan halaman ini dan membukanya lagi dari riwayat Studio AI.', ['value1' => ($aiPhoto->id)]) }}</p>
        <div class="mt-6 p-5 bg-white border border-zinc-200 rounded-lg">
            <p class="font-medium text-zinc-900" role="status" aria-live="polite" x-text="labels[status]">{{ ['queued' => __('Menunggu antrean'), 'processing' => __('Foto sedang diproses'), 'failed' => __('Pemrosesan gagal')][$aiPhoto->status] ?? 'Memeriksa status' }}</p>
            <p class="mt-2 text-sm text-zinc-600" x-show="status === 'queued'">{{ __('Proses dimulai saat worker tersedia. Status akan diperiksa secara berkala.') }}</p>
            <p class="mt-2 text-sm text-zinc-600" x-show="status === 'processing'">{{ __('Layanan AI sedang memproses foto. Kredit tidak dipotong lagi saat halaman dimuat ulang.') }}</p>
            <p class="mt-2 text-sm text-zinc-600" x-show="status === 'failed'">{{ $aiPhoto->error_message }}</p>
            <p class="mt-2 text-sm text-rose-700" role="alert" x-show="error" x-text="error"></p>
            <button type="button" x-show="error" @click="poll()" class="mt-3 px-3 py-2 border border-zinc-300 rounded text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2">{{ __('Periksa lagi') }}</button>
        </div>
        @if($errors->any())
            <div role="alert" class="mt-4 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif
        <div class="mt-5 flex flex-wrap gap-3 items-center">
            <a href="{{ route('ai-photo.index') }}" class="px-4 py-2 border border-zinc-300 rounded text-sm text-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2">{{ __('Kembali ke Studio AI') }}</a>
            @if($aiPhoto->status === 'failed' && $aiPhoto->source_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($aiPhoto->source_path))
                <form method="POST" action="{{ route('ai-photo.retry', $aiPhoto->id) }}">
                    @csrf
                    <input type="hidden" name="request_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                    <button type="submit" class="px-4 py-2 bg-zinc-900 text-white rounded text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2">{{ __('Coba lagi:value1', ['value1' => (\App\Models\Setting::isMonetizationEnabled() ? ' · 1 kredit' : '')]) }}</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
