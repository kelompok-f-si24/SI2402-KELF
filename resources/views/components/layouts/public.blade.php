@props(['title' => null])

<x-layouts.base :title="$title" body-class="bg-white">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <x-logo />

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" @class(['text-sm font-medium transition hover:text-navy-700', 'text-navy-800' => request()->routeIs('home'), 'text-slate-600' => ! request()->routeIs('home')])>Beranda</a>
                <a href="{{ route('jobs.index') }}" @class(['text-sm font-medium transition hover:text-navy-700', 'text-navy-800' => request()->routeIs('jobs.*'), 'text-slate-600' => ! request()->routeIs('jobs.*')])>Lowongan</a>
                <a href="{{ route('companies.index') }}" @class(['text-sm font-medium transition hover:text-navy-700', 'text-navy-800' => request()->routeIs('companies.*'), 'text-slate-600' => ! request()->routeIs('companies.*')])>Perusahaan</a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>

            <button type="button" data-toggle="mobile-nav" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Buka menu">
                <x-icon name="menu" class="size-6" />
            </button>
        </nav>

        <div id="mobile-nav" class="hidden border-t border-slate-200 bg-white px-4 py-4 md:hidden">
            <div class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Beranda</a>
                <a href="{{ route('jobs.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Lowongan</a>
                <a href="{{ route('companies.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Perusahaan</a>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary col-span-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    @if (session('success') || session('error'))
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <x-flash />
        </div>
    @endif

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-20 bg-navy-950 text-navy-100">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <x-logo light />
                <p class="mt-4 max-w-sm text-sm text-navy-200">
                    Lokerin menghubungkan pencari kerja dengan perusahaan terbaik di Indonesia. Temukan karier impian Anda atau rekrut talenta terbaik dengan mudah.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white">Pencari Kerja</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white">Cari Lowongan</a></li>
                    <li><a href="{{ route('companies.index') }}" class="hover:text-white">Daftar Perusahaan</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Buat Akun</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white">Perusahaan</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('register') }}" class="hover:text-white">Pasang Lowongan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Masuk Employer</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-navy-900 py-6 text-center text-xs text-navy-300">
            &copy; {{ date('Y') }} LOKERIN. Seluruh hak cipta dilindungi.
        </div>
    </footer>
</x-layouts.base>
