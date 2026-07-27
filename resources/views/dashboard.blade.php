<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-6 bg-[#22C55E] rounded-full inline-block"></span>
                    Dashboard & Katalog Venue
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">Selamat Datang Kembali, {{ Auth::user()->name }}! ⚡</p>
            </div>
            <a href="{{ route('reservasi.index') }}" 
               class="bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-2.5 px-5 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition text-xs tracking-wider flex items-center gap-2">
                <span>🗓️ Riwayat Reservasi</span>
            </a>
        </div>
    </x-slot>

    <!-- WRAPPER DENGAN ALPINE JS STATE UNTUK POPUP MODAL & LOGIKA DYNAMIC TIME SELECT -->
    <div class="py-8" x-data="{ 
        openModal: false, 
        selectedLapanganId: '', 
        selectedLapanganNama: '', 
        hargaPerJam: 0,
        jamMulai: '',
        jamSelesai: '',
        jamSelesaiOptions: [],

        updateJamSelesaiOptions() {
            this.jamSelesai = '';
            this.jamSelesaiOptions = [];
            if (!this.jamMulai) return;

            let [hour, minute] = this.jamMulai.split(':').map(Number);
            let startHour = hour + 1; // Minimal 1 jam bermain

            for (let h = startHour; h <= 22; h++) {
                let formattedHour = h.toString().padStart(2, '0');
                let formattedMinute = minute.toString().padStart(2, '0');
                this.jamSelesaiOptions.push(`${formattedHour}:${formattedMinute}`);
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- ALERT NOTIFIKASI -->
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

            <!-- 1. HERO BANNER WITH BADMINTON & FUTSAL -->
            <div class="relative overflow-hidden rounded-3xl bg-gray-900 border border-gray-800 p-6 sm:p-8 shadow-2xl">
                <!-- Soft Glow Background -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#22C55E]/10 blur-[120px] rounded-full pointer-events-none"></div>

                <!-- FLEX CONTAINER KIRI-KANAN -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                    
                    <!-- TEKS & TOMBOL (SISI KIRI - 60% LEBAR) -->
                    <div class="w-full sm:w-7/12 space-y-4 text-left">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#22C55E]/10 border border-[#22C55E]/30 text-[#22C55E] text-xs font-extrabold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                            <span>⚡ MULTI-SPORT VENUE CENTER</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-tight leading-tight">
                            MAIN SPORTIF, <br><span class="text-[#22C55E]">BOOKING PRAKTIS</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-300 font-medium leading-relaxed max-w-lg">
                            Nikmati arena olahraga berstandar BWF & FIFA dengan fasilitas pencahayaan LED ultra-bright dan rumput/karpet profesional. Amankan slot jam favorit tim kamu secara real-time!
                        </p>
                        <div class="pt-2">
                            <a href="#katalog-section" 
                               class="inline-flex items-center space-x-2 bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 px-5 rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] transition hover:scale-105 text-xs tracking-wider">
                                <span>Pesan Lapangan Sekarang</span>
                                <span>➔</span>
                            </a>
                        </div>
                    </div>

                    <!-- FOTO BADMINTON & FUTSAL (SISI KANAN - SIMETRIS) -->
                    <div class="w-full sm:w-5/12">
                        <div class="grid grid-cols-2 gap-3 items-center">
                            <!-- Foto Badminton Indoor -->
                            <div class="relative group rounded-2xl overflow-hidden border border-gray-700/80 shadow-lg bg-gray-950 aspect-[4/3] w-full">
                                <img 
                                    src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=400&auto=format&fit=crop" 
                                    alt="Badminton Venue" 
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300"
                                />
                            </div>

                            <!-- Foto Futsal Indoor -->
                            <div class="relative group rounded-2xl overflow-hidden border border-gray-700/80 shadow-lg bg-gray-950 aspect-[4/3] w-full">
                                <img 
                                    src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=500&auto=format&fit=crop" 
                                    alt="Lapangan Futsal Indoor" 
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300"
                                />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 2. STATS OVERVIEW CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Akun</p>
                        <p class="text-lg font-black text-[#22C55E] mt-1 uppercase">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-xl">
                        👤
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tersedia Lapangan</p>
                        <p class="text-xl font-black text-white mt-1">Badminton & Futsal</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-xl">
                        🏆
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Jam Operasional</p>
                        <p class="text-sm font-bold text-white mt-1">08:00 - 22:00 WIB</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-xl">
                        ⏰
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Layanan CS</p>
                        <p class="text-sm font-bold text-[#22C55E] mt-1">Ready 24/7</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-xl">
                        💬
                    </div>
                </div>
            </div>

            <!-- 3. KATALOG LAPANGAN -->
            <div id="katalog-section">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-black text-xl text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-5 bg-[#22C55E] rounded-full inline-block"></span>
                            Katalog Lapangan Olahraga
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih arena terbaik sesuai kebutuhan pertandingan Anda</p>
                    </div>
                    <span class="text-xs font-bold text-[#22C55E] uppercase tracking-wider bg-[#22C55E]/10 px-3 py-1 rounded-full border border-[#22C55E]/30">
                        ● All Available
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    @if(isset($lapangans) && $lapangans->count() > 0)
                        <!-- LOOPING DINAMIS DARI DATABASE -->
                        @foreach($lapangans as $lapangan)
                            <div class="bg-gray-900 border border-gray-800 hover:border-[#22C55E]/50 rounded-2xl overflow-hidden shadow-2xl transition duration-300 flex flex-col justify-between group">
                                <div>
                                    <div class="h-48 bg-gradient-to-br from-gray-800 to-black relative flex items-center justify-center p-6 border-b border-gray-800">
                                        <span class="absolute top-4 left-4 bg-[#22C55E] text-black text-[10px] font-black uppercase tracking-widest py-1 px-2.5 rounded-md shadow-md">
                                            {{ $lapangan->kategori ?? 'ARENA' }}
                                        </span>
                                        <span class="text-6xl group-hover:scale-110 transition duration-300">
                                            {{ Str::contains(strtolower($lapangan->nama_lapangan), 'futsal') ? '⚽' : '🏸' }}
                                        </span>
                                        <div class="absolute bottom-3 right-4 bg-black/80 backdrop-blur-md px-3 py-1 rounded-lg border border-gray-700 text-xs font-mono font-bold text-[#22C55E]">
                                            Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} <span class="text-gray-400 font-normal">/ Jam</span>
                                        </div>
                                    </div>

                                    <div class="p-6 space-y-4">
                                        <div>
                                            <h4 class="font-black text-lg text-white uppercase tracking-wide group-hover:text-[#22C55E] transition">{{ $lapangan->nama_lapangan }}</h4>
                                            <p class="text-xs text-gray-400 mt-1">{{ $lapangan->deskripsi ?? 'Fasilitas berstandar tinggi untuk kenyamanan bertanding.' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 pt-0">
                                    <button type="button"
                                            @click="openModal = true; selectedLapanganId = '{{ $lapangan->id }}'; selectedLapanganNama = '{{ $lapangan->nama_lapangan }}'; hargaPerJam = {{ $lapangan->harga_per_jam }}; jamMulai = ''; jamSelesai = ''; jamSelesaiOptions = [];"
                                            class="w-full text-center bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition text-xs tracking-wider">
                                        Booking {{ $lapangan->nama_lapangan }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- FALLBACK DUMMY CARDS -->
                        <!-- LAPANGAN 1 (BADMINTON VIP) -->
                        <div class="bg-gray-900 border border-gray-800 hover:border-[#22C55E]/50 rounded-2xl overflow-hidden shadow-2xl transition duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="h-48 bg-gradient-to-br from-gray-800 to-black relative flex items-center justify-center p-6 border-b border-gray-800">
                                    <span class="absolute top-4 left-4 bg-[#22C55E] text-black text-[10px] font-black uppercase tracking-widest py-1 px-2.5 rounded-md shadow-md">
                                        BADMINTON VIP
                                    </span>
                                    <span class="text-6xl group-hover:scale-110 transition duration-300">🏸</span>
                                    <div class="absolute bottom-3 right-4 bg-black/80 backdrop-blur-md px-3 py-1 rounded-lg border border-gray-700 text-xs font-mono font-bold text-[#22C55E]">
                                        Rp 75.000 <span class="text-gray-400 font-normal">/ Jam</span>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <h4 class="font-black text-lg text-white uppercase tracking-wide group-hover:text-[#22C55E] transition">Lapangan Badminton 1</h4>
                                        <p class="text-xs text-gray-400 mt-1">Karpet Impor BWF Standard 4.5mm dengan tribun penonton mini.</p>
                                    </div>

                                    <div class="space-y-2 border-t border-b border-gray-800/80 py-3">
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Karpet Premium BWF Approved</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Lampu LED Anti-Glare 500 Lux</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Akses Ruang Ganti VIP</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <button type="button" 
                                        @click="openModal = true; selectedLapanganId = '1'; selectedLapanganNama = 'Lapangan Badminton 1'; hargaPerJam = 75000; jamMulai = ''; jamSelesai = ''; jamSelesaiOptions = [];"
                                        class="w-full block text-center bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition text-xs tracking-wider">
                                    Booking Lapangan 1
                                </button>
                            </div>
                        </div>

                        <!-- LAPANGAN 2 (FUTSAL ARENA) -->
                        <div class="bg-gray-900 border border-gray-800 hover:border-[#22C55E]/50 rounded-2xl overflow-hidden shadow-2xl transition duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="h-48 bg-gradient-to-br from-gray-800 to-black relative flex items-center justify-center p-6 border-b border-gray-800">
                                    <span class="absolute top-4 left-4 bg-[#22C55E] text-black text-[10px] font-black uppercase tracking-widest py-1 px-2.5 rounded-md shadow-md">
                                        FUTSAL INTERIOR
                                    </span>
                                    <span class="text-6xl group-hover:scale-110 transition duration-300">⚽</span>
                                    <div class="absolute bottom-3 right-4 bg-black/80 backdrop-blur-md px-3 py-1 rounded-lg border border-gray-700 text-xs font-mono font-bold text-[#22C55E]">
                                        Rp 120.000 <span class="text-gray-400 font-normal">/ Jam</span>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <h4 class="font-black text-lg text-white uppercase tracking-wide group-hover:text-[#22C55E] transition">Lapangan Futsal A</h4>
                                        <p class="text-xs text-gray-400 mt-1">Rumput sintetis halus standar internasional, nyaman untuk sparingan.</p>
                                    </div>

                                    <div class="space-y-2 border-t border-b border-gray-800/80 py-3">
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Rumput Sintetis High Density</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Jaring Pengaman Keliling</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Papan Skor Digital</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <button type="button" 
                                        @click="openModal = true; selectedLapanganId = '2'; selectedLapanganNama = 'Lapangan Futsal A'; hargaPerJam = 120000; jamMulai = ''; jamSelesai = ''; jamSelesaiOptions = [];"
                                        class="w-full block text-center bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition text-xs tracking-wider">
                                    Booking Lapangan Futsal
                                </button>
                            </div>
                        </div>

                        <!-- LAPANGAN 3 (BADMINTON STANDARD) -->
                        <div class="bg-gray-900 border border-gray-800 hover:border-[#22C55E]/50 rounded-2xl overflow-hidden shadow-2xl transition duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="h-48 bg-gradient-to-br from-gray-800 to-black relative flex items-center justify-center p-6 border-b border-gray-800">
                                    <span class="absolute top-4 left-4 bg-gray-800 border border-gray-700 text-gray-200 text-[10px] font-black uppercase tracking-widest py-1 px-2.5 rounded-md shadow-md">
                                        BADMINTON STANDARD
                                    </span>
                                    <span class="text-6xl group-hover:scale-110 transition duration-300">⚡</span>
                                    <div class="absolute bottom-3 right-4 bg-black/80 backdrop-blur-md px-3 py-1 rounded-lg border border-gray-700 text-xs font-mono font-bold text-[#22C55E]">
                                        Rp 50.000 <span class="text-gray-400 font-normal">/ Jam</span>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <h4 class="font-black text-lg text-white uppercase tracking-wide group-hover:text-[#22C55E] transition">Lapangan Badminton 2</h4>
                                        <p class="text-xs text-gray-400 mt-1">Cocok untuk pemula, komunitas casual, atau latihan porsi santai.</p>
                                    </div>

                                    <div class="space-y-2 border-t border-b border-gray-800/80 py-3">
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Karpet Vinyl Standar Olahraga</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Net & Tiang Presisi</div>
                                        <div class="flex items-center text-xs text-gray-300 gap-2"><span class="text-[#22C55E]">✓</span> Kipas Angin Samping Lapangan</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <button type="button" 
                                        @click="openModal = true; selectedLapanganId = '3'; selectedLapanganNama = 'Lapangan Badminton 2'; hargaPerJam = 50000; jamMulai = ''; jamSelesai = ''; jamSelesaiOptions = [];"
                                        class="w-full block text-center bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition text-xs tracking-wider">
                                    Booking Lapangan 2
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- 4. FASILITAS PENDUKUNG -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sm:p-8">
                <h3 class="font-black text-lg text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#22C55E] rounded-full inline-block"></span>
                    Fasilitas Pendukung Sport Center
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-800">
                        <div class="text-3xl mb-2">🚿</div>
                        <h5 class="font-bold text-white text-xs uppercase">Shower & Loker</h5>
                        <p class="text-[10px] text-gray-400 mt-1">Ruang ganti bersih & air hangat</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-800">
                        <div class="text-3xl mb-2">🥤</div>
                        <h5 class="font-bold text-white text-xs uppercase">Kantin & Resto</h5>
                        <p class="text-[10px] text-gray-400 mt-1">Minuman dingin & snack sehat</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-800">
                        <div class="text-3xl mb-2">📶</div>
                        <h5 class="font-bold text-white text-xs uppercase">Free High-Speed Wi-Fi</h5>
                        <p class="text-[10px] text-gray-400 mt-1">Koneksi cepat untuk pengunjung</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-800">
                        <div class="text-3xl mb-2">🅿️</div>
                        <h5 class="font-bold text-white text-xs uppercase">Parkir Luas & Aman</h5>
                        <p class="text-[10px] text-gray-400 mt-1">Sistem CCTV 24 Jam</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= POPUP MODAL QUICK BOOKING (DYNAMIC TIME SELECTION) ================= -->
        <div x-show="openModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- MODAL UKURAN KOTAK RINGKAS (max-w-md) -->
            <div class="bg-gray-900 border border-gray-800 rounded-3xl max-w-md w-full p-6 shadow-2xl relative text-left"
                 @click.away="openModal = false">
                
                <!-- HEADER MODAL -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-800">
                    <div>
                        <h3 class="text-base font-black text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-4 bg-[#22C55E] rounded-full inline-block"></span>
                            Quick Booking Lapangan
                        </h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Pilih tanggal dan jam bertanding kamu</p>
                    </div>
                    <button @click="openModal = false" class="text-gray-400 hover:text-white text-2xl font-bold p-1 leading-none">&times;</button>
                </div>

                <!-- FORM SIMPAN RESERVASI -->
                <form action="{{ route('reservasi.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf

                    <!-- INPUT HIDDEN LAPANGAN ID -->
                    <input type="hidden" name="lapangan_id" :value="selectedLapanganId">

                    <!-- NAMA LAPANGAN (READONLY DISPLAY) -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Lapangan Terpilih</label>
                        <div class="bg-black/60 border border-[#22C55E]/40 text-[#22C55E] font-extrabold text-xs rounded-xl px-3.5 py-2.5 flex justify-between items-center shadow-inner">
                            <span x-text="selectedLapanganNama"></span>
                            <span class="text-[10px] font-mono text-gray-300 bg-gray-800/80 px-2 py-0.5 rounded border border-gray-700" 
                                  x-text="'Rp ' + Number(hargaPerJam).toLocaleString('id-ID') + ' / jam'"></span>
                        </div>
                    </div>

                    <!-- INPUT TANGGAL MAIN -->
                    <div>
                        <label for="tanggal" class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Tanggal Main</label>
                        <input type="date" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal', date('Y-m-d')) }}" required
                               class="w-full bg-black/60 border border-gray-800 rounded-xl px-3.5 py-2.5 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition [color-scheme:dark] [&::-webkit-calendar-picker-indicator]:invert cursor-pointer"
                               style="color-scheme: dark;">
                    </div>

                    <!-- DROPDOWN SELECT JAM MULAI & SELESAI -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- JAM MULAI -->
                        <div>
                            <label for="jam_mulai" class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Jam Mulai</label>
                            <select id="jam_mulai" name="jam_mulai" x-model="jamMulai" @change="updateJamSelesaiOptions()" required
                                    class="w-full bg-black/60 border border-gray-800 rounded-xl px-3.5 py-2.5 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition font-mono cursor-pointer">
                                <option value="" disabled selected>-- Jam Mulai --</option>
                                <option value="08:00" class="bg-gray-900">08:00 WIB</option>
                                <option value="08:30" class="bg-gray-900">08:30 WIB</option>
                                <option value="09:00" class="bg-gray-900">09:00 WIB</option>
                                <option value="09:30" class="bg-gray-900">09:30 WIB</option>
                                <option value="10:00" class="bg-gray-900">10:00 WIB</option>
                                <option value="10:30" class="bg-gray-900">10:30 WIB</option>
                                <option value="11:00" class="bg-gray-900">11:00 WIB</option>
                                <option value="11:30" class="bg-gray-900">11:30 WIB</option>
                                <option value="12:00" class="bg-gray-900">12:00 WIB</option>
                                <option value="12:30" class="bg-gray-900">12:30 WIB</option>
                                <option value="13:00" class="bg-gray-900">13:00 WIB</option>
                                <option value="13:30" class="bg-gray-900">13:30 WIB</option>
                                <option value="14:00" class="bg-gray-900">14:00 WIB</option>
                                <option value="14:30" class="bg-gray-900">14:30 WIB</option>
                                <option value="15:00" class="bg-gray-900">15:00 WIB</option>
                                <option value="15:30" class="bg-gray-900">15:30 WIB</option>
                                <option value="16:00" class="bg-gray-900">16:00 WIB</option>
                                <option value="16:30" class="bg-gray-900">16:30 WIB</option>
                                <option value="17:00" class="bg-gray-900">17:00 WIB</option>
                                <option value="17:30" class="bg-gray-900">17:30 WIB</option>
                                <option value="18:00" class="bg-gray-900">18:00 WIB</option>
                                <option value="18:30" class="bg-gray-900">18:30 WIB</option>
                                <option value="19:00" class="bg-gray-900">19:00 WIB</option>
                                <option value="19:30" class="bg-gray-900">19:30 WIB</option>
                                <option value="20:00" class="bg-gray-900">20:00 WIB</option>
                                <option value="20:30" class="bg-gray-900">20:30 WIB</option>
                                <option value="21:00" class="bg-gray-900">21:00 WIB</option>
                            </select>
                        </div>

                        <!-- JAM SELESAI (TERKUNCI SEBELUM JAM MULAI DIPILIH, DITERAPKAN DENGAN OPTION DINAMIS) -->
                        <div>
                            <label for="jam_selesai" class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Jam Selesai</label>
                            <select id="jam_selesai" name="jam_selesai" x-model="jamSelesai" :disabled="!jamMulai" required
                                    class="w-full bg-black/60 border border-gray-800 rounded-xl px-3.5 py-2.5 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition font-mono disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                
                                <template x-if="!jamMulai">
                                    <option value="" disabled selected>-- Pilih Jam Mulai Dulu --</option>
                                </template>
                                
                                <template x-if="jamMulai">
                                    <option value="" disabled selected>-- Jam Selesai --</option>
                                </template>

                                <template x-for="time in jamSelesaiOptions" :key="time">
                                    <option :value="time" x-text="time + ' WIB'" class="bg-gray-900"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- FOOTER ACTION -->
                    <div class="pt-3 border-t border-gray-800 flex justify-end space-x-2">
                        <button type="button" @click="openModal = false"
                                class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#22C55E] hover:bg-[#16a34a] text-black rounded-xl text-xs font-black uppercase tracking-wider transition shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                            Konfirmasi & Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>