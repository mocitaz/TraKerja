<x-app-layout>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-[#fafafa] min-h-screen pb-16" x-data="{ activeTab: 'submit' }">
        <div class="max-w-[1300px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-headset text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">Customer Support</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Help</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Get assistance, submit feedback, or request new features.</p>
                    </div>
                </div>
            </div>

            {{-- Flash Alert Messages --}}
            @if(session('success_message'))
                <div class="mb-5 p-3.5 bg-emerald-50/40 border border-emerald-250 text-emerald-800 rounded-md flex items-center gap-2.5 shadow-3xs">
                    <i class="ph ph-check-circle text-emerald-600 text-base shrink-0"></i>
                    <p class="text-xs font-semibold leading-normal">{{ session('success_message') }}</p>
                </div>
            @endif

            <style>
                [x-cloak] { display: none !important; }
                
                /* Custom Notion Switcher Active Styles */
                .tab-btn { color: #71717a; }
                .tab-btn.active { 
                    background-color: #f5f3ff; 
                    color: #27272a; 
                    border: 1px solid rgba(221, 214, 254, 0.8);
                    font-weight: 700;
                    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
                }
                .tab-btn:not(.active):hover {
                    background-color: #f4f4f5;
                    color: #18181b;
                }

                /* Notion-Inspired Form Styling Overrides */
                .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]), 
                .premium-form-wrapper textarea,
                .premium-form-wrapper select {
                    width: 100% !important;
                    border-radius: 6px !important;
                    border: 1px solid #e4e4e7 !important;
                    background-color: rgba(250, 250, 250, 0.5) !important;
                    padding: 6px 10px !important;
                    font-size: 11px !important;
                    font-weight: 600 !important;
                    color: #3f3f46 !important;
                    transition: all 0.15s ease !important;
                    outline: none !important;
                }
                .premium-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):focus, 
                .premium-form-wrapper textarea:focus,
                .premium-form-wrapper select:focus {
                    background-color: #ffffff !important;
                    border-color: #a1a1aa !important;
                    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
                }
                
                .premium-form-wrapper label {
                    text-transform: uppercase !important;
                    letter-spacing: 0.05em !important;
                    font-size: 9.5px !important;
                    font-weight: 700 !important;
                    color: #a1a1aa !important;
                    margin-left: 2px !important;
                    margin-bottom: 4px !important;
                    display: block !important;
                }
                
                .premium-form-wrapper button[type="submit"] {
                    width: auto !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    gap: 6px !important;
                    padding: 6px 14px !important;
                    background-color: #f5f3ff !important;
                    color: #27272a !important;
                    border: 1px solid rgba(221, 214, 254, 0.8) !important;
                    font-size: 10px !important;
                    font-weight: 700 !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.05em !important;
                    border-radius: 6px !important;
                    transition: all 0.15s ease !important;
                    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.02) !important;
                }
                .premium-form-wrapper button[type="submit"]:hover {
                    background-color: #ede9fe !important;
                    color: #18181b !important;
                }

                .slide-fade-enter { animation: slideIn 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
                @keyframes slideIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
            </style>

            <!-- Premium Notion-Inspired Tab Switcher -->
            <div class="flex p-0.5 bg-white border border-zinc-200/70 rounded-md shadow-3xs mb-6 max-w-xs">
                <button @click="activeTab = 'submit'" 
                        :class="activeTab === 'submit' ? 'active' : ''"
                        class="tab-btn flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] font-bold rounded transition-colors focus:outline-none">
                    <i class="ph ph-envelope-open text-xs"></i>
                    <span>Submit Ticket</span>
                </button>
                <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'active' : ''"
                        class="tab-btn flex-1 justify-center flex items-center gap-1.5 py-1.5 text-[11px] font-bold rounded transition-colors focus:outline-none relative">
                    <i class="ph ph-clock text-xs"></i>
                    <span>Ticket History</span>
                    @if($tickets->whereIn('status', ['replied', 'completed', 'on_hold'])->count() > 0)
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                    @endif
                </button>
            </div>

            {{-- Tab 1: Submit Ticket Form --}}
            <div x-show="activeTab === 'submit'" class="grid grid-cols-1 lg:grid-cols-3 gap-6 slide-fade-enter">

                {{-- Form Panel --}}
                <div class="lg:col-span-2 bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs">
                    <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest pb-1.5 border-b border-zinc-100 mb-4 leading-none">How can we help you today?</h3>
                    
                    <form action="{{ route('support.store') }}" method="POST" class="space-y-4 premium-form-wrapper">
                        @csrf

                        {{-- Category --}}
                        <div>
                            <label for="category">Category</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                    <i class="ph ph-list-bullets text-[13px]"></i>
                                </div>
                                <select name="category" id="category" required style="padding-left: 32px !important; appearance: none;">
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="technical_issue" {{ old('category') == 'technical_issue' ? 'selected' : '' }}>Technical Issue / Bug Report</option>
                                    <option value="payment_billing" {{ old('category') == 'payment_billing' ? 'selected' : '' }}>Payment & Billing Issues</option>
                                    <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                                    <option value="general_feedback" {{ old('category') == 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                                </select>
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                    <i class="ph ph-caret-down text-[10px]"></i>
                                </div>
                            </div>
                            @error('category')
                                <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label for="subject">Subject</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none flex items-center">
                                    <i class="ph ph-info text-[13px]"></i>
                                </div>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                    placeholder="e.g. Can't download CV as PDF"
                                    style="padding-left: 32px !important;">
                            </div>
                            @error('subject')
                                <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div>
                            <label for="message">Detailed Message</label>
                            <textarea name="message" id="message" rows="5" required
                                placeholder="Please explain with detail..."
                                class="resize-none leading-relaxed">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-[9px] text-rose-500 font-bold uppercase tracking-wider">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit">
                                <i class="ph ph-paper-plane-tilt text-xs"></i>
                                <span>Send Support Request</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Sidebar Guidelines Panel --}}
                <div class="space-y-6">
                    <div class="bg-zinc-950 text-white rounded-lg p-4 border border-zinc-900 shadow-3xs">
                        <div class="w-6.5 h-6.5 rounded bg-white/10 flex items-center justify-center mb-3.5">
                            <i class="ph ph-headset text-sm text-zinc-200"></i>
                        </div>
                        <h4 class="text-xs font-bold tracking-tight mb-1">Premium Help Desk</h4>
                        <p class="text-[11px] text-zinc-450 leading-relaxed mb-4 font-semibold">Our support team operates Monday - Friday from 9:00 AM to 6:00 PM (GMT+7). We aim to respond within 2-4 hours.</p>

                        <div class="space-y-3 pt-3.5 border-t border-white/5">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-envelope text-base text-zinc-400"></i>
                                <div>
                                    <p class="text-[7.5px] font-bold uppercase tracking-widest text-zinc-500 leading-none">Direct Email</p>
                                    <p class="text-[11px] font-semibold text-zinc-200 mt-1 leading-none">trakerja@teknalogi.id</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ph ph-phone text-base text-zinc-400"></i>
                                <div>
                                    <p class="text-[7.5px] font-bold uppercase tracking-widest text-zinc-500 leading-none">Emergency Hotline</p>
                                    <p class="text-[11px] font-semibold text-zinc-200 mt-1 leading-none">+62 21-5555-0199</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Card --}}
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-4 shadow-3xs">
                        <h4 class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mb-3.5 pb-1.5 border-b border-zinc-100 leading-none">Quick Answers</h4>
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-xs font-bold text-zinc-800 leading-tight">Response Time?</h5>
                                <p class="text-[11px] font-medium text-zinc-550 mt-1.5 leading-relaxed">Typically you will receive a comprehensive resolution from our admin support desk within a few hours on business days.</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-zinc-800 leading-tight">Feature Requests?</h5>
                                <p class="text-[11px] font-medium text-zinc-550 mt-1.5 leading-relaxed">Absolutely! Please submit them under the 'Feature Request' category. We track popular suggestions closely.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Ticket History --}}
            <div x-show="activeTab === 'history'" class="max-w-3xl mx-auto space-y-4 slide-fade-enter" style="display:none;" x-cloak>

                @forelse($tickets as $ticket)
                    @php
                        $userStatusClasses = [
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                            'replied' => 'bg-indigo-50 text-indigo-750 border-indigo-200/60',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                            'on_hold' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                        ][$ticket->status] ?? 'bg-zinc-50 text-zinc-700 border-zinc-200/60';

                        $userStatusIcon = [
                            'pending' => 'ph-clock',
                            'replied' => 'ph-chat-circle-dots',
                            'completed' => 'ph-check-circle',
                            'on_hold' => 'ph-warning-circle',
                        ][$ticket->status] ?? 'ph-question';
                    @endphp

                    <div class="bg-white rounded-lg border border-zinc-200/60 shadow-3xs overflow-hidden"
                        x-data="{ expanded: false }">

                        {{-- Ticket Summary Header --}}
                        <div class="p-3.5 cursor-pointer hover:bg-zinc-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                            @click="expanded = !expanded">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded border border-zinc-200 bg-zinc-50 flex items-center justify-center text-zinc-700 shrink-0">
                                    <i class="ph {{ $userStatusIcon }} text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[7.5px] font-bold border uppercase tracking-wider mb-1 {{ $userStatusClasses }} leading-none">
                                        {{ $ticket->status }}
                                    </span>
                                    <h4 class="text-xs font-bold text-zinc-800 tracking-tight leading-snug truncate">
                                        {{ $ticket->subject }}
                                    </h4>
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider leading-none">{{ $ticket->category_label }}</span>
                                        <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                        <span class="text-[8px] font-semibold text-zinc-400 leading-none">{{ $ticket->created_at->format('d M Y H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 self-end sm:self-center">
                                <form action="{{ route('support.destroy', $ticket->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket bantuan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop
                                        class="w-6.5 h-6.5 rounded hover:bg-rose-50 border border-transparent hover:border-rose-150 text-zinc-400 hover:text-rose-600 flex items-center justify-center transition-colors focus:outline-none">
                                        <i class="ph ph-trash text-sm"></i>
                                    </button>
                                </form>
                                <button class="w-6.5 h-6.5 rounded hover:bg-zinc-50 text-zinc-500 border border-transparent hover:border-zinc-200 flex items-center justify-center transition-transform duration-200 focus:outline-none" :class="expanded ? 'rotate-180' : ''">
                                    <i class="ph ph-caret-down text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Expanded Content --}}
                        <div x-show="expanded" x-cloak class="border-t border-zinc-150 bg-zinc-50/40 p-4 space-y-4">

                            {{-- User Message --}}
                            <div class="bg-white rounded-md border border-zinc-200 p-3 shadow-3xs">
                                <div class="flex items-center gap-2 mb-2 pb-1 border-b border-zinc-100">
                                    <div class="w-5.5 h-5.5 bg-zinc-50 border border-zinc-200 rounded flex items-center justify-center shrink-0">
                                        <span class="text-[9.5px] font-bold text-zinc-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-[9.5px] font-bold text-zinc-800 leading-none">You</p>
                                        <p class="text-[8px] font-semibold text-zinc-400 mt-0.5 leading-none">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-xs font-medium text-zinc-650 leading-relaxed whitespace-pre-line">
                                    {{ $ticket->message }}
                                </p>
                            </div>

                            {{-- Admin Reply --}}
                            @if($ticket->isReplied())
                                <div class="bg-white border border-zinc-200 rounded-md p-3 shadow-3xs">
                                    <div class="flex items-center gap-2 mb-2 pb-1 border-b border-zinc-100">
                                        <div class="w-5.5 h-5.5 bg-zinc-950 text-white rounded flex items-center justify-center shrink-0">
                                            <i class="ph ph-shield-check text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9.5px] font-bold text-zinc-800 leading-none">Support Desk</p>
                                            <p class="text-[8px] font-semibold text-zinc-400 mt-0.5 leading-none">{{ $ticket->replied_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs font-medium text-zinc-650 leading-relaxed whitespace-pre-line">
                                        {{ $ticket->admin_reply }}
                                    </p>
                                </div>
                            @else
                                <div class="p-3 rounded-md border border-dashed border-zinc-200 bg-white text-center text-zinc-400 font-bold text-[9px] uppercase tracking-wider">
                                    Support agent has received your request. We are preparing a resolution for you.
                                </div>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-lg border border-zinc-200/60 p-6 text-center shadow-3xs animate-fade-in">
                        <div class="w-9 h-9 mx-auto bg-zinc-50 text-zinc-450 border border-zinc-200 rounded flex items-center justify-center mb-3">
                            <i class="ph ph-folder text-lg"></i>
                        </div>
                        <h4 class="text-xs font-bold text-zinc-850 mb-0.5 uppercase tracking-wider">No ticket history found</h4>
                        <p class="text-[11px] text-zinc-500 max-w-xs mx-auto leading-normal font-semibold">Submit your first support ticket using the submit form tab.</p>
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>