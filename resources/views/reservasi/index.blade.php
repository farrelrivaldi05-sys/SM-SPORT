<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-6 bg-[#22C55E] rounded-full inline-block"></span>
                    Riwayat Reservasi Lapangan
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">SM Sport Center Booking System</p>
            </div>
            @if(Auth::user()->role === 'pelanggan')
                <a href="{{ route('reservasi.create') }}" 
                   class="bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-2.5 px-5 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition-all duration-200 text-xs tracking-wider flex items-center space-x-2">
                    <span>+ Tambah Reservasi</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            @if(session('success'))
                <div class="p-4 bg-[#22C55E]/10 border border-[#22C55E]/30 text-[#22C55E] rounded-xl text-sm font-semibold flex items-center space-x-2">
                    <span>✅ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-sm font-semibold flex items-center space-x-2">
                    <span>⚠️ {{ session('error') }}</span>
                </div>
            @endif

            <!-- TABLE CONTAINER -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-[#E5E7EB]">
                            <thead class="text-xs uppercase bg-black/60 text-gray-400 border-b border-gray-800 tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 font-bold">Kode</th>
                                    <th class="px-5 py-4 font-bold">Pemesan</th>
                                    <th class="px-5 py-4 font-bold">Lapangan</th>
                                    <th class="px-5 py-4 font-bold">Tanggal Main</th>
                                    <th class="px-5 py-4 font-bold">Jam Main</th>
                                    <th class="px-5 py-4 font-bold">Total Harga</th>
                                    <!-- 🟢 KOLOM DIBUAT DENGAN WAKTU ORDER CUSTOMER -->
                                    <th class="px-5 py-4 font-bold">Waktu Order</th>
                                    <th class="px-5 py-4 font-bold text-center">Status</th>
                                    <th class="px-5 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($reservasis as $item)
                                    <tr class="hover:bg-gray-800/60 transition-colors">
                                        <td class="px-5 py-4 font-mono font-bold text-[#22C55E] tracking-wider whitespace-nowrap">
                                            {{ $item->kode_reservasi }}
                                        </td>
                                        <td class="px-5 py-4 font-semibold text-white whitespace-nowrap">
                                            {{ $item->user->name }}
                                        </td>
                                        <td class="px-5 py-4 text-[#E5E7EB]">
                                            {{ $item->lapangan->nama_lapangan }}
                                        </td>
                                        <td class="px-5 py-4 text-gray-300 whitespace-nowrap">
                                            {{ date('d M Y', strtotime($item->tanggal)) }}
                                        </td>
                                        <td class="px-5 py-4 text-gray-300 font-mono whitespace-nowrap">
                                            {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                                        </td>
                                        <td class="px-5 py-4 font-bold text-white whitespace-nowrap">
                                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </td>

                                        <!-- 🟢 MENAMPILKAN JAM WAKTU ORDER PEMESAN DENGAN WITA (SESUAI LAPTOP) -->
                                        <td class="px-5 py-4 font-mono text-xs whitespace-nowrap">
                                            <div class="text-gray-300 font-bold">
                                                ⏰ {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('H:i:s') }} WITA
                                            </div>
                                            <div class="text-gray-500 text-[10px]">
                                                📅 {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('d/m/Y') }}
                                            </div>
                                        </td>

                                        <!-- BADGE STATUS -->
                                        <td class="px-5 py-4 text-center whitespace-nowrap">
                                            @if($item->status == 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#22C55E]/10 text-[#22C55E] border border-[#22C55E]/30 shadow-[0_0_10px_rgba(34,197,94,0.15)]">
                                                    ● Approved
                                                </span>
                                            @elseif($item->status == 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                                    ● Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                                    ● Cancelled
                                                </span>
                                            @endif
                                        </td>

                                        <!-- TOMBOL AKSI & KONTROL ADMIN -->
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-2">
                                                @if($item->status !== 'cancelled')
                                                    <a href="{{ route('reservasi.nota', $item->id) }}" 
                                                       target="_blank"
                                                       class="bg-gray-800 hover:bg-gray-700 text-[#E5E7EB] hover:text-[#22C55E] border border-gray-700 hover:border-[#22C55E]/50 text-xs font-bold py-1.5 px-3 rounded-lg transition-all duration-200">
                                                        🖨️ Nota
                                                    </a>
                                                @endif

                                                <!-- BISA DIUBAH ADMIN KAPAN SAJA -->
                                                @if(Auth::user()->role === 'admin')
                                                    @if($item->status !== 'approved')
                                                        <form action="{{ route('reservasi.approve', $item->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="bg-[#22C55E] hover:bg-[#16a34a] text-black text-xs font-extrabold uppercase py-1.5 px-3 rounded-lg shadow-[0_0_10px_rgba(34,197,94,0.2)] transition">
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($item->status !== 'cancelled')
                                                        <form action="{{ route('reservasi.cancel', $item->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-extrabold uppercase py-1.5 px-3 rounded-lg transition">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- 🔴 TOMBOL HAPUS -->
                                                    <form action="{{ route('reservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen riwayat pemesanan ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white border border-rose-500/30 p-1.5 rounded-lg transition flex items-center justify-center"
                                                                title="Hapus Pemesanan">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500 font-medium">
                                            Belum ada data reservasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reservasis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>