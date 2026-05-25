<x-admin-layout>
    <style>
        .mesh-gradient-primary {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(79, 70, 229, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-emerald {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-pink {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(236, 72, 153, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(219, 39, 119, 0.03) 0px, transparent 50%);
        }
        .mesh-gradient-amber {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.03) 0px, transparent 50%);
        }
        .bento-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 10px 20px -5px rgba(0, 0, 0, 0.03);
        }
        .bento-card-stat {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.8), 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        /* No hover animations as per user request */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6 sm:space-y-8 pb-10">
        @livewire('admin.analytics')
    </div>
</x-admin-layout>
