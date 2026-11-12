@php($companyRoute ??= null)

<div class="space-y-6">
    <div class="card p-6">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
            <x-avatar :src="$job->company->logoUrl()" :initials="$job->company->initials()" size="size-16" rounded="rounded-2xl" />
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $job->title }}</h1>
                    <x-badge :color="$job->isOpen() ? 'green' : 'gray'">{{ $job->isOpen() ? 'Aktif' : 'Ditutup' }}</x-badge>
                </div>
                <p class="mt-1 flex items-center gap-1 text-slate-600">
                    @if ($companyRoute)
                        <a href="{{ $companyRoute }}" class="font-medium hover:text-navy-700 hover:underline">{{ $job->company->name }}</a>
                    @else
                        <span class="font-medium">{{ $job->company->name }}</span>
                    @endif
                    @if ($job->company->is_verified)
                        <x-icon name="badge-check" class="size-5 text-sky-500" />
                    @endif
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <x-badge color="navy">{{ $job->employment_type->label() }}</x-badge>
                    <x-badge color="sky">{{ $job->work_system->label() }}</x-badge>
                    <x-badge color="gray">{{ $job->category->label() }}</x-badge>
                </div>
            </div>
        </div>

        <dl class="mt-6 grid gap-4 border-t border-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['map-pin', 'Lokasi', $job->location],
                ['currency', 'Gaji', $job->salaryRange()],
                ['calendar', 'Dipublikasikan', $job->published_at?->translatedFormat('d M Y') ?? '-'],
                ['clock', 'Batas Lamaran', $job->deadline->translatedFormat('d M Y')],
            ] as [$icon, $label, $value])
                <div class="flex items-start gap-3">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-navy-700"><x-icon :name="$icon" class="size-5" /></div>
                    <div>
                        <dt class="text-xs text-slate-500">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-slate-900">{{ $value }}</dd>
                    </div>
                </div>
            @endforeach
        </dl>
    </div>

    @foreach (['Deskripsi Pekerjaan' => $job->description, 'Tanggung Jawab' => $job->responsibilities, 'Kualifikasi' => $job->qualifications] as $heading => $content)
        <div class="card p-6">
            <h2 class="mb-3 text-lg font-semibold text-slate-900">{{ $heading }}</h2>
            @if ($heading === 'Deskripsi Pekerjaan')
                <p class="text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ $content }}</p>
            @else
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach (array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $content))) as $line)
                        <li class="flex gap-2"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-emerald-600" /> {{ ltrim($line, '-•* ') }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach

    <div class="card p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Tentang Perusahaan</h2>
        <div class="flex items-center gap-3">
            <x-avatar :src="$job->company->logoUrl()" :initials="$job->company->initials()" size="size-12" rounded="rounded-xl" />
            <div>
                <p class="font-semibold text-slate-900">{{ $job->company->name }}</p>
                <p class="text-sm text-slate-500">{{ $job->company->industry }} &middot; {{ $job->company->address }}</p>
            </div>
        </div>
        <p class="mt-4 line-clamp-4 text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ $job->company->description }}</p>
        @if ($companyRoute)
            <a href="{{ $companyRoute }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-navy-700 hover:underline">
                Lihat profil perusahaan <x-icon name="arrow-right" class="size-4" />
            </a>
        @endif
    </div>
</div>
