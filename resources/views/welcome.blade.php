@php
    $settings = $settings ?? (\Illuminate\Support\Facades\Schema::hasTable('landing_settings') ? \App\Models\LandingSetting::allKeyValues() : []);
    // Data real dari database (dikirim controller; fallback dihitung di sini bila belum dikirim)
    $linksReady = \Illuminate\Support\Facades\Schema::hasTable('links');
    $totalViews = $totalViews ?? ($linksReady ? (int) \App\Models\Link::sum('views') : 0);
    $totalEngagement = $totalEngagement ?? ($linksReady
        ? (int) (\App\Models\Link::sum('likes') + \App\Models\Link::sum('comments') + \App\Models\Link::sum('shares') + \App\Models\Link::sum('saves'))
        : 0);
    $topCreators = $topCreators ?? ($linksReady
        ? \App\Models\Link::selectRaw('username, COALESCE(SUM(views),0) as total_views, COALESCE(SUM(likes),0) + COALESCE(SUM(comments),0) + COALESCE(SUM(shares),0) + COALESCE(SUM(saves),0) as total_engagement, COUNT(*) as total_links')
            ->whereNotNull('username')->where('username', '!=', '')
            ->groupBy('username')->orderByDesc('total_views')->limit(5)->get()
        : collect());

    $fmt = function ($n) {
        $n = (float) $n;
        if ($n >= 1000000000) return round($n / 1000000000, 2) . 'B';
        if ($n >= 1000000) return round($n / 1000000, 2) . 'M';
        if ($n >= 1000) return round($n / 1000, 1) . 'K';
        return number_format($n, 0, ',', '.');
    };
    $maxViews = max(1, (int) ($topCreators->max('total_views') ?? 1));

    $emojiPattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE00}-\x{FE0F}\x{1F1E6}-\x{1F1FF}]/u';

    $waNumber = $settings['contact_whatsapp'] ?? '6281234567890';
    $brandName = $settings['brand_name'] ?? 'Kahfi Engagement';
    $brandLogoUrl = \App\Models\LandingSetting::brandLogoUrl();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $brandName }} - {{ $settings['brand_tagline'] ?? 'Agency Engagement & Influencer Campaign Management' }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            /*
             * Palet (satu aksen saja):
             *   latar #FAFAFA | kartu #FFFFFF | teks #18181B | teks redup #71717A
             *   garis #E4E4E7 | aksen oranye #F26A1B (gradasi tombol #FF8A2B -> #F2530A)
             * Warna ditulis langsung (bukan nama kustom) supaya selalu tampil
             * walaupun ada CSS lain (Vite) yang ikut dimuat.
             */
            body { font-family: 'Montserrat', sans-serif; background: #FAFAFA; color: #18181B; }
            .tabular { font-variant-numeric: tabular-nums; }
            .dot-grid {
                background-image: radial-gradient(circle, rgba(242, 106, 27, 0.28) 1px, transparent 1.5px);
                background-size: 22px 22px;
                -webkit-mask-image: radial-gradient(ellipse 70% 90% at 0% 0%, #000 0%, transparent 70%);
                mask-image: radial-gradient(ellipse 70% 90% at 0% 0%, #000 0%, transparent 70%);
            }
            .btn-accent { background-image: linear-gradient(to bottom, #FF8A2B, #F2530A); color: #fff; }
            .btn-accent:hover { filter: brightness(1.08); }
        </style>

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Montserrat"', 'sans-serif'] } } } }
        </script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @if($brandLogoUrl)
            <link rel="icon" type="image/png" href="{{ $brandLogoUrl }}">
        @endif
    </head>
    <body class="antialiased min-h-screen relative overflow-x-hidden selection:bg-[#F26A1B] selection:text-white">

        <div class="dot-grid pointer-events-none absolute top-0 left-0 w-full h-[520px] z-0"></div>

        <!-- ================= NAVBAR ================= -->
        <header class="sticky top-0 z-50 w-full backdrop-blur-md border-b" style="background: rgba(250,250,250,0.85); border-color: #E4E4E7;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    @if($brandLogoUrl)
                        <img src="{{ $brandLogoUrl }}" alt="{{ $brandName }}" class="h-10 w-auto max-w-[150px] object-contain rounded-lg">
                    @else
                        <div class="btn-accent w-10 h-10 rounded-xl flex items-center justify-center font-extrabold text-base">K</div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-xl font-bold tracking-tight leading-tight text-[#18181B]">{{ $brandName }}</span>
                        <span class="text-[10px] font-medium tracking-wider uppercase text-[#71717A]">Organic Growth Agency</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-accent px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-[#18181B] hover:text-[#F26A1B] transition px-3 py-2">Masuk</a>
                            <a href="{{ $settings['hero_primary_cta_url'] ?? 'https://wa.me/'.$waNumber }}" target="_blank" class="btn-accent px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition">Konsultasi</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <!-- ================= HERO ================= -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12 sm:pt-20 sm:pb-16 text-center">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white border border-[#E4E4E7] text-[#18181B] text-xs sm:text-sm font-medium mb-8">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F26A1B]"></span>
                    <span>{{ preg_replace($emojiPattern, '', $settings['hero_badge'] ?? 'Sistem Distribusi & Engagement Organik Terbesar') }}</span>
                </div>

                @php
                    $headline = $settings['hero_headline'] ?? 'Banjir Jutaan Views & Ribuan Followers Tanpa Iklan Mahal';
                    $words = explode(' ', $headline);
                    $half = max(1, (int) (count($words) * 0.5));
                @endphp
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold leading-[1.15] tracking-tight max-w-5xl mx-auto text-[#18181B]">
                    {{ implode(' ', array_slice($words, 0, $half)) }}
                    <span class="text-[#F26A1B]">{{ implode(' ', array_slice($words, $half)) }}</span>
                </h1>

                <p class="mt-6 text-sm sm:text-lg max-w-2xl mx-auto leading-relaxed text-[#71717A]">
                    {{ $settings['hero_subheadline'] ?? 'Tingkatkan brand awareness, trust, dan penjualan Anda secara eksponensial melalui jaringan ratusan kreator organik terverifikasi. Terpantau realtime dalam 1 dashboard.' }}
                </p>

                <div class="mt-9 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ $settings['hero_primary_cta_url'] ?? 'https://wa.me/'.$waNumber }}" target="_blank" class="btn-accent w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-sm font-bold shadow-[0_10px_24px_-10px_rgba(242,106,27,0.6)] transition">
                        {{ $settings['hero_primary_cta_text'] ?? 'Mulai Campaign Sekarang' }}
                    </a>
                    <a href="{{ $settings['hero_secondary_cta_url'] ?? 'https://wa.me/'.$waNumber }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-xl text-sm font-bold bg-white border border-[#D4D4D8] text-[#18181B] hover:border-[#18181B] transition">
                        {{ $settings['hero_secondary_cta_text'] ?? 'Lihat Portofolio Kami' }}
                    </a>
                </div>

                <!-- Total Views & Total Engagement (real dari database) -->
                <div class="mt-14 w-full max-w-2xl mx-auto grid grid-cols-2 gap-3 sm:gap-4 text-left">
                    <div class="rounded-2xl bg-white border border-[#E4E4E7] shadow-sm p-5 sm:p-7">
                        <div class="flex items-center gap-2 text-xs sm:text-sm font-medium text-[#71717A]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F26A1B]"></span>
                            <span>Total views</span>
                        </div>
                        <p class="tabular text-3xl sm:text-5xl font-bold mt-5 sm:mt-6 tracking-tight text-[#18181B]">{{ $fmt($totalViews) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-[#E4E4E7] shadow-sm p-5 sm:p-7">
                        <div class="flex items-center gap-2 text-xs sm:text-sm font-medium text-[#71717A]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F26A1B]"></span>
                            <span>Total engagement</span>
                        </div>
                        <p class="tabular text-3xl sm:text-5xl font-bold mt-5 sm:mt-6 tracking-tight text-[#18181B]">{{ $fmt($totalEngagement) }}</p>
                    </div>
                </div>
                <p class="text-xs mt-3 text-[#71717A]">Dihitung langsung dari seluruh konten campaign.</p>
            </section>

            <!-- ================= TOP 5 CREATOR ================= -->
            @if($topCreators->isNotEmpty())
                <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 sm:pb-32">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#18181B]">Top 5 creator</h2>
                        <p class="text-sm mt-2 text-[#71717A]">Diurutkan berdasarkan total views dari seluruh kontennya.</p>
                    </div>

                    <ol class="bg-white border border-[#E4E4E7] shadow-sm rounded-2xl overflow-hidden">
                        @foreach($topCreators as $i => $creator)
                            @php
                                $handle = ltrim($creator->username, '@');
                                $pct = max(3, round(($creator->total_views / $maxViews) * 100));
                            @endphp
                            <li class="p-4 sm:p-5 {{ $i > 0 ? 'border-t border-[#F0F0F2]' : '' }}">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <span class="w-9 h-9 rounded-full bg-[#FFF1E8] text-[#F26A1B] text-xs font-bold flex items-center justify-center shrink-0 tabular">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="w-10 h-10 rounded-full bg-[#F4F4F5] text-[#18181B] flex items-center justify-center font-bold shrink-0">
                                        {{ strtoupper(mb_substr($handle, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold truncate text-[#18181B]">{{ '@' . $handle }}</p>
                                        <p class="text-xs mt-0.5 text-[#71717A]">
                                            {{ number_format($creator->total_links, 0, ',', '.') }} konten, {{ $fmt($creator->total_engagement) }} engagement
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="tabular text-lg sm:text-xl font-bold text-[#18181B]">{{ $fmt($creator->total_views) }}</p>
                                        <p class="text-[11px] text-[#71717A]">views</p>
                                    </div>
                                </div>
                                <div class="mt-3 ml-12 sm:ml-[3.25rem] h-1 rounded-full bg-[#F0F0F2] overflow-hidden">
                                    <div class="h-full rounded-full bg-[#F26A1B]" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif
        </main>

        <!-- ================= TOMBOL KONSULTASI MELAYANG ================= -->
        <aside aria-label="WhatsApp Contact" class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50 p-1.5 rounded-full bg-white border border-[#E4E4E7] shadow-lg">
            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Admin%20{{ urlencode($brandName) }},%20saya%20tertarik%20bekerjasama" target="_blank" class="btn-accent flex items-center gap-2.5 px-5 py-3 rounded-full font-bold text-xs sm:text-sm transition">
                <span>{{ $settings['floating_wa_text'] ?? 'Konsultasi Campaign' }}</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.071.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.159.579 4.178 1.594 5.91l-1.693 6.183 6.356-1.667c1.677.915 3.599 1.438 5.643 1.438 6.627 0 12-5.373 12-12 0-6.627-5.373-12-12-12z"/></svg>
            </a>
        </aside>

    </body>
</html>