{{-- Shared: floating tooltip for data-tooltip attributes in pivot/table views --}}
<script>
(function () {
    if (document.getElementById('obe-tip')) return;
    var tip = document.createElement('div');
    tip.id = 'obe-tip';
    tip.style.cssText = [
        'position:fixed','z-index:9999','max-width:340px',
        'background:#1e293b','color:#f1f5f9',
        'font-size:0.72rem','line-height:1.6',
        'padding:9px 13px','border-radius:8px',
        'pointer-events:none','opacity:0',
        'transition:opacity .1s ease','box-shadow:0 8px 24px rgba(0,0,0,.45)',
        'display:none','word-break:break-word',
        'border:1px solid rgba(255,255,255,.08)'
    ].join(';');
    document.body.appendChild(tip);

    var lastEl = null;
    document.addEventListener('mouseover', function (e) {
        var el = e.target.closest('[data-tooltip]');
        if (el && el !== lastEl) {
            lastEl = el;
            tip.innerHTML = '<span style="opacity:.6;font-size:.65rem;letter-spacing:.05em;text-transform:uppercase">'
                + (el.dataset.tipLabel || '') + '</span>'
                + (el.dataset.tipLabel ? '<br>' : '')
                + el.getAttribute('data-tooltip');
            tip.style.display = 'block';
            requestAnimationFrame(function () { tip.style.opacity = '1'; });
        } else if (!el) {
            lastEl = null;
            tip.style.opacity = '0';
            setTimeout(function () { if (tip.style.opacity === '0') tip.style.display = 'none'; }, 120);
        }
    });
    document.addEventListener('mousemove', function (e) {
        if (tip.style.display === 'none') return;
        var x = e.clientX + 16, y = e.clientY - 10;
        var w = tip.offsetWidth, h = tip.offsetHeight;
        if (x + w > window.innerWidth - 8)  x = e.clientX - w - 10;
        if (y + h > window.innerHeight - 8) y = e.clientY - h - 10;
        tip.style.left = x + 'px';
        tip.style.top  = y + 'px';
    });
})();
</script>
