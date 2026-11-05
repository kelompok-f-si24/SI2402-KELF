@props(['title' => null, 'bodyClass' => 'bg-slate-50'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lokerin - platform pencarian lowongan kerja yang menghubungkan pencari kerja dengan perusahaan.">
    <title>{{ $title ? $title.' - ' : '' }}LOKERIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans text-slate-800 antialiased {{ $bodyClass }}">
    {{ $slot }}
</body>
</html>
