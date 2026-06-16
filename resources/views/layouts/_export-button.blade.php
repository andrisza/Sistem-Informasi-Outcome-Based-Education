<a href="{{ $route }}"
   style="display:inline-flex;align-items:center;gap:6px;background:#16A34A;color:#fff;font-size:13px;font-weight:600;padding:7px 14px;border-radius:8px;border:none;cursor:pointer;text-decoration:none">
    <svg style="width:15px;height:15px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m-8 8h10a2 2 0 002-2V8a2 2 0 00-2-2h-2.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 4H8a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    {{ $label ?? 'Export Excel' }}
</a>
