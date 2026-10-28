<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-lg sm:text-2xl text-primary leading-tight flex items-center gap-2 truncate">
                    <span class="truncate">Kelola Konten Landing Page (CMS)</span>
                    <span class="px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full bg-[#B08D57]/10 text-[#B08D57] border border-[#B08D57]/30 shrink-0">
                        Live Editor
                    </span>
                </h2>
                <p class="text-xs sm:text-sm text-secondary mt-1">
                    Sesuaikan brand, hero, tombol, dan nomor WhatsApp landing page. Statistik dan Top 5 creator terisi otomatis dari database.
                </p>
            </div>
            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-1.5 sm:px-4 sm:py-2 bg-surface border border-border text-primary rounded-xl text-xs sm:text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition shadow-sm">
                    <svg class="w-4 h-4 text-[#B08D57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Lihat Landing Page
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 shadow-sm">
            <div class="font-bold text-xs sm:text-sm mb-1">Ada kesalahan penginputan:</div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-4 sm:space-y-6">

        <!-- HERO & BRAND (satu-satunya bagian landing page saat ini) -->
        <form action="{{ route('admin.cms.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-surface border border-border rounded-2xl p-4 sm:p-6 shadow-sm space-y-4 sm:space-y-6">
            @csrf
            <input type="hidden" name="active_tab" value="hero">

            <div class="border-b border-border pb-4">
                <h3 class="text-lg font-bold text-primary">Pengaturan Hero & Brand Identity</h3>
                <p class="text-xs text-secondary">Ubah nama brand, hook utama, badge hero, subheadline, tombol CTA, dan nomor WhatsApp.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Brand Logo Upload -->
                <div class="md:col-span-2 p-5 rounded-2xl bg-body border border-border shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary flex items-center gap-2">
                                <span>Logo Brand</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#B08D57]/10 text-[#B08D57] border border-[#B08D57]/30">Upload Gambar</span>
                            </label>
                            <p class="text-xs text-secondary">
                                Upload gambar logo brand Anda (PNG transparan disarankan, JPG, SVG, WEBP maks 4MB). Logo ini akan ditampilkan di navbar, sidebar dashboard, dan halaman login.
                            </p>
                        </div>
                        <div class="flex items-center gap-4 shrink-0">
                            @php
                                $brandLogoUrl = \App\Models\LandingSetting::brandLogoUrl();
                            @endphp
                            @if($brandLogoUrl)
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-surface border border-border shadow-sm">
                                        <img src="{{ $brandLogoUrl }}" alt="Logo Brand" class="h-10 w-auto max-w-[120px] object-contain">
                                    </div>
                                    <label class="text-xs text-rose-500 hover:text-rose-700 font-bold flex items-center gap-1.5 cursor-pointer bg-rose-50 dark:bg-rose-950/40 px-3 py-1.5 rounded-lg border border-rose-200">
                                        <input type="checkbox" name="remove_brand_logo" value="1" class="rounded border-border text-rose-600 focus:ring-rose-500">
                                        Hapus Logo
                                    </label>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-surface border border-border text-xs font-semibold text-secondary">
                                    <div class="w-8 h-8 rounded-lg bg-[#0F1D31] flex items-center justify-center text-[#B08D57] font-extrabold shadow-sm">K</div>
                                    <span>Ikon "K" (Default)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-border">
                        <input type="file" name="brand_logo" accept="image/*" class="w-full text-xs text-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0F1D31] file:text-white hover:file:opacity-90 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Nama Brand</label>
                    <input type="text" name="brand_name" value="{{ $settings['brand_name'] ?? 'Kahfi Engagement' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                    <p class="text-[11px] text-secondary mt-1">Muncul di logo navbar dan judul tab browser.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Tagline Brand</label>
                    <input type="text" name="brand_tagline" value="{{ $settings['brand_tagline'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Hero Badge (Pill Hook Atas)</label>
                    <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Headline Utama Hero</label>
                    <textarea name="hero_headline" rows="2" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">{{ $settings['hero_headline'] ?? '' }}</textarea>
                    <p class="text-[11px] text-secondary mt-1">Separuh kedua kalimat otomatis tampil dengan warna lebih lembut.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Subheadline / Deskripsi Hero</label>
                    <textarea name="hero_subheadline" rows="3" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">{{ $settings['hero_subheadline'] ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Teks Tombol CTA Utama</label>
                    <input type="text" name="hero_primary_cta_text" value="{{ $settings['hero_primary_cta_text'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">URL / Link Tombol CTA Utama (WhatsApp / URL)</label>
                    <input type="text" name="hero_primary_cta_url" value="{{ $settings['hero_primary_cta_url'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                    <p class="text-[11px] text-secondary mt-1">Kosong = otomatis ke WhatsApp. Juga dipakai tombol Konsultasi di navbar.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Teks Tombol Sekunder</label>
                    <input type="text" name="hero_secondary_cta_text" value="{{ $settings['hero_secondary_cta_text'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">URL Tombol Sekunder</label>
                    <input type="text" name="hero_secondary_cta_url" value="{{ $settings['hero_secondary_cta_url'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                    <p class="text-[11px] text-secondary mt-1">Kosong = otomatis ke WhatsApp.</p>
                </div>

                <!-- Real-Time Stats Sync Notification -->
                <div class="md:col-span-2 p-4 rounded-xl bg-[#0F1D31]/5 dark:bg-[#B08D57]/10 border border-[#0F1D31]/10 dark:border-[#B08D57]/30 text-primary text-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#B08D57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold">Terisi otomatis dari database:</span> <strong>Total Views</strong>, <strong>Total Engagement</strong>, dan <strong>Top 5 Creator</strong> dihitung langsung dari data link di dashboard (diurutkan berdasarkan total views per username), sehingga tidak perlu diinput manual.
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Nomor WhatsApp Official (Format: 628xxx)</label>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '' }}" class="w-full rounded-xl border-border bg-body text-primary text-sm focus:border-[#B08D57] focus:ring-[#B08D57]">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-border">
                <button type="submit" class="px-6 py-2.5 bg-[#0F1D31] hover:bg-[#1B2F4D] text-white font-bold rounded-xl shadow-sm transition">
                    Simpan Perubahan Hero
                </button>
            </div>
        </form>

    </div>
</x-app-layout>