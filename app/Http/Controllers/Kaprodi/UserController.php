<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->orderBy('name');

        if ($search = $request->search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                                       ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($role = $request->role) {
            $query->where('role', $role);
        }

        if ($status = $request->status) {
            $query->where('status_aktif', $status);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('kaprodi.users.index', compact('users'));
    }

    public function create()
    {
        return view('kaprodi.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', Password::min(8)],
            'role'          => ['required', Rule::in(array_column(Role::cases(), 'value'))],
            'identifier'    => ['nullable', 'string', 'max:30'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'tahun_masuk'   => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'kelas'         => ['nullable', 'string', 'max:20'],
            'status_aktif'  => ['nullable', Rule::in(['aktif', 'nonaktif', 'cuti'])],
        ]);

        $data['password']     = Hash::make($data['password']);
        $data['status_aktif'] = $data['status_aktif'] ?? 'aktif';

        $user = User::create($data);

        ActivityLog::record('create', User::class, $user->id, [], $user->only(['name', 'email', 'role']));

        return redirect()->route('kaprodi.users.show', $user)
                         ->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        return view('kaprodi.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('kaprodi.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => ['nullable', Password::min(8)],
            'role'          => ['required', Rule::in(array_column(Role::cases(), 'value'))],
            'identifier'    => ['nullable', 'string', 'max:30'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'tahun_masuk'   => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'kelas'         => ['nullable', 'string', 'max:20'],
            'status_aktif'  => ['nullable', Rule::in(['aktif', 'nonaktif', 'cuti'])],
        ]);

        $old = $user->only(['name', 'email', 'role', 'status_aktif']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        ActivityLog::record('update', User::class, $user->id, $old, $user->fresh()->only(['name', 'email', 'role', 'status_aktif']));

        return redirect()->route('kaprodi.users.show', $user)
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        ActivityLog::record('delete', User::class, $user->id, $user->only(['name', 'email', 'role']));

        $user->delete();

        return redirect()->route('kaprodi.users.index')
                         ->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }

    public function toggleStatus(User $user)
    {
        $old = $user->status_aktif;
        $user->update([
            'status_aktif' => $old === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        ActivityLog::record('toggle_status', User::class, $user->id, ['status_aktif' => $old], ['status_aktif' => $user->status_aktif]);

        return back()->with('success', 'Status pengguna diperbarui.');
    }
}
