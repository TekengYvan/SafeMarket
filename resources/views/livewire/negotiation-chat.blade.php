<div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 overflow-hidden shadow-xl rounded-3xl flex flex-col h-[650px]" wire:poll.5s>
    <!-- Header Info Card -->
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/50 flex flex-wrap justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center font-bold shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 dark:text-gray-500">{{ __('Négociation de prix en direct') }}</span>
                <h3 class="font-extrabold text-gray-900 dark:text-white text-base md:text-lg line-clamp-1">{{ $negotiation->product->title }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Acheteur') }}: <strong class="text-gray-700 dark:text-gray-300">{{ $negotiation->buyer->name }}</strong> • {{ __('Vendeur') }}: <strong class="text-gray-700 dark:text-gray-300">{{ $negotiation->seller->name }}</strong>
                </p>
            </div>
        </div>

        <div class="text-left sm:text-right shrink-0">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">{{ __('Offre Proposée') }}</span>
            <p class="text-2xl font-black text-red-600 dark:text-red-400 tracking-tight">{{ number_format($negotiation->proposed_price, 2) }} FCFA</p>
            <div class="mt-0.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide
                    @if($negotiation->status == 'pending') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50
                    @elseif($negotiation->status == 'accepted') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50
                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50 @endif">
                    {{ $negotiation->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Chat Messages Scroll Area -->
    <div class="flex-grow p-6 overflow-y-auto space-y-4 bg-stone-50/40 dark:bg-gray-950/30" id="chat-box" x-data x-init="$el.scrollTop = $el.scrollHeight" x-on:messageSent.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })">
        @forelse($messages as $msg)
            <div wire:key="msg-{{ $msg->id }}" class="flex {{ (int)$msg->user_id === (int)auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] rounded-2xl p-4 shadow-sm {{ (int)$msg->user_id === (int)auth()->id() ? 'bg-red-600 text-white rounded-br-none' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-750 rounded-bl-none' }}">
                    <div class="flex justify-between items-center gap-4 mb-1">
                        <span class="text-[10px] font-bold opacity-80 uppercase tracking-wider">{{ $msg->user?->name ?? __('Utilisateur') }}</span>
                        <span class="text-[9px] opacity-60">{{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}</span>
                    </div>
                    <p class="text-sm font-medium leading-relaxed whitespace-pre-wrap">{{ $msg->content }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-stone-400 text-xs italic">
                {{ __('Aucun message dans cette négociation pour l\'instant. Exprimez votre offre ou posez vos questions ci-dessous.') }}
            </div>
        @endforelse
    </div>

    <!-- Footer Form Controls -->
    <div class="p-4 sm:p-5 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
        @if(in_array($negotiation->status, ['pending', 'accepted'], true))
            @if(auth()->id() === $negotiation->seller_id)
                @if($negotiation->status === 'pending')
                <div class="flex gap-3 mb-4">
                    <button wire:click="updateStatus('accepted')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ __('Accepter l\'offre') }} ({{ number_format($negotiation->proposed_price, 2) }} FCFA)</span>
                    </button>
                    <button wire:click="updateStatus('rejected')" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>{{ __('Refuser l\'offre') }}</span>
                    </button>
                </div>
                @endif
            @endif

            @if(auth()->id() === $negotiation->buyer_id && $negotiation->status === 'pending')
                <button type="button" wire:click="updateStatus('cancelled')" wire:confirm="{{ __('Annuler cette négociation ?') }}" class="w-full mb-3 border border-rose-200 text-rose-700 dark:border-rose-900 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition">
                    {{ __('Annuler la proposition') }}
                </button>
            @endif

            <form wire:submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" wire:model="newMessage" placeholder="{{ __('Écrivez un message au vendeur / acheteur...') }}" class="flex-grow border-gray-200 dark:border-gray-800 dark:bg-gray-950 rounded-xl px-4 py-3 text-sm focus:ring-red-500 focus:border-red-500 text-gray-900 dark:text-white outline-none" required>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition flex items-center gap-1.5 shrink-0 shadow-md">
                    <span>{{ __('Envoyer') }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7-9 7 9 7zm0 0v-8"></path></svg>
                </button>
            </form>
        @else
            <div class="text-center py-2 text-stone-400 text-xs font-semibold italic flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>{{ __('Cette négociation est terminée') }} ({{ __('Statut') }} : {{ $negotiation->status }}).</span>
            </div>
        @endif
    </div>
</div>
