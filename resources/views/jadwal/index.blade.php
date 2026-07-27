<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2.5">
                <!-- PIL HIJAU -->
                <span class="w-2 h-6 bg-emerald-500 rounded-full inline-block shrink-0"></span>
                <h2 class="font-black text-xl text-white uppercase tracking-wider leading-none">
                    {{ __('CEK JADWAL LAPANGAN') }}
                </h2>
            </div>
            <p class="text-xs text-slate-400 font-semibold tracking-wide uppercase mt-1.5 pl-4">
                Pantau Ketersediaan Lapangan Secara Real-Time
            </p>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Informasi & Filter Tanggal -->
            <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl">
                <div>
                    <h3 class="text-2xl font-black text-white flex items-center gap-2">
                        📅 Jadwal Operasional Arena
                    </h3>
                    <p class="text-slate-400 text-sm mt-1">
                        Menampilkan Jadwal Tanggal: 
                        <span class="text-emerald-400 font-semibold underline underline-offset-4">
                            {{ isset($tanggalFormatted) ? $tanggalFormatted : \Carbon\Carbon::parse($tanggal ?? date('Y-m-d'))->translatedFormat('d F Y') }}
                        </span>
                    </p>
                </div>

                <!-- FORM FILTER TANGGAL (FIXED BACKGROUND GELAP) -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2 bg-slate-950 p-2 rounded-xl border border-slate-800">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-800 rounded-lg border border-slate-700 w-full sm:w-auto">
                            <span class="text-xs text-slate-300 font-bold uppercase shrink-0">Pilih Tanggal:</span>
                            
                            <!-- INPUT DATE DENGAN INLINE CSS UNTUK OVERRIDE BROWSER DEFAULT -->
                            <input 
                                type="date" 
                                name="tanggal" 
                                value="{{ $tanggal ?? date('Y-m-d') }}" 
                                style="background-color: #0f172a !important; color: #ffffff !important; color-scheme: dark;"
                                class="text-xs font-bold rounded-md px-2 py-1 border border-slate-700 focus:outline-none focus:ring-1 focus:ring-emerald-500 cursor-pointer"
                                onchange="this.form.submit()"
                            >
                        </div>

                        <button 
                            type="submit" 
                            class="bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-extrabold text-xs uppercase px-4 py-2.5 rounded-lg transition shadow-[0_0_10px_rgba(16,185,129,0.2)] flex items-center gap-1.5 justify-center w-full sm:w-auto">
                            <span>🔍</span>
                            <span>Cari</span>
                        </button>

                        @if(($tanggal ?? date('Y-m-d')) != date('Y-m-d'))
                            <a 
                                href="{{ url()->current() }}" 
                                class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs uppercase px-3.5 py-2.5 rounded-lg transition border border-slate-700 text-center w-full sm:w-auto">
                                Hari Ini
                            </a>
                        @endif
                    </form>

                    <div class="flex items-center justify-center gap-2 bg-slate-800 px-4 py-3 rounded-xl border border-slate-700 text-xs font-mono">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Waktu Sistem: <strong id="live-clock">--:--</strong> WIB</span>
                    </div>
                </div>
            </div>

            <!-- List Lapangan -->
            <div class="space-y-8">
                @foreach($lapangans as $lapangan)
                    @php
                        // Mengambil reservasi approved sesuai tanggal pilihan/filter (default: hari ini)
                        $selectedTanggal = $tanggal ?? \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');
                        $reservasis = $lapangan->reservasis()
                            ->where('status', 'approved')
                            ->whereDate('tanggal', $selectedTanggal)
                            ->orderBy('jam_mulai', 'asc')
                            ->get();
                    @endphp

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h4 class="text-xl font-bold text-white uppercase tracking-wide">{{ $lapangan->nama_lapangan }}</h4>
                                <p class="text-emerald-400 font-semibold text-sm">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam</p>
                            </div>
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 text-xs font-semibold rounded-full border border-slate-700">
                                {{ $reservasis->count() }} Slot Terisi
                            </span>
                        </div>

                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-3">Aktivitas Terisi Tanggal Ini:</p>

                        @if($reservasis->isEmpty())
                            <div class="p-4 bg-slate-800/50 border border-slate-700/50 rounded-xl text-center text-emerald-400 text-sm font-medium">
                                ✅ Belum ada pesanan terkonfirmasi untuk lapangan ini pada tanggal tersebut. Semua jam masih TERSEDIA!
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($reservasis as $reservasi)
                                    @php
                                        $jamMulai = substr($reservasi->jam_mulai, 0, 5);
                                        $jamSelesai = substr($reservasi->jam_selesai, 0, 5);
                                        $tglFormated = \Carbon\Carbon::parse($reservasi->tanggal)->format('Y-m-d');
                                    @endphp

                                    <!-- Box Kartu Jadwal -->
                                    <div class="jadwal-card p-4 rounded-xl border border-slate-800 bg-slate-800/80 transition-all duration-300 flex items-center justify-between">
                                        <div>
                                            <div class="text-jam flex items-center gap-2 font-bold text-white text-base">
                                                ⏰ {{ $jamMulai }} - {{ $jamSelesai }}
                                            </div>
                                            <div class="text-pemesan text-xs text-slate-400 mt-1">
                                                Pemesan: <span class="font-medium text-slate-200">{{ $reservasi->user->name ?? 'Pelanggan' }}</span>
                                            </div>
                                        </div>

                                        <!-- Container Badge Status -->
                                        <div class="status-container" 
                                             data-tanggal="{{ $tglFormated }}" 
                                             data-jam-mulai="{{ $jamMulai }}" 
                                             data-jam-selesai="{{ $jamSelesai }}">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-400 border border-blue-500/40">
                                                APPROVED
                                            </span>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Script Evaluasi Status & Style Langsung via Inline CSS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateRealtimeStatuses() {
                const now = new Date();
                
                // Format Jam Laptop (HH:MM)
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const currentTimeStr = `${hours}:${minutes}`;
                
                // Update jam di header
                const clockEl = document.getElementById('live-clock');
                if (clockEl) clockEl.innerText = currentTimeStr;

                // Format Tanggal Hari Ini (YYYY-MM-DD)
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const todayStr = `${year}-${month}-${day}`;

                // Cek seluruh kartu jadwal
                document.querySelectorAll('.status-container').forEach(container => {
                    const card = container.closest('.jadwal-card');
                    const textJam = card.querySelector('.text-jam');
                    const textPemesan = card.querySelector('.text-pemesan');

                    const tgl = container.getAttribute('data-tanggal');
                    const jamMulai = container.getAttribute('data-jam-mulai');
                    const jamSelesai = container.getAttribute('data-jam-selesai');

                    let isCompleted = false;
                    let isPlaying = false;

                    if (tgl < todayStr) {
                        isCompleted = true;
                    } else if (tgl === todayStr) {
                        if (currentTimeStr >= jamSelesai) {
                            isCompleted = true;
                        } else if (currentTimeStr >= jamMulai && currentTimeStr < jamSelesai) {
                            isPlaying = true;
                        }
                    }

                    if (isCompleted) {
                        // 1. TAMPILAN COMPLETED (REDUC / ABU-ABU KERUH)
                        card.style.opacity = '0.35';
                        card.style.filter = 'grayscale(100%)';
                        card.style.backgroundColor = '#0f172a';
                        card.style.borderColor = '#1e293b';

                        if (textJam) {
                            textJam.style.textDecoration = 'line-through';
                            textJam.style.color = '#64748b';
                        }
                        if (textPemesan) {
                            textPemesan.style.color = '#475569';
                        }

                        container.innerHTML = `
                            <span class="px-3 py-1 rounded-full text-xs font-bold" 
                                  style="background-color: #1e293b; color: #64748b; border: 1px solid #334155;">
                                COMPLETED
                            </span>`;

                    } else if (isPlaying) {
                        // 2. TAMPILAN PLAYING NOW (MENYALA HIJAU)
                        card.style.opacity = '1';
                        card.style.filter = 'none';
                        card.style.backgroundColor = 'rgba(6, 78, 59, 0.3)';
                        card.style.borderColor = '#10b981';

                        if (textJam) {
                            textJam.style.textDecoration = 'none';
                            textJam.style.color = '#ffffff';
                        }
                        if (textPemesan) {
                            textPemesan.style.color = '#cbd5e1';
                        }

                        container.innerHTML = `
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold animate-pulse" 
                                  style="background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.5);">
                                <span class="w-2 h-2 rounded-full animate-ping" style="background-color: #34d399;"></span>
                                PLAYING NOW
                            </span>`;

                    } else {
                        // 3. TAMPILAN APPROVED (NORMAL BIRU/TERANG)
                        card.style.opacity = '1';
                        card.style.filter = 'none';
                        card.style.backgroundColor = 'rgba(30, 41, 59, 0.8)';
                        card.style.borderColor = '#334155';

                        if (textJam) {
                            textJam.style.textDecoration = 'none';
                            textJam.style.color = '#ffffff';
                        }
                        if (textPemesan) {
                            textPemesan.style.color = '#94a3b8';
                        }

                        container.innerHTML = `
                            <span class="px-3 py-1 rounded-full text-xs font-bold" 
                                  style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4);">
                                APPROVED
                            </span>`;
                    }
                });
            }

            // Jalankan saat pertama kali dimuat
            updateRealtimeStatuses();

            // Jalankan otomatis tiap 5 detik
            setInterval(updateRealtimeStatuses, 5000);
        });
    </script>
</x-app-layout>