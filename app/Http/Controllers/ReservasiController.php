<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    // 1. Tampilkan Daftar Reservasi
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelanggan') {
            $reservasis = Reservasi::with(['user', 'lapangan'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $reservasis = Reservasi::with(['user', 'lapangan'])
                ->latest()
                ->paginate(10);
        }

        return view('reservasi.index', compact('reservasis'));
    }

    // 2. Form Tambah Reservasi
    public function create()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('reservasi.index')
                ->with('error', 'Admin hanya bertugas mengelola dan melihat riwayat reservasi.');
        }

        $lapangans = Lapangan::all();
        return view('reservasi.create', compact('lapangans'));
    }

    // 3. Simpan Reservasi & Auto-Approve Langsung
    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('reservasi.index')
                ->with('error', 'Admin tidak diizinkan membuat reservasi.');
        }

        // VALIDASI INPUT + PESAN ERROR KHUSUS
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal'     => 'required|date|after_or_equal:today',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'lapangan_id.required'   => 'Silakan pilih lapangan terlebih dahulu.',
            'lapangan_id.exists'     => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required'       => 'Tanggal reservasi wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal reservasi tidak boleh memilih tanggal yang sudah berlalu!',
            'jam_mulai.required'     => 'Jam mulai wajib diisi.',
            'jam_selesai.required'   => 'Jam selesai wajib diisi.',
            'jam_selesai.after'      => 'Jam selesai harus lebih lambat dari jam mulai.',
        ]);

        $jamMulai   = Carbon::createFromFormat('H:i', $request->jam_mulai)->format('H:i:s');
        $jamSelesai = Carbon::createFromFormat('H:i', $request->jam_selesai)->format('H:i:s');

        // LOGIKA PENGECEKAN BENTROK TERISOLASI PER LAPANGAN
        $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai)
                      ->where('jam_selesai', '>', $jamMulai);
            })
            ->exists();

        if ($bentrok) {
            return back()
                ->withInput()
                ->with('error', '⚠️ Maaf, jadwal pada jam dan lapangan tersebut sudah terisi! Silakan pilih lapangan atau waktu lain.');
        }

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $durasiJam = (strtotime($jamSelesai) - strtotime($jamMulai)) / 3600;
        $totalHarga = $durasiJam * $lapangan->harga_per_jam;

        // SIMPAN DENGAN STATUS LANGSUNG APPROVED (AUTO-APPROVE)
        Reservasi::create([
            'kode_reservasi' => 'RES-' . strtoupper(Str::random(6)),
            'user_id'        => Auth::id(),
            'lapangan_id'    => $request->lapangan_id,
            'tanggal'        => $request->tanggal,
            'jam_mulai'      => $jamMulai,
            'jam_selesai'    => $jamSelesai,
            'total_harga'    => $totalHarga,
            'status'         => 'approved', // 🟢 OTOMATIS APPROVED TANPA PERLU KONFIRMASI ADMIN
        ]);

        return redirect()->route('reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat dan langsung terkonfirmasi (Approved)!');
    }

    // 4. Approve Admin (Jika ingin merubah manual)
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => 'approved']);

        return back()->with('success', 'Reservasi berhasil disetujui (Approved).');
    }

    // 5. Cancel Admin
    public function cancel($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservasi berhasil dibatalkan (Cancelled).');
    }

    // 6. Cetak Nota Reservasi
    public function nota($id)
    {
        $reservasi = Reservasi::with(['user', 'lapangan'])->findOrFail($id);

        if (Auth::user()->role === 'pelanggan' && $reservasi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat nota ini.');
        }

        return view('reservasi.nota', compact('reservasi'));
    }

    // 7. Halaman Laporan Rekap (Khusus Admin)
    public function laporan(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-t'));
        $statusFilter = $request->input('status');

        $query = Reservasi::with(['user', 'lapangan'])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $reservasis = $query->latest()->get();

        $totalPendapatan = $reservasis->where('status', 'approved')->sum('total_harga');
        $totalReservasi = $reservasis->count();
        $totalApproved = $reservasis->where('status', 'approved')->count();
        $totalPending = $reservasis->where('status', 'pending')->count();
        $totalCancelled = $reservasis->where('status', 'cancelled')->count();

        return view('reservasi.laporan', compact(
            'reservasis', 
            'tanggalMulai', 
            'tanggalSelesai', 
            'statusFilter',
            'totalPendapatan', 
            'totalReservasi',
            'totalApproved',
            'totalPending',
            'totalCancelled'
        ));
    }

    // 8. Hapus Reservasi (Khusus Admin)
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat menghapus data.');
        }

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return back()->with('success', 'Riwayat pemesanan berhasil dihapus!');
    }

    // 9. Cek Jadwal & Ketersediaan Lapangan (DENGAN FILTER TANGGAL)
    public function jadwal(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $tanggalFormatted = Carbon::parse($tanggal)->translatedFormat('d F Y');
        
        $lapangans = Lapangan::all();
        
        $reservasis = Reservasi::where('tanggal', $tanggal)
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'lapangan'])
            ->get();

        return view('jadwal.index', compact('tanggal', 'tanggalFormatted', 'lapangans', 'reservasis'));
    }
}