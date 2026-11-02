@props(['title' => null])

@php
    $user = auth()->user();

    $menus = match (true) {
        $user->isAdmin() => [
            'Monitoring' => [
                ['Dashboard', 'admin.dashboard', 'chart', 'admin.dashboard'],
            ],
            'Manajemen' => [
                ['Pengguna', 'admin.users.index', 'users', 'admin.users.*'],
                ['Perusahaan', 'admin.companies.index', 'building', 'admin.companies.*'],
                ['Lowongan', 'admin.jobs.index', 'briefcase', 'admin.jobs.*'],
            ],
        ],
        $user->isEmployer() => [
            'Utama' => [
                ['Dashboard', 'employer.dashboard', 'home', 'employer.dashboard'],
                ['Profil Perusahaan', 'employer.company.edit', 'building', 'employer.company.*'],
            ],
            'Rekrutmen' => [
                ['Buat Lowongan', 'employer.jobs.create', 'plus', 'employer.jobs.create'],
                ['Daftar Lowongan', 'employer.jobs.index', 'briefcase', ['employer.jobs.index', 'employer.jobs.edit']],
                ['Daftar Pelamar', 'employer.applicants.index', 'users', 'employer.applicants.*'],
            ],
        ],
        default => [
            'Utama' => [
                ['Dashboard', 'seeker.dashboard', 'home', 'seeker.dashboard'],
                ['Cari Lowongan', 'seeker.jobs.index', 'search', 'seeker.jobs.*'],
            ],
            'Profil Saya' => [
                ['Profil', 'seeker.profile.edit', 'user', 'seeker.profile.*'],
                ['Pendidikan', 'seeker.educations.index', 'academic', 'seeker.educations.*'],
                ['Skill', 'seeker.skills.index', 'sparkles', 'seeker.skills.*'],
                ['Pengalaman Kerja', 'seeker.experiences.index', 'briefcase', 'seeker.experiences.*'],
                ['Upload CV', 'seeker.cv.show', 'document', 'seeker.cv.*'],
            ],
            'Lamaran' => [
                ['Riwayat Lamaran', 'seeker.applications.index', 'clipboard', ['seeker.applications.index', 'seeker.applications.show']],
                ['Status Lamaran', 'seeker.applications.status', 'check-circle', 'seeker.applications.status'],
            ],
        ],
    };
@endphp

<x-layouts.base :title="$title">
    <div id="sidebar-backdrop" data-toggle="sidebar sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden"></div>

    <aside id="sidebar" data-toggle-class="-translate-x-full" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-navy-900 transition-transform lg:translate-x-0">
        <div class="flex h-16 items-center justify-between px-5">
            <x-logo light />
            <button type="button" data-toggle="sidebar sidebar-backdrop" class="rounded-lg p-1.5 text-navy-200 hover:bg-navy-800 lg:hidden" aria-label="Tutup menu">
                <x-icon name="x" class="size-5" />
            </button>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
            @foreach ($menus as $group => $items)
                <div>
                    <p class="mb-2 px-3 text-[11px] font-semibold tracking-wider text-navy-300 uppercase">{{ $group }}</p>
                    <div class="space-y-1">
                        @foreach ($items as [$label, $route, $icon, $pattern])
                            <a href="{{ route($route) }}" @class(['sidebar-link', 'is-active' => request()->routeIs($pattern)])>
                                <x-icon :name="$icon" class="size-5" />
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-navy-800 p-3">
            <button type="button" data-modal-open="logout-modal" class="sidebar-link w-full">
                <x-icon name="logout" class="size-5" />
                Logout
            </button>
        </div>
    </aside>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
            <button type="button" data-toggle="sidebar sidebar-backdrop" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
                <x-icon name="menu" class="size-6" />
            </button>

            <a href="{{ route('home') }}" class="hidden items-center gap-1 text-sm font-medium text-slate-500 hover:text-navy-700 sm:flex">
                <x-icon name="globe" class="size-4" /> Lihat Website
            </a>

            <div class="ml-auto flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $user->role->label() }}</p>
                </div>
                <x-avatar :src="$user->avatarUrl()" :initials="$user->initials()" />
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success') || session('error') || $errors->any())
                <div class="mb-6"><x-flash /></div>
            @endif
            {{ $slot }}
        </main>
    </div>

    <x-confirm-modal id="logout-modal" :action="route('logout')" title="Keluar dari Lokerin?"
        message="Sesi Anda akan diakhiri dan Anda akan diarahkan ke halaman login." confirm-label="Ya, logout" variant="primary" />
</x-layouts.base>
