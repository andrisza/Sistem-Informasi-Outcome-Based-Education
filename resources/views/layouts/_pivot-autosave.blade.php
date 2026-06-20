{{-- Auto-save engine untuk semua matriks pivot (1 native checkbox per sel).
     Konvensi:
       - Setiap sel = <td.pivot-cell data-table="..." data-keys='{...}'>
       - Setiap sel berisi <input type="checkbox" .pivot-cb>
       - JS attach 'change' event langsung ke checkbox → POST ke /pivot/toggle
     Dependencies: meta[name=csrf-token] di <head>. --}}

<style>
    /* Sel matriks */
    .pivot-cell { transition: background-color 0.15s ease; }
    .pivot-cell.is-checked { background: #FEF3C7 !important; }
    .pivot-cell.is-saving  { opacity: 0.55; }
    .pivot-cell.is-error   { background: #fee2e2 !important; }

    /* Checkbox itu sendiri */
    .pivot-cb {
        width: 18px; height: 18px;
        accent-color: #b45309;     /* amber-700 */
        cursor: pointer;
        margin: 0;
    }
    .pivot-cb:disabled { cursor: not-allowed; opacity: 0.5; }

    /* Toast status */
    .pivot-autosave-status {
        position: fixed; right: 1rem; bottom: 1rem; z-index: 60;
        background: #1f2937; color: #fff;
        padding: 0.55rem 0.9rem; border-radius: 0.5rem;
        font-size: 0.75rem; font-weight: 600;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
        /* Sembunyikan sepenuhnya di luar viewport: geser tinggi penuh + jarak bottom.
           translateY(120%) tidak cukup karena ada offset bottom:1rem → menyisakan sliver. */
        transform: translateY(calc(100% + 2rem));
        opacity: 0; visibility: hidden;
        transition: transform 0.25s ease, opacity 0.25s ease, visibility 0s linear 0.25s;
        display: flex; align-items: center; gap: 0.5rem;
        pointer-events: none;
    }
    .pivot-autosave-status.is-visible {
        transform: translateY(0);
        opacity: 1; visibility: visible;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .pivot-autosave-status.is-ok      { background: #047857; }
    .pivot-autosave-status.is-derived { background: #6d28d9; }
    .pivot-autosave-status.is-error   { background: #b91c1c; }
    .pivot-autosave-status .spinner {
        width: 12px; height: 12px; border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff; border-radius: 50%; animation: pas-spin 0.7s linear infinite;
    }
    @keyframes pas-spin { to { transform: rotate(360deg); } }
</style>

<div id="pivot-autosave-status" class="pivot-autosave-status" role="status" aria-live="polite">
    <span class="indicator">●</span>
    <span class="msg">Tersimpan</span>
</div>

<script>
(function () {
    const url   = @json(route('kurikulum.pivot.toggle', $kurikulum));
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const statusEl = document.getElementById('pivot-autosave-status');
    const msgEl    = statusEl?.querySelector('.msg');
    const indEl    = statusEl?.querySelector('.indicator');

    let hideTimer = null;
    function showStatus(text, kind /* ok|derived|error|saving */) {
        if (!statusEl) return;
        statusEl.classList.remove('is-ok', 'is-derived', 'is-error');
        if (kind === 'ok')      statusEl.classList.add('is-ok');
        if (kind === 'derived') statusEl.classList.add('is-derived');
        if (kind === 'error')   statusEl.classList.add('is-error');
        if (indEl) indEl.innerHTML = (kind === 'saving') ? '<span class="spinner"></span>' : '●';
        if (msgEl) msgEl.textContent = text;
        statusEl.classList.add('is-visible');
        clearTimeout(hideTimer);
        if (kind !== 'saving') {
            hideTimer = setTimeout(() => statusEl.classList.remove('is-visible'), 1800);
        }
    }

    function paintCell(cell, checked) {
        if (!cell) return;
        cell.classList.toggle('is-checked', checked);
        cell.classList.remove('is-error');
    }
    window.__pivotPaintCell = paintCell;

    async function saveChange(cb) {
        const cell = cb.closest('.pivot-cell');
        if (!cell || !cell.dataset.table) return;

        const table  = cell.dataset.table;
        let   keys   = {};
        try { keys = JSON.parse(cell.dataset.keys || '{}'); } catch (_) {}

        const newChecked = cb.checked;
        paintCell(cell, newChecked);
        cb.disabled = true;
        cell.classList.add('is-saving');
        showStatus('Menyimpan…', 'saving');

        try {
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ table, keys, checked: newChecked }),
            });
            const json = await res.json();
            if (!res.ok || !json.ok) throw new Error(json.message || ('HTTP ' + res.status));
            showStatus(json.derived ? 'Tersimpan & matriks turunan disinkronkan' : 'Tersimpan', json.derived ? 'derived' : 'ok');
        } catch (err) {
            // Rollback
            cb.checked = !newChecked;
            paintCell(cell, cb.checked);
            cell.classList.add('is-error');
            showStatus('Gagal menyimpan: ' + err.message, 'error');
        } finally {
            cell.classList.remove('is-saving');
            cb.disabled = false;
        }
    }

    // Bind change handler to every .pivot-cb (idempotent)
    document.querySelectorAll('.pivot-cb').forEach(cb => {
        if (cb.dataset.pivotBound === '1') return;
        cb.dataset.pivotBound = '1';
        cb.addEventListener('change', () => saveChange(cb));
    });
})();
</script>
