<x-safemarket-layout>
    <x-slot name="title">{{ __('Contactez-nous - SafeMarket') }}</x-slot>

    <!-- Contact Header Banner -->
    <div class="relative bg-zinc-900 py-16 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
            <span class="text-red-500 font-heading font-black tracking-widest text-xs uppercase block">{{ app()->getLocale() == 'en' ? 'Customer Support' : 'Support Client' }}</span>
            <h1 class="font-heading font-black text-4xl sm:text-5xl uppercase leading-none tracking-tight">
                {{ app()->getLocale() == 'en' ? 'CONTACT' : 'CONTACTEZ' }} <span class="text-red-500 italic font-black">{{ app()->getLocale() == 'en' ? 'THE SAFEMARKET TEAM' : 'L\'ÉQUIPE SAFEMARKET' }}</span>
            </h1>
            <p class="text-stone-300 text-sm max-w-xl mx-auto leading-relaxed">
                {{ app()->getLocale() == 'en' ? 'Questions about shipping, KYC merchant validation, or escrow operations? We reply within 24 hours.' : 'Une question sur la livraison, la validation de votre KYC commerçant, ou le fonctionnement de notre escrow ? Nous vous répondons sous 24h.' }}
            </p>
        </div>
    </div>

    <!-- Contact Grid Content -->
    <div class="py-16 bg-stone-50 dark:bg-zinc-950 text-stone-850 dark:text-zinc-300"
         x-data="{
             name: '', email: '', subject: '', message: '',
             formSubmitted: false,
             submitForm() {
                 this.formSubmitted = true;
                 this.name = ''; this.email = ''; this.subject = ''; this.message = '';
                 setTimeout(() => this.formSubmitted = false, 6500);
             }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Contact Form -->
            <div class="lg:col-span-7 bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-[2.5rem] p-8 shadow-sm">
                <h2 class="font-heading font-black text-2xl text-stone-900 dark:text-white uppercase mb-6">{{ app()->getLocale() == 'en' ? 'Send us a Message' : 'Envoyez-nous un Message' }}</h2>
                
                <form @submit.prevent="submitForm()" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-stone-400">{{ app()->getLocale() == 'en' ? 'Full Name' : 'Nom Complet' }}</label>
                            <input type="text" required x-model="name" class="w-full text-xs border border-stone-200 dark:border-zinc-800 rounded-xl px-4 py-3 bg-stone-50 dark:bg-zinc-950 focus:outline-none focus:border-red-500 text-stone-900 dark:text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-stone-400">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Adresse Email' }}</label>
                            <input type="email" required x-model="email" class="w-full text-xs border border-stone-200 dark:border-zinc-800 rounded-xl px-4 py-3 bg-stone-50 dark:bg-zinc-950 focus:outline-none focus:border-red-500 text-stone-900 dark:text-white">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-stone-400">{{ app()->getLocale() == 'en' ? 'Message Subject' : 'Sujet du Message' }}</label>
                        <input type="text" required x-model="subject" class="w-full text-xs border border-stone-200 dark:border-zinc-800 rounded-xl px-4 py-3 bg-stone-50 dark:bg-zinc-950 focus:outline-none focus:border-red-500 text-stone-900 dark:text-white">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-stone-400">{{ app()->getLocale() == 'en' ? 'Your Message' : 'Votre Message' }}</label>
                        <textarea required rows="5" x-model="message" class="w-full text-xs border border-stone-200 dark:border-zinc-800 rounded-xl px-4 py-3 bg-stone-50 dark:bg-zinc-950 focus:outline-none focus:border-red-500 text-stone-900 dark:text-white"></textarea>
                    </div>

                    <button type="submit" class="w-full justify-center inline-flex items-center px-6 py-3.5 bg-red-600 hover:bg-red-750 text-white font-heading font-black text-xs tracking-widest uppercase rounded-xl transition shadow-md focus:outline-none">
                        {{ app()->getLocale() == 'en' ? 'Send Message →' : 'Envoyer le Message →' }}
                    </button>
                </form>

                <!-- Alpine feedback notification -->
                <div x-show="formSubmitted" 
                     x-cloak
                     x-transition
                     class="mt-6 p-4 bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-xs rounded-xl font-bold">
                    <div class="flex items-center gap-2 text-emerald-600 font-bold"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ app()->getLocale() == 'en' ? 'Thank you! Your request has been transmitted. We will get back to you within 24 hours.' : 'Merci ! Votre demande a été transmise avec succès. Nous reviendrons vers vous sous 24 heures.' }}</div>
                </div>
            </div>

            <!-- Right: Support branches details -->
            <div class="lg:col-span-5 space-y-8">
                <!-- Location Douala -->
                <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-[2rem] p-6 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/30 flex items-center justify-center"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <h3 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-3 mb-1">{{ app()->getLocale() == 'en' ? 'Main Office - Douala' : 'Bureau Principal - Douala' }}</h3>
                    <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                        {{ app()->getLocale() == 'en' ? 'Joss Street, Akwa (SafeMarket Building), BP 4511 Douala, Cameroon.' : 'Rue Joss, Akwa (immeuble SafeMarket), BP 4511 Douala, Cameroun.' }}
                    </p>
                </div>

                <!-- Location Yaoundé -->
                <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-[2rem] p-6 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/30 flex items-center justify-center"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    <h3 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-3 mb-1">{{ app()->getLocale() == 'en' ? 'Branch Office - Yaoundé' : 'Branche - Yaoundé' }}</h3>
                    <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                        {{ app()->getLocale() == 'en' ? 'Kennedy Avenue (opposite Elysée), Yaoundé, Cameroon.' : 'Avenue Kennedy (Face Élysée), Yaoundé, Cameroun.' }}
                    </p>
                </div>

                <!-- Help details -->
                <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/30 rounded-[2rem] p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-950/40 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                        <div>
                            <p class="text-[9px] text-stone-400 uppercase font-bold tracking-widest">{{ app()->getLocale() == 'en' ? 'Hotline Phone 24/7' : 'Téléphone Hotline 24/7' }}</p>
                            <p class="text-sm font-black text-red-500">(237) 688 88 95 14</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-950/40 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                        <div>
                            <p class="text-[9px] text-stone-400 uppercase font-bold tracking-widest">{{ app()->getLocale() == 'en' ? 'Email Support' : 'Support par E-mail' }}</p>
                            <p class="text-xs text-stone-700 dark:text-zinc-300 font-bold">contact@example.com / support@safemarket.cm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-safemarket-layout>
