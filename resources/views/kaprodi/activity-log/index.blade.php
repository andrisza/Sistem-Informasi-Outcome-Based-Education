@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Log Aktivitas')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Log Aktivitas</span>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kaprodi.activity-log.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari pengguna atau aksi..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="action"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Aksi</option>
            @foreach ($actions as $action)
                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                    {{ $action }}
                </option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}"
               class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="to" value="{{ request('to') }}"
               class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['search', 'action', 'from', 'to']))
            <a href="{{ route('kaprodi.activity-log.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Log List --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Model</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">IP</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ open: false }">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs font-bold uppercase">
                                {{ mb_substr($log->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-xs">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-xs text-gray-400">{{ $log->user->role?->label() ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if (str_contains($log->action, 'create') || str_contains($log->action, 'store')) bg-green-100 text-green-700
                            @elseif (str_contains($log->action, 'delete') || str_contains($log->action, 'destroy')) bg-red-100 text-red-700
                            @elseif (str_contains($log->action, 'update') || str_contains($log->action, 'edit')) bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-600
                            @endif">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">
                        <p>{{ class_basename($log->model_type) }}</p>
                        @if ($log->model_id)
                            <p class="text-gray-400 font-mono">#{{ $log->model_id }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400 font-mono">
                        {{ $log->ip_address ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">
                        <p>{{ $log->created_at->format('d M Y') }}</p>
                        <p class="text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($log->old_values || $log->new_values)
                            <button
                                onclick="toggleDetail(this)"
                                data-old="{{ json_encode($log->old_values) }}"
                                data-new="{{ json_encode($log->new_values) }}"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Detail
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Tidak ada log aktivitas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($logs->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Detail Modal --}}
<div id="detail-modal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Perubahan Data</h3>
            <button onclick="document.getElementById('detail-modal').classList.add('hidden');document.getElementById('detail-modal').classList.remove('flex')"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <p class="font-semibold text-gray-500 uppercase tracking-wider mb-2">Sebelum</p>
                <pre id="old-values" class="bg-gray-50 rounded-lg p-3 overflow-auto max-h-64 text-gray-700"></pre>
            </div>
            <div>
                <p class="font-semibold text-gray-500 uppercase tracking-wider mb-2">Sesudah</p>
                <pre id="new-values" class="bg-gray-50 rounded-lg p-3 overflow-auto max-h-64 text-gray-700"></pre>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleDetail(btn) {
    const old = JSON.parse(btn.dataset.old || 'null');
    const nw  = JSON.parse(btn.dataset.new  || 'null');
    document.getElementById('old-values').textContent = old ? JSON.stringify(old, null, 2) : '—';
    document.getElementById('new-values').textContent = nw  ? JSON.stringify(nw,  null, 2) : '—';
    const modal = document.getElementById('detail-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
</script>
@endpush

@endsection
