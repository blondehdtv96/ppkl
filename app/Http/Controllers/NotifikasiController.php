<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifikasi()->with('permohonan.user');

        // Filter berdasarkan status baca
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        // Filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $query->byType($request->tipe);
        }

        // Filter khusus untuk kaprog berdasarkan jurusan
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu)) {
            $query->whereHas('permohonan.user', function($q) use ($user) {
                $q->whereIn('jurusan', $user->jurusan_diampu);
            });
        }

        $notifikasi = $query->orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = $user->getUnreadNotificationsCount();

        return view('notifikasi.index', compact('notifikasi', 'unreadCount'));
    }

    public function show(Notifikasi $notifikasi)
    {
        $user = Auth::user();
        
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== $user->id) {
            abort(403);
        }

        // Validasi tambahan untuk kaprog - pastikan notifikasi sesuai dengan jurusan yang diampu
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu) && $notifikasi->permohonan) {
            $notifikasi->load('permohonan.user');
            if (!in_array($notifikasi->permohonan->user->jurusan, $user->jurusan_diampu)) {
                abort(403, 'Anda tidak berwenang melihat notifikasi ini.');
            }
        }

        // Tandai sebagai sudah dibaca
        if (!$notifikasi->is_read) {
            $notifikasi->markAsRead();
        }

        $notifikasi->load('permohonan.user');

        return view('notifikasi.show', compact('notifikasi'));
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        $user = Auth::user();
        
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== $user->id) {
            abort(403);
        }

        // Validasi tambahan untuk kaprog - pastikan notifikasi sesuai dengan jurusan yang diampu
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu) && $notifikasi->permohonan) {
            $notifikasi->load('permohonan.user');
            if (!in_array($notifikasi->permohonan->user->jurusan, $user->jurusan_diampu)) {
                abort(403, 'Anda tidak berwenang mengakses notifikasi ini.');
            }
        }

        $notifikasi->markAsRead();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $query = $user->notifikasi()->unread();
        
        // Filter khusus untuk kaprog berdasarkan jurusan
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu)) {
            $query->whereHas('permohonan.user', function($q) use ($user) {
                $q->whereIn('jurusan', $user->jurusan_diampu);
            });
        }
        
        $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sebagai sudah dibaca.'
        ]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->getUnreadNotificationsCount();
        
        return response()->json([
            'count' => $count
        ]);
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $user = Auth::user();
        
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== $user->id) {
            abort(403);
        }

        // Validasi tambahan untuk kaprog - pastikan notifikasi sesuai dengan jurusan yang diampu
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu) && $notifikasi->permohonan) {
            $notifikasi->load('permohonan.user');
            if (!in_array($notifikasi->permohonan->user->jurusan, $user->jurusan_diampu)) {
                abort(403, 'Anda tidak berwenang menghapus notifikasi ini.');
            }
        }

        $notifikasi->delete();

        return redirect()->route('notifikasi.index')
                       ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll()
    {
        $user = Auth::user();
        $query = $user->notifikasi();
        
        // Filter khusus untuk kaprog berdasarkan jurusan
        if ($user->role === 'kaprog' && !empty($user->jurusan_diampu)) {
            $query->whereHas('permohonan.user', function($q) use ($user) {
                $q->whereIn('jurusan', $user->jurusan_diampu);
            });
        }
        
        $query->delete();

        return redirect()->route('notifikasi.index')
                       ->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}