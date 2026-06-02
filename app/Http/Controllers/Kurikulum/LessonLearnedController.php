<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\LessonLearned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonLearnedController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $lessons = $kurikulum->lessonLearned()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $kategoriList  = $lessons->pluck('kategori')->unique()->sort()->values();
        $prioritasList = ['high', 'medium', 'low'];
        $statusList    = ['open', 'ditangani'];

        return view('kurikulum.lesson-learned.index', compact(
            'kurikulum', 'lessons', 'kategoriList', 'prioritasList', 'statusList'
        ));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kategori'    => 'required|string|max:100',
            'temuan'      => 'required|string',
            'rekomendasi' => 'required|string',
            'prioritas'   => 'required|in:high,medium,low',
        ]);

        $validated['id_kurikulum'] = $kurikulum->id;
        $validated['id_user']      = Auth::id();
        $validated['status']       = 'open';

        LessonLearned::create($validated);

        return redirect()
            ->route('kurikulum.lesson-learned.index', $kurikulum)
            ->with('success', 'Lesson Learned berhasil ditambahkan.');
    }

    public function update(Request $request, Kurikulum $kurikulum, LessonLearned $lessonLearned)
    {
        $validated = $request->validate([
            'kategori'    => 'sometimes|string|max:100',
            'temuan'      => 'sometimes|string',
            'rekomendasi' => 'sometimes|string',
            'prioritas'   => 'sometimes|in:high,medium,low',
            'status'      => 'sometimes|in:open,ditangani',
        ]);

        $lessonLearned->update($validated);

        return redirect()
            ->route('kurikulum.lesson-learned.index', $kurikulum)
            ->with('success', 'Lesson Learned berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, LessonLearned $lessonLearned)
    {
        $lessonLearned->delete();

        return redirect()
            ->route('kurikulum.lesson-learned.index', $kurikulum)
            ->with('success', 'Lesson Learned berhasil dihapus.');
    }
}
