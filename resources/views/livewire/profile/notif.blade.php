<div class="space-y-3">
    @if(empty($notifications))
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <span class="rounded-full bg-slate-50 p-3.5 text-slate-400 mb-2 border border-slate-100/50">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a9.049 9.049 0 0 1-5.137-4.89M9 11.25H21m-6-6v6" />
                </svg>
            </span>
            <h4 class="text-xs font-semibold text-slate-700">Tidak ada notifikasi</h4>
            <p class="text-3xs text-slate-400 max-w-xs mt-0.5">Anda akan menerima pemberitahuan saat ada aktivitas baru.</p>
        </div>
    @else
        <div class="flex flex-col gap-2.5">
            @foreach($notifications as $notif)
                @php
                    $colors = match($notif['type']) {
                        'success' => ['bg' => 'bg-emerald-50/60', 'border' => 'border-emerald-100/70', 'text' => 'text-emerald-700', 'iconBg' => 'bg-emerald-500'],
                        'warning' => ['bg' => 'bg-amber-50/60', 'border' => 'border-amber-100/70', 'text' => 'text-amber-700', 'iconBg' => 'bg-amber-500'],
                        'danger' => ['bg' => 'bg-rose-50/60', 'border' => 'border-rose-100/70', 'text' => 'text-rose-700', 'iconBg' => 'bg-rose-500'],
                        default => ['bg' => 'bg-indigo-50/60', 'border' => 'border-indigo-100/70', 'text' => 'text-indigo-700', 'iconBg' => 'bg-indigo-500'],
                    };
                @endphp
                <div class="relative overflow-hidden rounded-xl border {{ $colors['border'] }} {{ $colors['bg'] }} p-3 shadow-3xs transition-all duration-200 hover:shadow-2xs">
                    <div class="flex gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $colors['iconBg'] }} text-white shadow-3xs">
                            @if(str_contains($notif['icon'], 'calendar'))
                                <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            @elseif(str_contains($notif['icon'], 'check'))
                                <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif(str_contains($notif['icon'], 'x'))
                                <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif(str_contains($notif['icon'], 'tool'))
                                <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 0019.5 21l2-2a1.5 1.5 0 000-2.25l-5.83-5.83M11.42 15.17a4.996 4.996 0 01-7.072 0 4.996 4.996 0 010-7.072 4.996 4.996 0 017.072 0 4.996 4.996 0 010 7.072zM11.42 15.17L12 14.5" />
                                </svg>
                            @else
                                <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.086 1.086L12 12.75l-.041.02a.75.75 0 11-1.086-1.086l.041-.02a.75.75 0 011.086 0zM12 21a9 9 0 100-18 9 9 0 000 18z" />
                                </svg>
                            @endif
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col">
                                <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $notif['title'] }}</h4>
                                <span class="text-4xs text-slate-400 mt-0.5">{{ $notif['time'] }}</span>
                            </div>
                            <p class="mt-1 text-2xs text-slate-600 leading-relaxed">{{ $notif['message'] }}</p>
                            @if(isset($notif['route']) && Route::has($notif['route']))
                                <a href="{{ route($notif['route']) }}" class="mt-2 inline-flex items-center gap-0.5 text-3xs font-bold {{ $colors['text'] }} hover:underline">
                                    Buka Halaman
                                    <svg class="h-2.5 w-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
