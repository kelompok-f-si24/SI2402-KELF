<x-layouts.public title="Temukan Pekerjaan Impian">
    <section class="relative overflow-hidden bg-navy-900">
        <div class="absolute -top-40 -right-32 size-[32rem] rounded-full bg-navy-700/50 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-20 size-[28rem] rounded-full bg-sky-400/20 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-sky-200 ring-1 ring-white/15">
                    <x-icon name="sparkles" class="size-4" /> {{ number_format($stats['jobs']) }} lowongan aktif menunggu Anda
                </span>
                <h1 class="mt-6 text-4xl leading-tight font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Temukan pekerjaan yang <span class="text-sky-300">tepat</span> untuk masa depan Anda.
                </h1>
                <p class="mt-6 max-w-2xl text-lg text-navy-200">
                    Lokerin menghubungkan pencari kerja dengan perusahaan terpercaya. Cari, lamar, dan pantau lamaran Anda dalam satu platform.
                </p>
            </div>

            <form method="GET" action="{{ route('jobs.index') }}" class="mt-10 grid gap-3 rounded-2xl bg-white p-3 shadow-2xl shadow-navy-950/40 md:grid-cols-[1fr_1fr_auto]">
                <label class="flex items-center gap-3 rounded-xl px-3 py-2 ring-1 ring-slate-200 focus-within:ring-2 focus-within:ring-navy-300">
                    <x-icon name="search" class="size-5 text-slate-400" />
                    <input type="text" name="q" maxlength="100" placeholder="Posisi atau nama perusahaan" class="w-full border-0 bg-transparent text-sm focus:outline-none">
                </label>
                <label class="flex items-center gap-3 rounded-xl px-3 py-2 ring-1 ring-slate-200 focus-within:ring-2 focus-within:ring-navy-300">
                    <x-icon name="map-pin" class="size-5 text-slate-400" />
                    <input type="text" name="location" maxlength="100" placeholder="Kota atau lokasi" class="w-full border-0 bg-transparent text-sm focus:outline-none">
                </label>
                <button type="submit" class="btn btn-primary px-8 py-3">Cari Lowongan</button>
            </form>

            <dl class="mt-12 grid max-w-2xl grid-cols-3 gap-6">
                @foreach ([['Lowongan Aktif', $stats['jobs']], ['Perusahaan', $stats['companies']], ['Pencari Kerja', $stats['seekers']]] as [$label, $value])
                    <div>
                        <dt class="text-sm text-navy-300">{{ $label }}</dt>
                        <dd class="mt-1 text-3xl font-bold text-white">{{ number_format($value) }}+</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-bold text-slate-900">Jelajahi Berdasarkan Kategori</h2>
            <p class="mt-2 text-slate-500">Pilih bidang yang sesuai dengan keahlian Anda.</p>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($categories as $item)
                <a href="{{ route('jobs.index', ['category' => $item['category']->value]) }}"
                    class="card group p-5 transition hover:-translate-y-0.5 hover:border-navy-200 hover:bg-navy-50/50">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-sky-50 text-navy-700 group-hover:bg-navy-800 group-hover:text-white">
                        <x-icon name="briefcase" class="size-5" />
                    </div>
                    <p class="mt-4 font-semibold text-slate-900">{{ $item['category']->label() }}</p>
                    <p class="text-sm text-slate-500">{{ $item['total'] }} lowongan</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Lowongan Terbaru</h2>
                    <p class="mt-2 text-slate-500">Kesempatan terbaru dari perusahaan di Lokerin.</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Lihat Semua <x-icon name="arrow-right" class="size-4" /></a>
            </div>

            @if ($latestJobs->isEmpty())
                <div class="card"><x-empty-state icon="briefcase" title="Belum ada lowongan aktif" message="Lowongan baru akan muncul di sini." /></div>
            @else
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestJobs as $job)
                        <x-job-card :job="$job" :href="route('jobs.show', $job)" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($featuredCompanies->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-slate-900">Perusahaan yang Sedang Merekrut</h2>
                <p class="mt-2 text-slate-500">Bergabunglah dengan perusahaan-perusahaan terbaik.</p>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($featuredCompanies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="card flex flex-col items-center p-6 text-center transition hover:border-navy-200 hover:shadow-md">
                        <x-avatar :src="$company->logoUrl()" :initials="$company->initials()" size="size-14" rounded="rounded-2xl" />
                        <p class="mt-3 line-clamp-1 font-semibold text-slate-900">{{ $company->name }}</p>
                        <p class="text-xs text-slate-500">{{ $company->industry }}</p>
                        <x-badge color="sky" class="mt-3">{{ $company->active_job_listings_count }} lowongan</x-badge>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl bg-sky-100 p-8 lg:p-10">
                <x-icon name="user" class="size-10 text-navy-800" />
                <h3 class="mt-4 text-2xl font-bold text-navy-900">Untuk Pencari Kerja</h3>
                <p class="mt-2 text-navy-800/80">Buat profil, unggah CV, dan lamar pekerjaan dengan sekali klik. Pantau status lamaran secara real-time.</p>
                <a href="{{ route('register') }}" class="btn btn-primary mt-6">Mulai Cari Kerja</a>
            </div>
            <div class="rounded-3xl bg-navy-900 p-8 lg:p-10">
                <x-icon name="building" class="size-10 text-sky-300" />
                <h3 class="mt-4 text-2xl font-bold text-white">Untuk Perusahaan</h3>
                <p class="mt-2 text-navy-200">Pasang lowongan, kelola pelamar, dan temukan kandidat terbaik untuk tim Anda.</p>
                <a href="{{ route('register') }}" class="btn btn-light mt-6">Pasang Lowongan</a>
            </div>
        </div>
    </section>
</x-layouts.public>
