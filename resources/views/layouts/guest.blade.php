<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') — SI-OBE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 mb-4">
                <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9-5 9 5-9 5zm0-14v7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">SI-OBE</h1>
            <p class="text-slate-400 text-sm mt-1">Sistem Informasi Outcome-Based Education</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @yield('content')
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; {{ date('Y') }} Program Studi Sistem Informasi — LAMINFOKOM
        </p>
    </div>

</body>
</html>
