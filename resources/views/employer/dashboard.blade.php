<x-layouts.dashboard title="Dashboard Employer">
    @if (! $company)
        <div class="card">
            <x-empty-state icon="building" title="Profil perusahaan belum dibuat" message="Lengkapi profil perusahaan terlebih dahulu agar dapat mempublikasikan lowongan.">
                <a href="{{ route('employer.company.edit') }}" class="btn btn-primary">Buat Profil Perusahaan</a>
            </x-empty-state>
        </div>
    @else
        <x-page-header :title="$company->name" subtitle="Ringkasan aktivitas rekrutmen perusahaan Anda.">
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary"><x-icon name="plus" class="size-4" /> Buat Lowongan</a>
        </x-page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="Lowongan Aktif" :value="$activeJobs" icon="briefcase" tone="green" :href="route('employer.jobs.index', ['status' => 'active'])" />
            <x-stat-card label="Lowongan Ditutup" :value="$closedJobs" icon="lock" tone="gray" :href="route('employer.jobs.index', ['status' => 'closed'])" />
            <x-stat-card label="Total Pelamar" :value="$totalApplicants" icon="users" :href="route('employer.applicants.index')" />
            <x-stat-card label="Menunggu Ditinjau" :value="$pendingApplicants" icon="clock" tone="amber" :href="route('employer.applicants.index', ['status' => 'pending'])" />
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="card p-6 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Pelamar Terbaru</h2>
                    <a href="{{ route('employer.applicants.index') }}" class="text-sm font-medium text-navy-700 hover:underline">Lihat semua</a>
                </div>
                @if ($recentApplicants->isEmpty())
                    <x-empty-state icon="users" title="Belum ada pelamar" message="Pelamar untuk lowongan Anda akan muncul di sini." />
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($recentApplicants as $application)
                            <a href="{{ route('employer.applicants.show', $application) }}" class="flex items-center gap-4 py-3 hover:bg-slate-50/60">
                                <x-avatar :src="$application->user->avatarUrl()" :initials="$application->user->initials()" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-slate-900">{{ $application->user->name }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $application->jobListing->title }} &middot; {{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <x-badge :color="$application->status->color()">{{ $application->status->label() }}</x-badge>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="card p-6">
                    <h2 class="mb-4 font-semibold text-slate-900">Status Pelamar</h2>
                    <div class="space-y-3">
                        @foreach ($statuses as $status)
                            @php($count = $statusCounts[$status->value] ?? 0)
                            <div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">{{ $status->label() }}</span>
                                    <span class="font-semibold text-slate-900">{{ $count }}</span>
                                </div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full {{ ['amber' => 'bg-amber-400', 'sky' => 'bg-sky-500', 'green' => 'bg-emerald-500', 'red' => 'bg-red-500'][$status->color()] }}"
                                        style="width: {{ $totalApplicants ? round($count / $totalApplicants * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="mb-4 font-semibold text-slate-900">Lowongan Terpopuler</h2>
                    @forelse ($topJobs as $job)
                        <div class="flex items-center justify-between gap-3 py-2 text-sm">
                            <span class="truncate text-slate-700">{{ $job->title }}</span>
                            <x-badge color="navy">{{ $job->applications_count }} pelamar</x-badge>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada lowongan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</x-layouts.dashboard>
