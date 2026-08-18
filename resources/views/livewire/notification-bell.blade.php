<div class="relative" x-data="{ open: false }" @click.outside="open = false" wire:poll.45s="loadNotifications">
    <!-- Bell Trigger -->
    <button @click="open = !open" class="relative p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/80 rounded-2xl transition-all duration-200 focus:outline-none group" title="{{ __('Notifications') }}">
        <svg class="w-5 h-5 transition-transform duration-200 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600 ring-2 ring-white dark:ring-gray-900"></span>
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute right-0 mt-3 w-84 sm:w-96 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border border-gray-200/80 dark:border-gray-800/80 rounded-3xl shadow-2xl z-50 overflow-hidden">
        
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800/80 flex justify-between items-center bg-gray-50/50 dark:bg-gray-950/40">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-sm text-gray-900 dark:text-white">{{ __('Notifications') }}</span>
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-extrabold bg-red-500/10 text-red-600 dark:text-red-400 rounded-full border border-red-200 dark:border-red-900/40">
                        {{ $unreadCount }} {{ $unreadCount > 1 ? __('nouvelles') : __('nouvelle') }}
                    </span>
                @endif
            </div>

            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 font-bold transition">
                    {{ __('Tout marquer lu') }}
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800/50">
            @forelse($unreadNotifications as $notif)
                <div class="px-5 py-3.5 hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors {{ !$notif->is_read ? 'bg-red-50/30 dark:bg-red-950/20' : '' }}">
                    <div class="flex justify-between items-start gap-2">
                        <p class="text-xs font-bold {{ !$notif->is_read ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                            {{ $notif->title }}
                        </p>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 shrink-0 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $notif->content }}</p>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-xs text-gray-400 dark:text-gray-500">
                    <div class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-700">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <span>{{ __('Aucune notification pour le moment.') }}</span>
                </div>
            @endforelse
        </div>

        <div class="p-3 bg-gray-50/70 dark:bg-gray-950/60 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="{{ route('negotiations.index') }}" class="text-xs font-bold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                {{ __('Voir toutes les négociations') }} →
            </a>
        </div>
    </div>
</div>

