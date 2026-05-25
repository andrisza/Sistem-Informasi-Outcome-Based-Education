@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Pengguna</span>
@endsection

@section('header-actions')
    <a href="{{ route('kaprodi.users.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
              px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah User
    </a>
@endsection

@section('content')

{{-- Filter & Search --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kaprodi.users.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama atau email..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="role"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Peran</option>
            <option value="kaprodi"       {{ request('role') === 'kaprodi'       ? 'selected' : '' }}>Kaprodi</option>
            <option value="tim_kurikulum" {{ request('role') === 'tim_kurikulum' ? 'selected' : '' }}>Tim Kurikulum</option>
            <option value="dosen"         {{ request('role') === 'dosen'         ? 'selected' : '' }}>Dosen</option>
            <option value="mahasiswa"     {{ request('role') === 'mahasiswa'     ? 'selected' : '' }}>Mahasiswa</option>
        </select>
        <select name="status"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
            <option value="cuti"     {{ request('status') === 'cuti'     ? 'selected' : '' }}>Cuti</option>
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['search','role','status']))
            <a href="{{ route('kaprodi.users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Identifier</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Login Terakhir</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0 text-white text-xs font-bold uppercase">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 font-mono text-xs">
                        {{ $user->identifier ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $roleColor = match($user->role->value) {
                                'kaprodi'       => 'bg-blue-100 text-blue-700',
                                'tim_kurikulum' => 'bg-violet-100 text-violet-700',
                                'dosen'         => 'bg-emerald-100 text-emerald-700',
                                'mahasiswa'     => 'bg-amber-100 text-amber-700',
                                default         => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                            {{ $user->role->label() }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $statusColor = match($user->status_aktif) {
                                'aktif'    => 'bg-green-100 text-green-700',
                                'nonaktif' => 'bg-red-100 text-red-700',
                                'cuti'     => 'bg-yellow-100 text-yellow-700',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ ucfirst($user->status_aktif) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400">
                        {{ $user->last_login_at?->diffForHumans() ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('kaprodi.users.show', $user) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                                </svg>
                            </a>
                            <a href="{{ route('kaprodi.users.edit', $user) }}"
                               class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('kaprodi.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Tidak ada pengguna ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
