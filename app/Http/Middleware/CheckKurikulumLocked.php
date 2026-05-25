<?php

namespace App\Http\Middleware;

use App\Models\Kurikulum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKurikulumLocked
{
    /**
     * Blokir operasi tulis (POST/PUT/PATCH/DELETE) pada kurikulum berstatus 'arsip'.
     * Terapkan pada route group yang memiliki parameter {kurikulum}.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        $param = $request->route('kurikulum');

        if (!$param) {
            return $next($request);
        }

        $kurikulum = $param instanceof Kurikulum
            ? $param
            : Kurikulum::find((int) $param);

        if ($kurikulum && $kurikulum->isArsip()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Kurikulum telah diarsipkan dan tidak dapat diubah.',
                ], 403);
            }

            abort(403, 'Kurikulum telah diarsipkan dan tidak dapat diubah.');
        }

        return $next($request);
    }
}
