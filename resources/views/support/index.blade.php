<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                Customer <span
                    class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-indigo-600">Support</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Get assistance, submit
                feedback, or request features</p>
        </div>
    </x-slot>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#f8fafc] min-h-screen pb-20 pt-8" x-data="{ activeTab: 'submit' }">
        <div class="max-w-[1300px] w-full mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Alert Messages --}}
            @if(session('success_message'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow">
                        <i class="ph-bold ph-check text-base"></i>
                    </div>
                    <p class="text-sm font-semibold">{{ session('success_message') }}</p>
                </div>
            @endif

            {{-- Tabs Selector --}}
            <div class="flex p-1 bg-white border border-slate-200/60 rounded-2xl shadow-sm mb-8 max-w-md shrink-0">
                <button @click="activeTab = 'submit'"
                    :class="activeTab === 'submit' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300 uppercase tracking-widest">
                    Submit New Ticket
                </button>
                <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300 uppercase tracking-widest relative">
                    Ticket History
                    @if($tickets->whereIn('status', ['replied', 'completed', 'on_hold'])->count() > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </button>
            </div>

            {{-- Tab 1: Submit Ticket Form --}}
            <div x-show="activeTab === 'submit'" x-transition:enter="transition ease-out duration-300"
                class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Form Panel --}}
                <div
                    class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 sm:p-8 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-[80px] -mr-32 -mt-32 opacity-40">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-50 rounded-full blur-[80px] -ml-32 -mb-32 opacity-40">
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mb-2">How can we help you today?
                        </h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Describe your issue
                            or share feedback, and our team will get back to you.</p>

                        <form action="{{ route('support.store') }}" method="POST" class="space-y-6">
                            @csrf

                            {{-- Category --}}
                            <div>
                                <label for="category"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Category</label>
                                <select name="category" id="category" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm">
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="technical_issue" {{ old('category') == 'technical_issue' ? 'selected' : '' }}>Technical Issue / Bug Report</option>
                                    <option value="payment_billing" {{ old('category') == 'payment_billing' ? 'selected' : '' }}>Payment & Billing Issues</option>
                                    <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                                    <option value="general_feedback" {{ old('category') == 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                                </select>
                                @error('category')
                                    <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label for="subject"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Subject</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                    placeholder="e.g. Can't download CV as PDF"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm">
                                @error('subject')
                                    <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div>
                                <label for="message"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Detailed
                                    Message</label>
                                <textarea name="message" id="message" rows="6" required
                                    placeholder="Please explain with detail..."
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all shadow-sm resize-none">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-paper-plane-tilt text-base"></i> Send Support Request
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Sidebar Guidelines Panel --}}
                <div class="space-y-6">
                    <div
                        class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-primary-500 opacity-20 blur-2xl">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-5 border border-white/10 shadow-inner">
                                <i class="ph-duotone ph-headset text-2xl text-primary-300"></i>
                            </div>
                            <h4 class="text-lg font-black tracking-tight mb-2">Premium Help Desk</h4>
                            <p class="text-xs text-slate-300 leading-relaxed mb-6">Our dedicated tech support team
                                operates Monday - Friday from 9:00 AM to 6:00 PM (GMT+7). We aim to respond within 2-4
                                hours.</p>

                            <div class="space-y-4 pt-4 border-t border-white/10">
                                <div class="flex items-center gap-3">
                                    <i class="ph-bold ph-envelope text-lg text-primary-300"></i>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            Direct Email</p>
                                        <p class="text-xs font-bold text-white mt-0.5">trakerja@teknalogi.id</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="ph-bold ph-phone text-lg text-primary-300"></i>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            Emergency Hotlines</p>
                                        <p class="text-xs font-bold text-white mt-0.5">+62 21-5555-0199</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Card --}}
                    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4">Quick Answers</h4>
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-xs font-black text-slate-800">How long will my support ticket take?</h5>
                                <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Typically you
                                    will receive a comprehensive resolution from our admin support desk within a few
                                    hours on business days.</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-black text-slate-800">Can I request feature enhancements?</h5>
                                <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Absolutely!
                                    Please submit them under the 'Feature Request' category. We track popular
                                    suggestions closely.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Ticket History --}}
            <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300"
                class="max-w-4xl mx-auto space-y-6">

                @forelse($tickets as $ticket)
                    @php
                        $userStatusClasses = [
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'replied' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'on_hold' => 'bg-rose-100 text-rose-800 border-rose-200',
                        ][$ticket->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';

                        $userStatusIcon = [
                            'pending' => 'ph-clock',
                            'replied' => 'ph-chat-circle-dots',
                            'completed' => 'ph-check-circle',
                            'on_hold' => 'ph-warning-circle',
                        ][$ticket->status] ?? 'ph-question';
                        
                        $userStatusBg = [
                            'pending' => 'bg-amber-50 text-amber-600',
                            'replied' => 'bg-indigo-50 text-indigo-600',
                            'completed' => 'bg-emerald-50 text-emerald-600',
                            'on_hold' => 'bg-rose-50 text-rose-600',
                        ][$ticket->status] ?? 'bg-slate-50 text-slate-600';
                    @endphp

                    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden"
                        x-data="{ expanded: false }">

                        {{-- Ticket Summary Header --}}
                        <div class="p-6 cursor-pointer hover:bg-slate-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                            @click="expanded = !expanded">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-inner {{ $userStatusBg }}">
                                    <i class="ph-bold {{ $userStatusIcon }} text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border uppercase tracking-widest mb-1.5 {{ $userStatusClasses }}">
                                        {{ $ticket->status }}
                                    </span>
                                    <h4 class="text-base font-black text-slate-900 tracking-tight leading-snug truncate">
                                        {{ $ticket->subject }}</h4>
                                    <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $ticket->category_label }}</span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span
                                            class="text-[10px] font-medium text-slate-400">{{ $ticket->created_at->format('d M Y H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 self-end sm:self-center">
                                <form action="{{ route('support.destroy', $ticket->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket bantuan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop
                                        class="w-8 h-8 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-colors">
                                        <i class="ph-bold ph-trash text-base"></i>
                                    </button>
                                </form>
                                <button
                                    class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-transform duration-300"
                                    :class="expanded ? 'rotate-180' : ''">
                                    <i class="ph-bold ph-caret-down text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Expanded Content --}}
                        <div x-show="expanded" x-collapse x-cloak
                            class="border-t border-slate-100 bg-slate-50/50 p-6 space-y-6">

                            {{-- User Message --}}
                            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                                        <span
                                            class="text-xs font-bold text-slate-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">You</p>
                                        <p class="text-[10px] font-medium text-slate-400">
                                            {{ $ticket->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-slate-600 leading-relaxed whitespace-pre-line">
                                    {{ $ticket->message }}</p>
                            </div>

                            {{-- Admin Reply --}}
                            @if($ticket->isReplied())
                                <div
                                    class="bg-indigo-50/30 rounded-2xl border border-indigo-100 p-5 shadow-sm relative overflow-hidden">
                                    <div
                                        class="absolute top-0 right-0 -mr-12 -mt-12 w-24 h-24 rounded-full bg-indigo-500/5 blur-xl">
                                    </div>
                                    <div class="flex items-center gap-3 mb-3 relative z-10">
                                        <div
                                            class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shrink-0 shadow-md">
                                            <i class="ph-bold ph-shield-check text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-indigo-950">TraKerja Support Representative</p>
                                            <p class="text-[10px] font-bold text-indigo-500">
                                                {{ $ticket->replied_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <p
                                        class="text-sm font-medium text-indigo-900 leading-relaxed whitespace-pre-line relative z-10">
                                        {{ $ticket->admin_reply }}</p>
                                </div>
                            @else
                                <div
                                    class="p-4 rounded-2xl border border-dashed border-slate-200 text-center text-slate-400 font-bold text-xs">
                                    Support agent has received your request. We are preparing a resolution for you.
                                </div>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200/60 p-12 text-center">
                        <div
                            class="w-16 h-16 mx-auto bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                            <i class="ph-duotone ph-folder-open text-3xl"></i>
                        </div>
                        <h4 class="text-base font-black text-slate-800 mb-1">No ticket history found</h4>
                        <p class="text-xs font-medium text-slate-500 max-w-sm mx-auto leading-relaxed">Submit your first
                            customer support ticket or request assistance using the submit form tab.</p>
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>