<div id="launchModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-gray-900 p-8 text-center shadow-2xl overflow-hidden">

        <div id="launchStage" class="relative mx-auto mb-6 h-48 w-full overflow-hidden rounded-xl bg-gradient-to-b from-indigo-950 via-indigo-900 to-indigo-800">
            <div class="stars absolute inset-0"></div>

            <div id="rocket" class="absolute left-1/2 bottom-6 -translate-x-1/2 transition-transform duration-[1600ms] ease-in">
                <svg width="40" height="64" viewBox="0 0 40 64" fill="none">
                    <path d="M20 0C20 0 32 14 32 34C32 42 28 48 28 48H12C12 48 8 42 8 34C8 14 20 0 20 0Z" fill="#E5E7EB"/>
                    <path d="M20 4C20 4 29 15 29 32H11C11 15 20 4 20 4Z" fill="#818CF8"/>
                    <circle cx="20" cy="24" r="5" fill="#4F46E5"/>
                    <path d="M8 34L2 48H10L8 34Z" fill="#F87171"/>
                    <path d="M32 34L38 48H30L32 34Z" fill="#F87171"/>
                    <path d="M14 48H26L24 58H16L14 48Z" fill="#D1D5DB"/>
                </svg>
                <div id="flame" class="mx-auto -mt-1 h-0 w-3 rounded-b-full bg-gradient-to-b from-amber-300 via-orange-400 to-transparent opacity-0 transition-all duration-300"></div>
            </div>

            <div id="successBadge" class="absolute inset-0 hidden items-center justify-center opacity-0 transition-opacity duration-500">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-emerald-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
            </div>
        </div>

        <p id="launchTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Launching your campaign…</p>
        <p id="launchSubtitle" class="mt-1 text-sm text-gray-500 dark:text-gray-400">Setting up your discount</p>
    </div>
</div>
