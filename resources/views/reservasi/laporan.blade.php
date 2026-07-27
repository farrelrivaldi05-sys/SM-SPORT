<x-app-layout>
    <style>
        @media print {
            nav, header, .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
                color: black !important;
            }
            .print-container {
                background-color: white !important;
                color: black !important;
                border: none !important;
                box-shadow: none !important;
            }
            .print-text-dark {
                color: black !important;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-black text-2xl text-white uppercase tracking-wider flex items-center gap-2">
            <span class="w-2 h-6 bg-[#22C55E] rounded-full inline-block"></span>
            Laporan Rekap Reservasi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- FORM FILTER (HILANG SAAT DIPRINT) -->
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl no-print">
                <form method="GET" action="{{ route('reservasi.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="tanggal_mulai" class="block text-xs font-bold uppercase tracking-wider text-gray-400">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai }}" 
                               class="mt-1 block w-full rounded-xl bg-gray-800 border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-xs font-bold uppercase tracking-wider text-gray-400">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggalSelesai }}" 
                               class="mt-1 block w-full rounded-xl bg-gray-800 border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-400">Filter Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-xl bg-gray-800 border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="approved" {{ $statusFilter == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ $statusFilter == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-2.5 px-4 rounded-xl text-xs tracking-wider transition shadow-[0_0_10px_rgba(34,197,94,0.2)]">
                            🔍 Filter
                        </button>
                        <button type="button" onclick="window.print()" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-extrabold uppercase py-2.5 px-4 rounded-xl text-xs tracking-wider border border-gray-700 transition">
                            🖨️ Cetak
                        </button>
                    </div>
                </form>
            </div>

            <!-- LAPORAN CONTAINER -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl p-6 print-container">
                
                <div class="text-center border-b border-gray-800 pb-4 mb-6">
                    <h1 class="text-2xl font-black uppercase text-white print-text-dark tracking-wider">SM SPORT CENTER</h1>
                    <p class="text-xs text-[#22C55E] uppercase font-bold tracking-widest mt-1">Laporan Rekapitulasi Reservasi Lapangan</p>
                    <p class="text-xs text-gray-400 mt-1">
                        Periode: <strong>{{ date('d-m-Y', strtotime($tanggalMulai)) }}</strong> s/d <strong>{{ date('d-m-Y', strtotime($tanggalSelesai)) }}</strong>
                    </p>
                </div>

                <!-- CARDS RINGKASAN -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-800/80 border border-[#22C55E]/40 p-4 rounded-xl">
                        <p class="text-xs text-[#22C55E] font-bold uppercase tracking-wider">Total Omset</p>
                        <p class="text-xl font-black text-white mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-800/80 border border-gray-700 p-4 rounded-xl">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Transaksi</p>
                        <p class="text-xl font-bold text-white mt-1">{{ $totalReservasi }} Transaksi</p>
                    </div>
                    <div class="bg-gray-800/80 border border-emerald-500/30 p-4 rounded-xl">
                        <p class="text-xs text-emerald-400 font-bold uppercase tracking-wider">Approved</p>
                        <p class="text-xl font-bold text-white mt-1">{{ $totalApproved }}</p>
                    </div>
                    <div class="bg-gray-800/80 border border-amber-500/30 p-4 rounded-xl">
                        <p class="text-xs text-amber-400 font-bold uppercase tracking-wider">Pending / Cancel</p>
                        <p class="text-xl font-bold text-white mt-1">{{ $totalPending }} / {{ $totalCancelled }}</p>
                    </div>
                </div>

                <!-- TABEL LAPORAN -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-[#E5E7EB] border border-gray-800">
                        <thead class="bg-black/60 uppercase border-b border-gray-800 text-gray-400 font-bold">
                            <tr>
                                <th class="px-4 py-3 border border-gray-800">No</th>
                                <th class="px-4 py-3 border border-gray-800">Kode</th>
                                <th class="px-4 py-3 border border-gray-800">Pemesan</th>
                                <th class="px-4 py-3 border border-gray-800">Lapangan</th>
                                <th class="px-4 py-3 border border-gray-800">Tanggal</th>
                                <th class="px-4 py-3 border border-gray-800">Jam</th>
                                <th class="px-4 py-3 border border-gray-800">Total Harga</th>
                                <th class="px-4 py-3 border border-gray-800">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($reservasis as $index => $item)
                                <tr class="hover:bg-gray-800/50">
                                    <td class="px-4 py-3 border border-gray-800 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border border-gray-800 font-mono font-bold text-[#22C55E]">{{ $item->kode_reservasi }}</td>
                                    <td class="px-4 py-3 border border-gray-800 font-semibold">{{ $item->user->name }}</td>
                                    <td class="px-4 py-3 border border-gray-800">{{ $item->lapangan->nama_lapangan }}</td>
                                    <td class="px-4 py-3 border border-gray-800">{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                                    <td class="px-4 py-3 border border-gray-800 font-mono">
                                        {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                                    </td>
                                    <td class="px-4 py-3 border border-gray-800 font-bold">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 border border-gray-800 text-center uppercase font-bold">
                                        {{ $item->status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 border border-gray-800">
                                        Tidak ada data reservasi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- TANDA TANGAN (PRINT ONLY) -->
                <div class="hidden print:block mt-12 text-right text-black">
                    <p class="text-xs">Dicetak pada: {{ date('d-m-Y H:i') }}</p>
                    <p class="text-xs mt-8 font-bold">Manager SM Sport Center</p>
                    <br><br><br>
                    <p class="text-xs">( _______________________ )</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>