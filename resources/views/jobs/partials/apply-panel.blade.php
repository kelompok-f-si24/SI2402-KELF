@php($user = auth()->user())

<div class="card p-6">
    <h2 class="text-lg font-semibold text-slate-900">Lamar Pekerjaan Ini</h2>
    <p class="mt-1 text-sm text-slate-500">{{ $job->applications_count ?? $job->applications()->count() }} orang sudah melamar.</p>

    <div class="mt-5">
        @if ($existingApplication)
            <div class="rounded-xl bg-sky-50 p-4">
                <p class="text-sm font-medium text-navy-900">Anda sudah melamar pada {{ $existingApplication->created_at->translatedFormat('d M Y') }}.</p>
                <div class="mt-2"><x-badge :color="$existingApplication->status->color()">{{ $existingApplication->status->label() }}</x-badge></div>
                <a href="{{ route('seeker.applications.show', $existingApplication) }}" class="btn btn-secondary mt-4 w-full">Lihat Status Lamaran</a>
            </div>
        @elseif (! $job->isOpen())
            <div class="rounded-xl bg-slate-100 p-4 text-sm text-slate-600">
                <x-icon name="lock" class="mb-2 size-5" />
                Lowongan ini sudah ditutup dan tidak menerima lamaran baru.
            </div>
        @elseif (! $user)
            <a href="{{ route('login') }}" class="btn btn-primary w-full">Masuk untuk Melamar</a>
            <p class="mt-3 text-center text-xs text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-navy-700">Daftar gratis</a></p>
        @elseif (! $user->isJobSeeker())
            <div class="rounded-xl bg-slate-100 p-4 text-sm text-slate-600">Hanya akun Job Seeker yang dapat melamar lowongan.</div>
        @elseif (! $cv)
            <div class="rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                Unggah CV terlebih dahulu sebelum melamar pekerjaan.
            </div>
            <a href="{{ route('seeker.cv.show') }}" class="btn btn-primary mt-4 w-full"><x-icon name="upload" class="size-4" /> Upload CV</a>
        @else
            <form method="POST" action="{{ route('seeker.applications.store', $job) }}" class="space-y-4">
                @csrf
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 p-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-red-50 text-red-600"><x-icon name="document" class="size-5" /></div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-900">{{ $cv->original_name }}</p>
                        <p class="text-xs text-slate-500">CV yang akan dikirim &middot; {{ $cv->readableSize() }}</p>
                    </div>
                </div>
                <x-form.textarea name="cover_letter" label="Surat Lamaran (opsional)" rows="5" maxlength="3000"
                    placeholder="Ceritakan singkat mengapa Anda cocok untuk posisi ini." />
                <button type="button" data-modal-open="apply-modal" class="btn btn-primary w-full">Kirim Lamaran</button>

                <div id="apply-modal" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" role="dialog" aria-modal="true">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                        <h3 class="text-base font-semibold text-slate-900">Kirim lamaran ke {{ $job->company->name }}?</h3>
                        <p class="mt-1 text-sm text-slate-500">Lamaran untuk posisi <strong>{{ $job->title }}</strong> hanya dapat dikirim satu kali.</p>
                        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                            <button type="button" data-modal-close data-modal-focus class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-primary">Ya, kirim lamaran</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
