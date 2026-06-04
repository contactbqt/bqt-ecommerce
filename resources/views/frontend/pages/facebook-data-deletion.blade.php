<x-layouts.frontend>
    <div class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h1 class="text-4xl font-black text-slate-900 mb-4 uppercase tracking-tight">Facebook Data Deletion</h1>
                <p class="text-slate-500 font-medium mb-12">Instructions on how to request deletion of your Facebook data from {{ config('app.name') }}.</p>
                
                <div class="space-y-12">
                    <!-- Section 1 -->
                    <section>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900">How we use your data</h2>
                        </div>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            {{ config('app.name') }} uses Facebook Login to provide a seamless registration and login experience. When you log in with Facebook, we may access certain information from your Facebook profile, such as your name and email address, to create your account on our platform.
                        </p>
                    </section>

                    <!-- Section 2 -->
                    <section>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900">How to Delete Your Data</h2>
                        </div>
                        <p class="text-slate-600 mb-6 leading-relaxed text-lg">
                            If you wish to delete your activities for {{ config('app.name') }}, you can follow these steps:
                        </p>
                        <ol class="space-y-4 text-slate-600 text-lg">
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm">1</span>
                                <span>Go to your Facebook Account's <strong>Settings & Privacy</strong>. Click <strong>Settings</strong>.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm">2</span>
                                <span>Look for <strong>Apps and Websites</strong> and you will see all of the apps and websites you linked with your Facebook.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm">3</span>
                                <span>Search and click <strong>{{ config('app.name') }}</strong> in the search bar.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm">4</span>
                                <span>Scroll and click <strong>Remove</strong>.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm">5</span>
                                <span>Congratulations, you have successfully removed your app activities.</span>
                            </li>
                        </ol>
                    </section>

                    <!-- Section 3 -->
                    <section class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-900 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900">Manual Deletion Request</h2>
                        </div>
                        <p class="text-slate-600 mb-6 font-medium">
                            If you want us to manually delete all data associated with your Facebook account from our databases, please contact our support team.
                        </p>
                        <a href="mailto:{{ config('mail.from.address') }}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all active:scale-95 text-sm uppercase tracking-widest">
                            Contact Support
                        </a>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
