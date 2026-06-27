{{-- Ultra-Sleek & Compact Bottom-Right Toast Container --}}
<div id="toast-container" class="fixed bottom-5 right-5 z-[10000] flex flex-col-reverse gap-2.5 pointer-events-none max-w-full sm:max-w-none"></div>

<script>
    // Hyper-Compact Notion/Linear-Style Toast System
    window.showToast = function(type, title, message, duration = 3500, customIcon = null) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        // If only title or message is provided, handle gracefully
        if (!message && title) {
            message = title;
            title = '';
        }

        const toast = document.createElement('div');
        
        const styleConfigs = {
            success: {
                icon: 'ph-fill ph-check-circle',
                iconBox: 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400',
                glow: 'shadow-[0_4px_20px_-2px_rgba(16,185,129,0.2)] border-emerald-500/30',
                bar: 'bg-emerald-400'
            },
            error: {
                icon: 'ph-fill ph-warning-circle',
                iconBox: 'bg-rose-500/15 border-rose-500/30 text-rose-400',
                glow: 'shadow-[0_4px_20px_-2px_rgba(244,63,94,0.2)] border-rose-500/30',
                bar: 'bg-rose-400'
            },
            info: {
                icon: 'ph-fill ph-info',
                iconBox: 'bg-primary-500/15 border-primary-500/30 text-primary-300',
                glow: 'shadow-[0_4px_20px_-2px_rgba(168,85,247,0.2)] border-primary-500/30',
                bar: 'bg-primary-400'
            },
            warning: {
                icon: 'ph-fill ph-warning',
                iconBox: 'bg-amber-500/15 border-amber-500/30 text-amber-400',
                glow: 'shadow-[0_4px_20px_-2px_rgba(245,158,11,0.2)] border-amber-500/30',
                bar: 'bg-amber-400'
            }
        };

        const cfg = styleConfigs[type] || styleConfigs.info;
        const activeIcon = customIcon ? customIcon : cfg.icon;

        // Ultra compact, sleek obsidian card styling
        toast.className = `pointer-events-auto relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-zinc-950/95 backdrop-blur-xl border ${cfg.glow} text-white shadow-2xl transition-all duration-300 transform translate-y-6 scale-95 opacity-0 overflow-hidden group select-none min-w-[260px] max-w-[340px]`;

        toast.innerHTML = `
            <!-- Ambient Subtle Radial Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/5 via-transparent to-transparent pointer-events-none"></div>

            <!-- Micro Icon Container -->
            <div class="w-7 h-7 rounded-lg border ${cfg.iconBox} flex items-center justify-center shrink-0 shadow-3xs relative z-10">
                <i class="${activeIcon} text-base"></i>
            </div>

            <!-- Text Content Container -->
            <div class="flex-1 min-w-0 relative z-10 pr-1">
                ${title ? `<h4 class="text-xs font-bold text-white tracking-tight leading-none truncate">${title}</h4>` : ''}
                ${message ? `<p class="text-[11px] font-medium text-zinc-300 ${title ? 'mt-1' : ''} leading-tight line-clamp-2">${message}</p>` : ''}
            </div>

            <!-- Close Trigger -->
            <button class="shrink-0 w-5 h-5 rounded-md text-zinc-400 hover:text-white hover:bg-zinc-800/80 flex items-center justify-center transition-colors relative z-10 focus:outline-none ml-auto">
                <i class="ph-bold ph-x text-xs"></i>
            </button>

            <!-- Bottom Micro Progress Line -->
            ${duration > 0 ? `
                <div class="absolute bottom-0 left-0 h-[2px] bg-zinc-800/80 w-full pointer-events-none">
                    <div class="toast-progress-bar h-full ${cfg.bar} origin-left transition-all ease-linear" style="width: 100%; transition-duration: ${duration}ms"></div>
                </div>
            ` : ''}
        `;

        container.appendChild(toast);

        // Animate In
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-6', 'scale-95', 'opacity-0');
                toast.classList.add('translate-y-0', 'scale-100', 'opacity-100');
                
                const bar = toast.querySelector('.toast-progress-bar');
                if (bar && duration > 0) {
                    setTimeout(() => {
                        bar.style.width = '0%';
                    }, 20);
                }
            });
        });

        const removeToast = () => {
            toast.classList.add('translate-y-4', 'scale-95', 'opacity-0');
            toast.classList.remove('translate-y-0', 'scale-100', 'opacity-100');
            setTimeout(() => toast.remove(), 300);
        };

        const closeBtn = toast.querySelector('button');
        if (closeBtn) closeBtn.onclick = removeToast;
        if (duration > 0) setTimeout(removeToast, duration);
    };

    // Global Alias for Notification Bell
    window.showToastNotification = function(notification) {
        if (!notification) return;
        const type = notification.type || 'info';
        const title = notification.title || '';
        const message = notification.message || notification.body || '';
        window.showToast(type, title, message, 3500);
    };

    // Listen for Livewire Events
    document.addEventListener('livewire:init', () => {
        Livewire.on('showNotification', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            window.showToast(data.type || 'info', data.title || '', data.message || '', data.duration || 3500, data.icon || null);
        });

        Livewire.on('showToast', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            if (typeof data === 'string') {
                window.showToast('info', '', data);
            } else {
                window.showToast(data.type || 'info', data.title || '', data.message || data.body || '', data.duration || 3500);
            }
        });
    });
</script>
