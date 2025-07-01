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
        
        // Tambahkan custom_kelas_diampu jika role adalah wali_kelas
        if ($request->role === 'wali_kelas') {
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
        
        // Tambahkan custom_kelas_diampu jika role adalah wali_kelas
        if ($request->role === 'wali_kelas') {
            $data['custom_kelas_diampu'] = $request->custom_kelas_diampu;
        } else {
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
            'jurusan_diampu' => 'nullable|array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nis' => $request->nis,
        ];
        

        
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

    public function export() 
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::all();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = ['id', 'name', 'email', 'role', 'kelas', 'jurusan', 'nis', 'is_active', 'jurusan_diampu', 'custom_kelas_diampu', 'created_at', 'updated_at'];
        
        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($users as $user) {
                $row = [];
                foreach ($columns as $column) {
                    if ($column == 'jurusan_diampu') {
                        $row[] = $user->$column ? json_encode($user->$column) : '';
                    } else if ($column == 'is_active') {
                        $row[] = $user->$column ? '1' : '0';
                    } else {
                        $row[] = $user->$column;
                    }
                }
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function import(Request $request)
    {
        $this->authorize('create', User::class);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        $headers = array_shift($data);
        
        $importCount = 0;
        $updateCount = 0;
        $errorCount = 0;
        
        foreach ($data as $row) {
            $userData = array_combine($headers, $row);
            
            try {
                // Jika ada ID dan user dengan ID tersebut ada, update user
                if (!empty($userData['id']) && User::find($userData['id'])) {
                    $user = User::find($userData['id']);
                    
                    // Jangan update password dari CSV
                    unset($userData['password']);
                    
                    // Konversi array yang disimpan sebagai JSON string
                    
                    if (isset($userData['jurusan_diampu']) && !empty($userData['jurusan_diampu']) && is_string($userData['jurusan_diampu'])) {
                        $userData['jurusan_diampu'] = json_decode($userData['jurusan_diampu'], true);
                    }
                    
                    // Konversi is_active dari string ke boolean
                    if (isset($userData['is_active'])) {
                        $userData['is_active'] = filter_var($userData['is_active'], FILTER_VALIDATE_BOOLEAN);
                    }
                    
                    $user->update($userData);
                    $updateCount++;
                } 
                // Jika tidak ada ID atau user dengan ID tersebut tidak ada, buat user baru
                else {
                    // Hapus ID untuk membuat user baru
                    unset($userData['id']);
                    
                    // Set password default jika tidak ada
                    if (empty($userData['password'])) {
                        $userData['password'] = Hash::make('password');
                    } else {
                        $userData['password'] = Hash::make($userData['password']);
                    }
                    
                    // Konversi array yang disimpan sebagai JSON string
                    
                    if (isset($userData['jurusan_diampu']) && !empty($userData['jurusan_diampu']) && is_string($userData['jurusan_diampu'])) {
                        $userData['jurusan_diampu'] = json_decode($userData['jurusan_diampu'], true);
                    }
                    
                    // Konversi is_active dari string ke boolean
                    if (isset($userData['is_active'])) {
                        $userData['is_active'] = filter_var($userData['is_active'], FILTER_VALIDATE_BOOLEAN);
                    }
                    
                    User::create($userData);
                    $importCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                // Log error jika diperlukan
                \Log::error('Error importing user: ' . $e->getMessage());
            }
        }
        
        $message = "Import selesai: $importCount user baru ditambahkan, $updateCount user diperbarui";
        if ($errorCount > 0) {
            $message .= ", $errorCount error ditemukan";
        }
        
        return redirect()->route('users.index')->with('success', $message);
    }
}