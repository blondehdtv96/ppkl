<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPkl;
use App\Models\HistoriPermohonan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PermohonanPklController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PermohonanPkl::with(['user', 'historiPermohonan']);

        // Filter berdasarkan role
        if ($user->isSiswa()) {
            $query->where('user_id', $user->id);
        } elseif (!$user->isAdmin()) {
            // Staff hanya melihat permohonan yang perlu diproses
            $statusMap = [
                'wali_kelas' => ['diajukan'],
                'bp' => ['disetujui_wali'],
                'kaprog' => ['disetujui_bp'],
                'tu' => ['disetujui_kaprog'],
                'hubin' => ['disetujui_tu'],
            ];
            
            if (isset($statusMap[$user->role])) {
                $query->whereIn('status', $statusMap[$user->role]);
                
                // Filter berdasarkan kelas untuk wali kelas
                if ($user->isWaliKelas() && $user->custom_kelas_diampu) {
                    // Parse kelas yang diampu (bisa multiple, dipisah koma)
                    $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
                    
                    $query->whereHas('user', function($q) use ($kelasArray) {
                        $q->whereIn('kelas', $kelasArray);
                    });
                }
                
                // Filter berdasarkan jurusan untuk kaprog
                if ($user->isKaprog() && !empty($user->jurusan_diampu)) {
                    $query->whereHas('user', function($q) use ($user) {
                        $q->whereIn('jurusan', $user->jurusan_diampu);
                    });
                }
            }
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter kelas (untuk admin dan wali kelas)
        if ($request->filled('kelas') && ($user->isAdmin() || $user->isWaliKelas())) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }
        
        // Filter jurusan (untuk admin dan kaprog)
        if ($request->filled('jurusan') && ($user->isAdmin() || $user->isKaprog())) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        $permohonan = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));

        return view('permohonan.index', compact('permohonan'));
    }

    public function create()
    {
        $this->authorize('create', PermohonanPkl::class);
        return view('permohonan.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', PermohonanPkl::class);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'kontak_perusahaan' => 'required|string|max:20',
            'email_perusahaan' => 'nullable|email|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'alasan' => 'required|string',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        
        // Set default tanggal untuk sementara (akan diubah oleh hubin nanti)
        $tanggalMulai = date('Y-m-d', strtotime('+30 days'));
        $tanggalSelesai = date('Y-m-d', strtotime('+90 days'));

        DB::beginTransaction();
        try {
            // Upload dokumen pendukung jika ada
            $dokumenPath = null;
            if ($request->hasFile('dokumen_pendukung')) {
                $file = $request->file('dokumen_pendukung');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $dokumenPath = $file->storeAs('dokumen_pendukung', $fileName, 'public');
            }
            
            // Buat permohonan PKL langsung dengan status diajukan
            $permohonan = PermohonanPkl::create([
                'user_id' => Auth::id(),
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'kontak_perusahaan' => $request->kontak_perusahaan,
                'email_perusahaan' => $request->email_perusahaan,
                'bidang_usaha' => $request->bidang_usaha,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'alasan' => $request->alasan,
                'dokumen_pendukung' => $dokumenPath,
                'status' => 'diajukan',
                'current_role' => 'wali_kelas',
            ]);

            // Buat histori langsung dengan status diajukan
            HistoriPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => Auth::id(),
                'status_dari' => '',
                'status_ke' => 'diajukan',
                'role_processor' => 'siswa',
                'catatan' => 'Permohonan PKL diajukan',
                'aksi' => 'diteruskan',
            ]);
            
            // Kirim notifikasi ke wali kelas
            $this->sendNotificationToRole('wali_kelas', $permohonan, 'Permohonan PKL Baru', 
                'Ada permohonan PKL baru dari ' . Auth::user()->name . ' yang perlu diproses.');

            DB::commit();
            return redirect()->route('permohonan.index')
                           ->with('success', 'Permohonan PKL berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal membuat permohonan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function show(PermohonanPkl $permohonan)
    {
        $this->authorize('view', $permohonan);
        
        $permohonan->load(['user', 'historiPermohonan.user']);
        return view('permohonan.show', compact('permohonan'));
    }

    public function edit(PermohonanPkl $permohonan)
    {
        $this->authorize('update', $permohonan);
        
        if (!$permohonan->canBeEdited()) {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Permohonan tidak dapat diedit pada status ini.');
        }

        return view('permohonan.edit', compact('permohonan'));
    }

    public function update(Request $request, PermohonanPkl $permohonan)
    {
        $this->authorize('update', $permohonan);

        if (!$permohonan->canBeEdited()) {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Permohonan tidak dapat diedit pada status ini.');
        }

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'kontak_perusahaan' => 'required|string|max:20',
            'email_perusahaan' => 'nullable|email|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'alasan' => 'required|string',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        
        // Set default tanggal untuk sementara (akan diubah oleh hubin nanti)
        $tanggalMulai = date('Y-m-d', strtotime('+30 days'));
        $tanggalSelesai = date('Y-m-d', strtotime('+90 days'));

        DB::beginTransaction();
        try {
            // Upload dokumen pendukung jika ada
            $dokumenPath = $permohonan->dokumen_pendukung;
            if ($request->hasFile('dokumen_pendukung')) {
                // Hapus dokumen lama jika ada
                if ($permohonan->dokumen_pendukung) {
                    Storage::disk('public')->delete($permohonan->dokumen_pendukung);
                }
                
                $file = $request->file('dokumen_pendukung');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $dokumenPath = $file->storeAs('dokumen_pendukung', $fileName, 'public');
            }
            
            // Update permohonan PKL
            $permohonan->update([
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'kontak_perusahaan' => $request->kontak_perusahaan,
                'email_perusahaan' => $request->email_perusahaan,
                'bidang_usaha' => $request->bidang_usaha,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'alasan' => $request->alasan,
                'dokumen_pendukung' => $dokumenPath,
            ]);

            // Buat histori
            HistoriPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => Auth::id(),
                'status_dari' => $permohonan->status,
                'status_ke' => $permohonan->status,
                'role_processor' => 'siswa',
                'catatan' => 'Permohonan PKL diperbarui',
                'aksi' => 'diteruskan',
            ]);

            DB::commit();
            return redirect()->route('permohonan.index')
                           ->with('success', 'Permohonan PKL berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal memperbarui permohonan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function submit(PermohonanPkl $permohonan)
    {
        $this->authorize('update', $permohonan);

        if ($permohonan->status !== 'draft') {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Permohonan sudah diajukan.');
        }

        DB::beginTransaction();
        try {
            $permohonan->update([
                'status' => 'diajukan',
                'current_role' => 'wali_kelas'
            ]);

            // Buat histori
            HistoriPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => Auth::id(),
                'status_dari' => 'draft',
                'status_ke' => 'diajukan',
                'role_processor' => 'siswa',
                'catatan' => 'Permohonan PKL diajukan',
                'aksi' => 'diteruskan',
            ]);

            // Kirim notifikasi ke wali kelas
            $this->sendNotificationToRole('wali_kelas', $permohonan, 'Permohonan PKL Baru', 
                'Ada permohonan PKL baru dari ' . $permohonan->user->name . ' yang perlu diproses.');

            DB::commit();
            return redirect()->route('permohonan.index')
                           ->with('success', 'Permohonan PKL berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengajukan permohonan: ' . $e->getMessage());
        }
    }

    public function process(Request $request, PermohonanPkl $permohonan)
    {
        $user = Auth::user();
        
        if (!$permohonan->canBeProcessedBy($user->role)) {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Anda tidak berwenang memproses permohonan ini.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'keterangan' => 'required_if:action,reject|string|nullable',
        ]);

        DB::beginTransaction();
        try {
            $action = $request->action;
            $statusLama = $permohonan->status;
            $statusBaru = $permohonan->getNextStatus($action, $user->role);

            if (!$statusBaru) {
                throw new \Exception('Status tidak valid untuk aksi ini.');
            }

            $updateData = ['status' => $statusBaru];
            
            if ($action === 'reject') {
                $updateData['catatan_penolakan'] = $request->keterangan;
                $updateData['current_role'] = null;
            } else {
                // Set role selanjutnya
                $nextRoleMap = [
                    'disetujui_wali' => 'bp',
                    'disetujui_bp' => 'kaprog',
                    'disetujui_kaprog' => 'tu',
                    'disetujui_tu' => 'hubin',
                ];
                $updateData['current_role'] = $nextRoleMap[$statusBaru] ?? null;
                $updateData['catatan_penolakan'] = null;
            }

            $permohonan->update($updateData);

            // Buat histori
            HistoriPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => $user->id,
                'status_dari' => $statusLama,
                'status_ke' => $statusBaru,
                'role_processor' => $user->role,
                'catatan' => $request->keterangan ?? ($action === 'approve' ? 'Disetujui' : 'Ditolak'),
                'aksi' => $action === 'approve' ? 'disetujui' : 'ditolak',
            ]);

            // Kirim notifikasi
            if ($action === 'approve' && isset($nextRoleMap[$statusBaru])) {
                // Notifikasi ke role selanjutnya
                $this->sendNotificationToRole($nextRoleMap[$statusBaru], $permohonan, 
                    'Permohonan PKL Perlu Diproses', 
                    'Ada permohonan PKL dari ' . $permohonan->user->name . ' yang perlu diproses.');
            }

            // Notifikasi ke siswa
            $this->sendNotificationToUser($permohonan->user_id, $permohonan,
                $action === 'approve' ? 'Permohonan Disetujui' : 'Permohonan Ditolak',
                $action === 'approve' 
                    ? 'Permohonan PKL Anda telah disetujui oleh ' . $this->getRoleLabel($user->role)
                    : 'Permohonan PKL Anda ditolak oleh ' . $this->getRoleLabel($user->role) . '. Catatan: ' . $request->keterangan
            );

            DB::commit();
            return redirect()->route('permohonan.index')
                           ->with('success', 'Permohonan berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses permohonan: ' . $e->getMessage());
        }
    }

    public function print(PermohonanPkl $permohonan)
    {
        $user = Auth::user();
        
        // Gunakan authorize untuk memeriksa izin berdasarkan policy
        $this->authorize('print', $permohonan);
        
        // Validasi status hanya untuk hubin (admin tidak perlu validasi status)
        if ($user->isHubin() && ($permohonan->status !== 'disetujui_tu' && $permohonan->status !== 'dicetak_hubin')) {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Anda tidak berwenang mencetak permohonan ini.');
        }

        DB::beginTransaction();
        try {
            // Hanya update status dan buat histori jika user adalah hubin dan status masih 'disetujui_tu'
            if ($user->isHubin() && $permohonan->status === 'disetujui_tu') {
                $permohonan->update([
                    'status' => 'dicetak_hubin',
                    'current_role' => null
                ]);

                // Buat histori
                HistoriPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'user_id' => $user->id,
                    'status_dari' => 'disetujui_tu',
                    'status_ke' => 'dicetak_hubin',
                    'role_processor' => 'hubin',
                    'catatan' => 'Surat permohonan PKL dicetak',
                    'aksi' => 'disetujui',
                ]);

                // Notifikasi ke siswa
                $this->sendNotificationToUser($permohonan->user_id, $permohonan,
                    'Surat PKL Siap', 'Surat permohonan PKL Anda telah dicetak dan siap diambil.');
            }
            
            // Jika admin mencetak, tambahkan histori tanpa mengubah status
            if ($user->isAdmin() && $permohonan->status !== 'dicetak_hubin') {
                // Buat histori
                HistoriPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'user_id' => $user->id,
                    'status_dari' => $permohonan->status,
                    'status_ke' => $permohonan->status,
                    'role_processor' => 'admin',
                    'catatan' => 'Surat permohonan PKL dicetak oleh admin',
                    'aksi' => 'disetujui', // Menggunakan nilai yang valid dalam enum
                ]);
            }

            DB::commit();
            
            // Generate PDF atau tampilkan halaman print
            return view('permohonan.print', compact('permohonan'));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mencetak permohonan: ' . $e->getMessage());
        }
    }

    private function sendNotificationToRole($role, $permohonan, $judul, $pesan)
    {
        $query = User::where('role', $role)->where('is_active', true);
        
        // Filter wali kelas berdasarkan kelas siswa
        if ($role === 'wali_kelas') {
            $kelasSiswa = $permohonan->user->kelas;
            $query->where(function($q) use ($kelasSiswa) {
                // Cek custom_kelas_diampu yang bisa berisi multiple kelas dipisah koma
                $q->where('custom_kelas_diampu', 'LIKE', '%' . $kelasSiswa . '%')
                  ->orWhere('custom_kelas_diampu', 'LIKE', '%' . substr($kelasSiswa, 0, 2) . '%');
            });
        }
        
        // Filter kaprog berdasarkan jurusan siswa
        if ($role === 'kaprog') {
            $jurusanSiswa = $permohonan->user->jurusan;
            $query->whereJsonContains('jurusan_diampu', $jurusanSiswa);
        }
        
        $users = $query->get();
        
        foreach ($users as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'permohonan_id' => $permohonan->id,
                'judul' => $judul,
                'pesan' => $pesan,
                'tipe' => 'info',
            ]);
        }
    }

    private function sendNotificationToUser($userId, $permohonan, $judul, $pesan)
    {
        Notifikasi::create([
            'user_id' => $userId,
            'permohonan_id' => $permohonan->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => 'info',
        ]);
    }

    private function getRoleLabel($role)
    {
        $labels = [
            'wali_kelas' => 'Wali Kelas',
            'bp' => 'BP (Bimbingan dan Penyuluhan)',
            'kaprog' => 'Kaprog (Kepala Program)',
            'tu' => 'TU (Tata Usaha)',
            'hubin' => 'Hubin (Hubungan Industri)',
        ];

        return $labels[$role] ?? $role;
    }
    
    public function updatePembimbing(Request $request, PermohonanPkl $permohonan)
    {
        $user = Auth::user();
        
        // Hanya hubin yang dapat mengubah data pembimbing dan tanggal PKL
        if (!$user->isHubin()) {
            return redirect()->route('permohonan.index')
                           ->with('error', 'Anda tidak berwenang mengubah data pembimbing PKL.');
        }
        
        $request->validate([
            'nama_pembimbing' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);
        
        DB::beginTransaction();
        try {
            $permohonan->update([
                'nama_pembimbing' => $request->nama_pembimbing,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            
            // Buat histori
            HistoriPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => $user->id,
                'status_dari' => $permohonan->status,
                'status_ke' => $permohonan->status,
                'role_processor' => 'hubin',
                'catatan' => 'Data pembimbing dan periode PKL diperbarui oleh Hubin',
                'aksi' => 'diteruskan', // Menggunakan nilai yang valid dalam enum
            ]);
            
            // Notifikasi ke siswa
            $this->sendNotificationToUser($permohonan->user_id, $permohonan,
                'Data PKL Diperbarui', 
                'Data pembimbing dan periode PKL Anda telah diperbarui oleh Hubin.');
            
            DB::commit();
            return redirect()->route('permohonan.show', $permohonan)
                           ->with('success', 'Data pembimbing dan periode PKL berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
}