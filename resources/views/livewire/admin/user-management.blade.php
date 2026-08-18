<div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>{{ __('Gestion des Utilisateurs') }}</span>
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Gérez les comptes, les rôles et la suspension des membres.') }}</p>
        </div>

        <div class="relative w-full sm:w-64">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Rechercher par nom, email...') }}" class="w-full text-xs bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white rounded-2xl pl-9 pr-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session()->has('status'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-800 dark:text-emerald-400 font-bold text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-400 font-bold text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 text-[10px] uppercase font-extrabold text-gray-400 tracking-wider">
                    <th class="py-3 px-4">{{ __('Nom / Contact') }}</th>
                    <th class="py-3 px-4">{{ __('Rôles') }}</th>
                    <th class="py-3 px-4">{{ __('Solde Wallet') }}</th>
                    <th class="py-3 px-4">{{ __('Statut Compte') }}</th>
                    <th class="py-3 px-4 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-xs">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-850/40 transition">
                        <!-- User Name & Email -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-extrabold flex items-center justify-center text-sm shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                                    <span class="text-[11px] text-gray-400 block">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Roles -->
                        <td class="py-3.5 px-4">
                            <div class="flex flex-wrap gap-1">
                                @if($user->is_admin)
                                    <span class="text-[9px] bg-red-100 dark:bg-red-950/50 text-red-700 dark:text-red-400 px-2 py-0.5 rounded-full uppercase font-black tracking-wider">{{ __('Admin') }}</span>
                                @endif
                                @forelse($user->roles as $role)
                                    @if($role->name !== 'admin')
                                        <span class="text-[9px] bg-indigo-100 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400 px-2 py-0.5 rounded-full uppercase font-bold tracking-wider">{{ $role->name }}</span>
                                    @endif
                                @empty
                                    <span class="text-[9px] bg-gray-100 dark:bg-gray-800 text-gray-500 px-2 py-0.5 rounded-full uppercase font-bold">{{ __('Membre') }}</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Balance -->
                        <td class="py-3.5 px-4 font-extrabold text-gray-900 dark:text-white">
                            {{ number_format($user->balance, 0, ',', ' ') }} FCFA
                        </td>

                        <!-- Account Status Badge -->
                        <td class="py-3.5 px-4">
                            @if($user->is_suspended)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    {{ __('Suspendu') }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ __('Actif') }}
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Suspend / Unsuspend Button -->
                                @if($user->id !== auth()->id())
                                    <button wire:click="toggleSuspend({{ $user->id }})" 
                                            class="px-3 py-1.5 rounded-xl text-[11px] font-extrabold transition shadow-sm flex items-center gap-1.5 {{ $user->is_suspended ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-amber-100 dark:bg-amber-950/50 hover:bg-amber-200 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-300' }}">
                                        @if($user->is_suspended)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                            <span>{{ __('Débloquer') }}</span>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            <span>{{ __('Suspendre') }}</span>
                                        @endif
                                    </button>

                                    <!-- Admin Toggle Button -->
                                    <button wire:click="toggleAdmin({{ $user->id }})" 
                                            class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        {{ $user->is_admin ? __('Retirer Admin') : __('Faire Admin') }}
                                    </button>

                                    <!-- Delete Button -->
                                    <button onclick="confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?') || event.stopImmediatePropagation()" 
                                            wire:click="deleteUser({{ $user->id }})" 
                                            class="p-1.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition" 
                                            title="{{ __('Supprimer') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @else
                                    <span class="text-[10px] text-gray-400 italic">{{ __('Compte actuel') }}</span>
                                @endif
                            </div>
                        </td>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 italic">{{ __('Aucun utilisateur trouvé.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
        {{ $users->links() }}
    </div>
</div>
