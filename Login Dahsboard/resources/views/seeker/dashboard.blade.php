<x-layouts.dashboard title="Dashboard">
    <x-page-header :title="'Halo, '.$user->name.' 👋'" subtitle="Pantau lamaran dan lengkapi profil Anda agar mudah dilirik perusahaan.">
        <a href="{{ route('seeker.jobs.index') }}" class="btn btn-primary"><x-icon name="search" class="size-4" /> Cari Lowongan</a>
    </x-page-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-stat-card label="Total Lamaran" :value="$totalApplications" icon="clipboard" :href="route('seeker.applications.index')" />
        @foreach ($statuses as $status)
            <x-stat-card :label="$status->label()" :value="$statusCounts[$status->value] ?? 0" icon="check-circle"
                :tone="['amber' => 'amber', 'sky' => 'sky', 'green' => 'green', 'red' => 'red'][$status->color()]"
                :href="route('seeker.applications.status', ['status' => $status->value])" />
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">Lamaran Terbaru</h2>
                <a href="{{ route('seeker.applications.index') }}" class="text-sm font-medium text-navy-700 hover:underline">Lihat semua</a>
            </div>
            @if ($recentApplications->isEmpty())
                <x-empty-state icon="clipboard" title="Belum ada lamaran" message="Mulai cari lowongan dan kirim lamaran pertama Anda.">
                    <a href="{{ route('seeker.jobs.index') }}" class="btn btn-primary">Cari Lowongan</a>
                </x-empty-state>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($recentApplications as $application)
                        <a href="{{ route('seeker.applications.show', $application) }}" class="flex items-center gap-4 py-3 hover:bg-slate-50/60">
                            <x-avatar :src="$application->jobListing->company->logoUrl()" :initials="$application->jobListing->company->initials()" rounded="rounded-lg" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-900">{{ $application->jobListing->title }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $application->jobListing->company->name }} &middot; {{ $application->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                            <x-badge :color="$application->status->color()">{{ $application->status->label() }}</x-badge>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card p-6">
            <h2 class="font-semibold text-slate-900">Kelengkapan Profil</h2>
            <div class="mt-4 flex items-end justify-between">
                <span class="text-3xl font-bold text-navy-800">{{ $completenessPercent }}%</span>
                <span class="text-xs text-slate-500">{{ $completeness->filter()->count() }} dari {{ $completeness->count() }} bagian</span>
            </div>
            <div class="mt-2 h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-navy-700 transition-all" style="width: {{ $completenessPercent }}%"></div>
            </div>
            <ul class="mt-5 space-y-2 text-sm">
                @foreach ($completeness as $label => $done)
                    <li class="flex items-center gap-2 {{ $done ? 'text-slate-700' : 'text-slate-400' }}">
                        <x-icon :name="$done ? 'check-circle' : 'alert'" class="size-5 {{ $done ? 'text-emerald-600' : 'text-slate-300' }}" />
                        {{ $label }}
                    </li>
                @endforeach
            </ul>
            @if ($completenessPercent < 100)
                <a href="{{ route('seeker.profile.edit') }}" class="btn btn-light mt-5 w-full">Lengkapi Profil</a>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h2 class="mb-4 font-semibold text-slate-900">Rekomendasi Lowongan</h2>
        @if ($recommendedJobs->isEmpty())
            <div class="card"><x-empty-state icon="briefcase" title="Belum ada rekomendasi" message="Lowongan baru yang cocok akan muncul di sini." /></div>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($recommendedJobs as $job)
                    <x-job-card :job="$job" :href="route('seeker.jobs.show', $job)" />
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.dashboard>
