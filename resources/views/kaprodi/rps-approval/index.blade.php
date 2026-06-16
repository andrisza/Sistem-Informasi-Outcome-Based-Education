@extends('layouts.app')

@section('title', 'Persetujuan RPS')
@section('header', 'Persetujuan RPS')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Persetujuan RPS</span>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-4">
    <form method="GET" action="{{ route('kaprodi.rps-approval.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari mata kuliah atau dosen..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="semester"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Semester</option>
            @foreach ($semesters as $sem)
                <option value="{{ $sem->id }}" {{ request('semester') == $sem->id ? 'selected' : '' }}>
                    {{ $sem->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['search', 'semester']))
            <a href="{{ route('kaprodi.rps-approval.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Bulk action bar (hidden until selection) --}}
<div id="bulk-bar"
     style="display:none;position:sticky;top:0;z-index:30;margin-bottom:12px;background:#1E293B;color:#fff;border-radius:12px;padding:10px 16px;align-items:center;gap:12px;flex-wrap:wrap;box-shadow:0 4px 16px rgba(0,0,0,.18)">
    <span style="font-size:13px;font-weight:600">
        <span id="bulk-count">0</span> RPS dipilih
    </span>
    <div style="flex:1"></div>
    <form method="POST" action="{{ route('kaprodi.rps-approval.batch-approve') }}" id="form-batch-approve" style="display:inline">
        @csrf
        <input type="hidden" name="ids" id="ids-approve">
        <button type="submit"
                style="background:#16A34A;color:#fff;font-size:13px;font-weight:600;padding:7px 16px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px"
                onclick="return fillIds('ids-approve') && confirm('Sahkan semua RPS yang dipilih?')">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Sahkan Semua
        </button>
    </form>
    <form method="POST" action="{{ route('kaprodi.rps-approval.batch-draft') }}" id="form-batch-draft" style="display:inline">
        @csrf
        <input type="hidden" name="ids" id="ids-draft">
        <button type="submit"
                style="background:#64748B;color:#fff;font-size:13px;font-weight:600;padding:7px 16px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px"
                onclick="return fillIds('ids-draft') && confirm('Kembalikan semua RPS yang dipilih ke draft?')">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Ke Draft
        </button>
    </form>
    <button type="button" onclick="clearAll()"
            style="background:transparent;color:#94A3B8;font-size:12px;padding:6px 10px;border:1px solid #475569;border-radius:8px;cursor:pointer">
        Batal
    </button>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm" id="rps-table">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-4 py-3 w-10">
                    <input type="checkbox" id="select-all" title="Pilih semua"
                           style="width:16px;height:16px;cursor:pointer;accent-color:#2563EB">
                </th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen Pengembang</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Penyusunan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($rpsList as $rps)
                <tr class="hover:bg-gray-50 transition-colors rps-row" data-id="{{ $rps->id }}">
                    <td class="px-4 py-3 text-center">
                        <input type="checkbox" class="row-cb"
                               value="{{ $rps->id }}"
                               style="width:16px;height:16px;cursor:pointer;accent-color:#2563EB">
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800">{{ $rps->mataKuliah->nama_mk ?? '—' }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $rps->mataKuliah->kode_mk ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-gray-700">{{ $rps->dosenPengembang->name ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $rps->semester->nama ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-gray-600 text-xs">
                        {{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $sc = match($rps->status) {
                                'draft'    => 'background:#F3F4F6;color:#4B5563',
                                'review'   => 'background:#FEF3C7;color:#92400E',
                                'disahkan' => 'background:#D1FAE5;color:#065F46',
                                default    => 'background:#F3F4F6;color:#6B7280',
                            };
                        @endphp
                        <span style="{{ $sc }};display:inline-flex;align-items:center;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600">
                            {{ ucfirst($rps->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('kaprodi.rps-approval.show', $rps) }}"
                           class="inline-flex items-center gap-1.5 text-xs bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                            </svg>
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Tidak ada RPS yang menunggu persetujuan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($rpsList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $rpsList->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
(function () {
    const selectAll = document.getElementById('select-all');
    const bulkBar   = document.getElementById('bulk-bar');
    const countEl   = document.getElementById('bulk-count');

    function getChecked() {
        return Array.from(document.querySelectorAll('.row-cb:checked'));
    }

    function updateBar() {
        const checked = getChecked();
        if (checked.length > 0) {
            bulkBar.style.display = 'flex';
            countEl.textContent   = checked.length;
        } else {
            bulkBar.style.display = 'none';
        }
        // Update select-all indeterminate state
        const all  = document.querySelectorAll('.row-cb').length;
        selectAll.indeterminate = checked.length > 0 && checked.length < all;
        selectAll.checked       = all > 0 && checked.length === all;
    }

    // Select-all toggle
    selectAll && selectAll.addEventListener('change', function () {
        document.querySelectorAll('.row-cb').forEach(cb => { cb.checked = this.checked; });
        updateBar();
    });

    // Individual checkbox
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-cb')) updateBar();
    });

    // Click row (except checkbox/link/button cells) to toggle
    document.querySelectorAll('.rps-row').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a,button,input')) return;
            const cb = row.querySelector('.row-cb');
            if (cb) { cb.checked = !cb.checked; updateBar(); }
        });
    });

    // Fill hidden ids field before submit
    window.fillIds = function (fieldId) {
        const ids = getChecked().map(cb => cb.value).join(',');
        if (!ids) { alert('Pilih minimal satu RPS.'); return false; }
        document.getElementById(fieldId).value = ids;
        return true;
    };

    // Clear all selections
    window.clearAll = function () {
        document.querySelectorAll('.row-cb').forEach(cb => { cb.checked = false; });
        if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
        bulkBar.style.display = 'none';
    };
})();
</script>
@endpush
