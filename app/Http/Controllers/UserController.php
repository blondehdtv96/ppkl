<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = ['admin', 'siswa', 'wali_kelas', 'bp', 'kaprog', 'tu', 'hubin'];
        
        // Menghitung statistik pengguna
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'siswa' => User::where('role', 'siswa')->count()
        ];

        return view('users.index', compact('users', 'roles', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = ['admin', 'siswa', 'wali_kelas', 'bp', 'kaprog', 'tu', 'hubin'];
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,siswa,wali_kelas,bp,kaprog,tu,hubin',
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:255|unique:users',
            'is_active' => 'boolean',
            'kelas_diampu' => 'nullable|array',
            'jurusan_diampu' => 'nullable|array',
            'custom_kelas_diampu' => 'nullable|string|max:255',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nis' => $request->nis,
            'is_active' => $request->boolean('is_active', true),
        ];
        
        // Tambahkan kelas_diampu jika role adalah wali_kelas
        if ($request->role === 'wali_kelas') {
            $userData['kelas_diampu'] = $request->kelas_diampu ?? [];
            $userData['custom_kelas_diampu'] = $request->custom_kelas_diampu;
        }
        
        // Tambahkan jurusan_diampu jika role adalah kaprog
        if ($request->role === 'kaprog') {
            $userData['jurusan_diampu'] = $request->jurusan_diampu ?? [];
        }
        
        User::create($userData);

        return redirect()->route('users.index')
                       ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load(['permohonanPkl', 'notifikasi']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $roles = ['admin', 'siswa', 'wali_kelas', 'bp', 'kaprog', 'tu', 'hubin'];
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,siswa,wali_kelas,bp,kaprog,tu,hubin',
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'nis' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'is_active' => 'boolean',
            'kelas_diampu' => 'nullable|array',
            'jurusan_diampu' => 'nullable|array',
            'custom_kelas_diampu' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nis' => $request->nis,
            'is_active' => $request->boolean('is_active', true),
        ];
        
        // Tambahkan kelas_diampu jika role adalah wali_kelas
        if ($request->role === 'wali_kelas') {
            $data['kelas_diampu'] = $request->kelas_diampu ?? [];
            $data['custom_kelas_diampu'] = $request->custom_kelas_diampu;
        } else {
            $data['kelas_diampu'] = null;
            $data['custom_kelas_diampu'] = null;
        }
        
        // Tambahkan jurusan_diampu jika role adalah kaprog
        if ($request->role === 'kaprog') {
            $data['jurusan_diampu'] = $request->jurusan_diampu ?? [];
        } else {
            $data['jurusan_diampu'] = null;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
                       ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Cek apakah user memiliki permohonan PKL
        if ($user->permohonanPkl()->count() > 0) {
            return redirect()->route('users.index')
                           ->with('error', 'Tidak dapat menghapus pengguna yang memiliki permohonan PKL.');
        }

        $user->delete();

        return redirect()->route('users.index')
                       ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('users.index')
                       ->with('success', "Pengguna berhasil {$status}.");
    }

    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'nis' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'kelas_diampu' => 'nullable|array',
            'jurusan_diampu' => 'nullable|array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nis' => $request->nis,
        ];
        
        // Tambahkan kelas_diampu jika role adalah wali_kelas
        if ($user->isWaliKelas()) {
            $data['kelas_diampu'] = $request->kelas_diampu ?? [];
        }
        
        // Tambahkan jurusan_diampu jika role adalah kaprog
        if ($user->isKaprog()) {
            $data['jurusan_diampu'] = $request->jurusan_diampu ?? [];
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.profile')
                       ->with('success', 'Profil berhasil diperbarui.');
    }
}