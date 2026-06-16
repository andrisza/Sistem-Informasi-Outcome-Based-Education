@extends('layouts.app')

@section('title', $user->name)
@section('header', $user->name)

@section('breadcrumb')
    <a href="{{ route('kaprodi.users.index') }}" class="hover:text-blue-600">Pengguna</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $user->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('kaprodi.users.edit', $user) }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>
@endsection

@section('content')

<div class="max-w-2xl space-y-5">

    {{-- Profil card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-5 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl font-bold uppercase shrink-0">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-1.5">
                    @php
                        $roleColor = match($user->role->value) {
                            'kaprodi'       => 'bg-blue-100 text-blue-700',
                            'tim_kurikulum' => 'bg-violet-100 text-violet-700',
                            'dosen'         => 'bg-emerald-100 text-emerald-700',
                            'mahasiswa'     => 'bg-amber-100 text-amber-700',
                            default         => 'bg-gray-100 text-gray-600',
                        };
                        $statusColor = match($user->status_aktif) {
                            'aktif'    => 'bg-green-100 text-green-700',
                            'nonaktif' => 'bg-red-100 text-red-700',
                            'cuti'     => 'bg-yellow-100 text-yellow-700',
                            default    => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                        {{ $user->role->label() }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                        {{ ucfirst($user->status_aktif) }}
                    </span>
                </div>
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Identifier</dt>
                <dd class="text-gray-800 font-mono">{{ $user->identifier ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Program Studi</dt>
                <dd class="text-gray-800">{{ $user->program_studi ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Fakultas</dt>
                <dd class="text-gray-800">{{ $user->fakultas ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Perguruan Tinggi</dt>
                <dd class="text-gray-800">{{ $user->perguruan_tinggi ?? '—' }}</dd>
            </div>
            @if ($user->isMahasiswa())
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tahun Masuk</dt>
                <dd class="text-gray-800">{{ $user->tahun_masuk ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Kelas</dt>
                <dd class="text-gray-800">{{ $user->kelas ?? '—' }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Login Terakhir</dt>
                <dd class="text-gray-800">{{ $user->last_login_at?->format('d M Y, H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Terdaftar</dt>
                <dd class="text-gray-800">{{ $user->created_at->format('d M Y') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Danger zone --}}
    <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5">
        <h4 class="text-sm font-semibold text-red-700 mb-3">Zona Berbahaya</h4>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-700">Hapus pengguna ini</p>
                <p class="text-xs text-gray-400">Data akan di-soft delete dan tidak dapat login.</p>
            </div>
            <form method="POST" action="{{ route('kaprodi.users.destroy', $user) }}"
                  onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors border border-red-200">
                    Hapus
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
