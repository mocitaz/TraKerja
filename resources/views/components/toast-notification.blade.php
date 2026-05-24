{{-- Premium Glassmorphism Toast Container --}}
<div id="toast-container" class="fixed bottom-6 right-6 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

<script>
    // Premium Toast Notification System (Sonner/Vercel Style)
    function showToast(type, title, message, duration = 4000, customIcon = null) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const icons = {
            success: 'ph-fill ph-check-circle text-emerald-400',
            error: 'ph-fill ph-warning-circle text-rose-400',
            info: 'ph-fill ph-info text-blue-400',
            warning: 'ph-fill ph-warning text-amber-400'
        };

        const glowColors = {
            success: 'shadow-[0_0_30px_-5px_rgba(16,185,129,0.3)] border-emerald-500/30',
            error: 'shadow-[0_0_30px_-5px_rgba(244,63,94,0.3)] border-rose-500/30',
            info: 'shadow-[0_0_30px_-5px_rgba(59,130,246,0.3)] border-blue-500/30',
            warning: 'shadow-[0_0_30px_-5px_rgba(245,158,11,0.3)] border-amber-500/30'
        };

        // Glassmorphism styling (Dark/Sleek theme)
        toast.className = `relative flex items-start gap-3.5 p-4 rounded-2xl bg-slate-900/80 backdrop-blur-2xl border ${glowColors[type] || glowColors.info} pointer-events-auto transition-all duration-500 transform translate-y-10 scale-95 opacity-0 hover:-translate-y-1 overflow-hidden group`;
        toast.style.width = '340px';

        toast.innerHTML = `
            <!-- Ambient Glow Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            
            <div class="mt-0.5 shrink-0 relative z-10">
                <i class="${customIcon ? customIcon : ('ph-bold ' + (icons[type] || icons.info))} text-[22px]"></i>
            </div>
            <div class="flex-1 min-w-0 relative z-10">
                <h4 class="text-sm font-bold text-white tracking-tight leading-none">${title}</h4>
                <p class="text-[12px] font-medium text-slate-300 mt-1.5 leading-relaxed break-words">${message}</p>
            </div>
            <button class="shrink-0 text-slate-400 hover:text-white transition-colors p-1 -m-1 rounded-lg hover:bg-white/10 relative z-10 focus:outline-none">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
            
            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-[3px] bg-white/10 w-full">
                <div class="h-full bg-current origin-left transition-all ease-linear" style="color: inherit; width: 100%; transition-duration: ${duration}ms"></div>
            </div>
        `;

        container.appendChild(toast);

        // Animate In (Slide Up and Scale)
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'scale-95', 'opacity-0');
                toast.classList.add('translate-y-0', 'scale-100', 'opacity-100');
                
                // Start Progress Bar
                const progressBar = toast.querySelector('.bg-current');
                if (progressBar && duration > 0) {
                    setTimeout(() => {
                        progressBar.style.width = '0%';
                    }, 50);
                }
            });
        });

        const removeToast = () => {
            toast.classList.add('translate-y-8', 'scale-95', 'opacity-0');
            toast.classList.remove('translate-y-0', 'scale-100', 'opacity-100');
            setTimeout(() => toast.remove(), 500);
        };

        toast.querySelector('button').onclick = removeToast;
        if (duration > 0) setTimeout(removeToast, duration);
    }

    // Listen for Livewire showNotification
    document.addEventListener('livewire:init', () => {
        Livewire.on('showNotification', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            showToast(data.type, data.title, data.message, data.duration || 4000, data.icon || null);
        });
    });
</script>
