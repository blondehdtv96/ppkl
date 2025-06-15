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
        $query = $user->notifikasi()->with('permohonan');

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

        $notifikasi = $query->orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = $user->getUnreadNotificationsCount();

        return view('notifikasi.index', compact('notifikasi', 'unreadCount'));
    }

    public function show(Notifikasi $notifikasi)
    {
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
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
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->markAsRead();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $user->notifikasi()->unread()->update([
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
        // Pastikan notifikasi milik user yang sedang login
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->delete();

        return redirect()->route('notifikasi.index')
                       ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll()
    {
        $user = Auth::user();
        $user->notifikasi()->delete();

        return redirect()->route('notifikasi.index')
                       ->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}