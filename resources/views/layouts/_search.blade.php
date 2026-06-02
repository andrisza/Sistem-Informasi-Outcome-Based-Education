{{--
    Partial: reusable search/filter bar untuk pivot matrix & peta views.

    Penggunaan:
        @include('layouts._search', [
            'target'      => 'my-wrap-id',
            'placeholder' => 'Cari CPL, MK...',
            'mode'        => 'dim',          // 'dim' | 'hide'
            'rowSelector' => 'tbody tr',
        ])
--}}
@php
    $searchTarget   = $target      ?? 'obe-search-target';
    $searchPlaceholder = $placeholder ?? 'Cari kode atau deskripsi...';
    $searchMode     = $mode         ?? 'dim';
    $searchRowSel   = $rowSelector  ?? 'tbody tr';
    $searchId       = 'obe-s-' . Str::random(8);
@endphp

{{-- Search bar --}}
<div class="flex items-center gap-2 mb-3 bg-white border border-gray-200 rounded-xl px-3 py-2 shadow-sm">
    {{-- Icon --}}
    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>

    {{-- Input: pakai type="text" bukan "search" supaya tidak ada tombol × bawaan browser --}}
    <input type="text"
           id="{{ $searchId }}"
           placeholder="{{ $searchPlaceholder }}"
           autocomplete="off"
           spellcheck="false"
           class="flex-1 text-sm text-gray-800 placeholder-gray-400 bg-transparent outline-none min-w-0">

    {{-- Counter --}}
    <span id="{{ $searchId }}-count"
          class="text-xs text-gray-400 shrink-0 whitespace-nowrap hidden"></span>

    {{-- Tombol hapus (muncul saat ada teks) --}}
    <button type="button"
            id="{{ $searchId }}-clear"
            class="hidden p-0.5 text-gray-400 hover:text-gray-700 rounded transition-colors shrink-0"
            title="Hapus pencarian">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Shortcut hint --}}
    <span class="text-[10px] text-gray-300 shrink-0 hidden sm:block select-none">Ctrl+F</span>
</div>

<script>
// Search bar init — defer sampai seluruh DOM selesai diparse
(function() {
    var inputId  = {{ Js::from($searchId) }};
    var targetId = {{ Js::from($searchTarget) }};
    var rowSel   = {{ Js::from($searchRowSel) }};
    var mode     = {{ Js::from($searchMode) }};

    function init() {
        var input    = document.getElementById(inputId);
        var counter  = document.getElementById(inputId + '-count');
        var clearBtn = document.getElementById(inputId + '-clear');
        var targetEl = document.getElementById(targetId);

        if (!input) { return; }
        if (!targetEl) {
            // Retry once after a short delay (target belum dirender)
            setTimeout(init, 200);
            return;
        }

        // ── Fungsi utama pencarian ──────────────────────────────────────────
        function doSearch() {
            var q   = input.value.toLowerCase().trim();
            var all = Array.from(targetEl.querySelectorAll(rowSel));

            // Untuk card-based (data-search-card)
            var cards = Array.from(targetEl.querySelectorAll('[data-search-card]'));

            var total = 0, visible = 0;

            if (all.length > 0) {
                all.forEach(function(row) {
                    // Baris header (colspan tanpa checkbox/input) → jangan disembunyikan
                    var isGroupHeader = row.querySelector('td[colspan], th[colspan]') &&
                                        !row.querySelector('input, select');
                    if (isGroupHeader) {
                        if (mode === 'hide') row.style.display = '';
                        else row.style.opacity = '1';
                        return;
                    }

                    var text  = (row.dataset.searchText || row.textContent || '').toLowerCase();
                    var match = !q || text.indexOf(q) !== -1;
                    total++;
                    if (match) visible++;

                    if (mode === 'dim') {
                        row.style.opacity       = match ? '1' : '0.12';
                        row.style.pointerEvents = match ? '' : 'none';
                    } else {
                        row.style.display = match ? '' : 'none';
                    }
                    row.style.outline       = (q && match) ? '2px solid #fbbf24' : '';
                    row.style.outlineOffset = (q && match) ? '-2px' : '';
                });
            }

            if (cards.length > 0) {
                cards.forEach(function(card) {
                    var text  = (card.dataset.searchText || card.textContent || '').toLowerCase();
                    var match = !q || text.indexOf(q) !== -1;
                    card.style.display = match ? '' : 'none';
                    total++;
                    if (match) visible++;
                });
            }

            // Counter
            if (counter) {
                if (q) {
                    counter.textContent = visible + ' / ' + total + ' terlihat';
                    counter.className   = visible === 0
                        ? 'text-xs text-red-500 shrink-0 whitespace-nowrap'
                        : 'text-xs text-emerald-600 shrink-0 whitespace-nowrap font-medium';
                    counter.classList.remove('hidden');
                } else {
                    counter.classList.add('hidden');
                }
            }

            // Tombol clear
            if (clearBtn) {
                if (q) clearBtn.classList.remove('hidden');
                else   clearBtn.classList.add('hidden');
            }
        }

        // ── Event listeners ────────────────────────────────────────────────
        var timer;
        input.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(doSearch, 80);
        });

        // Tombol clear
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                input.value = '';
                doSearch();
                input.focus();
            });
        }

        // Ctrl+F / Cmd+F → fokus ke search box ini
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f' && !e.shiftKey) {
                var rect = input.getBoundingClientRect();
                if (rect.width > 0 && rect.height > 0) {
                    e.preventDefault();
                    input.focus();
                    input.select();
                }
            }
            if (e.key === 'Escape' && document.activeElement === input) {
                input.value = '';
                doSearch();
            }
        });
    }

    // Jalankan setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM sudah selesai diparse, tapi target mungkin belum
        setTimeout(init, 50);
    }
})();
</script>
