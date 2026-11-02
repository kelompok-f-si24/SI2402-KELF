@props(['title' => null, 'heading', 'subheading' => null])

<x-layouts.base :title="$title" body-class="bg-white">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-navy-900 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <div class="absolute -top-24 -right-24 size-96 rounded-full bg-navy-700/40 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-16 size-96 rounded-full bg-sky-400/20 blur-3xl"></div>

            <x-logo light class="relative" />

            <div class="relative">
                <h2 class="text-4xl leading-tight font-bold text-white">Langkah pertama menuju karier impian Anda.</h2>
                <p class="mt-4 max-w-md text-navy-200">Ribuan lowongan dari perusahaan terpercaya menunggu Anda. Daftar gratis dan mulai melamar hari ini.</p>

                <div class="mt-10 grid max-w-md grid-cols-3 gap-4">
                    @foreach ([['Lowongan', 'briefcase'], ['Perusahaan', 'building'], ['Kandidat', 'users']] as [$label, $icon])
                        <div class="rounded-xl bg-white/5 p-4 ring-1 ring-white/10">
                            <x-icon :name="$icon" class="size-6 text-sky-300" />
                            <p class="mt-2 text-sm font-medium text-white">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="relative text-xs text-navy-300">&copy; {{ date('Y') }} LOKERIN</p>
        </div>

        <div class="flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">
                <x-logo class="mb-8 lg:hidden" />
                <h1 class="text-2xl font-bold text-slate-900">{{ $heading }}</h1>
                @if ($subheading)
                    <p class="mt-1 text-sm text-slate-500">{{ $subheading }}</p>
                @endif

                @if (session('success') || session('error'))
                    <div class="mt-6"><x-flash /></div>
                @endif

                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
