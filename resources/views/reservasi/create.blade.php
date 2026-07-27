<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-6 bg-[#22C55E] rounded-full inline-block"></span>
                    Form Reservasi Lapangan
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">
                    Buat Jadwal & Reservasi Arenamu ⚡
                </p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-800 hover:bg-gray-700 text-gray-200 font-extrabold uppercase py-2.5 px-5 rounded-xl border border-gray-700 transition text-xs tracking-wider flex items-center gap-2">
                <span>⬅️ Kembali ke Dashboard</span>
            </a>
        </div>
    </x-slot>

    <!-- WRAPPER ALPINE JS STATE UNTUK PENANGANAN LOGIKA DYNAMIC TIME SELECT -->
    <div class="py-8" x-data="{ 
        jamMulai: '{{ old('jam_mulai', '') }}',
        jamSelesai: '{{ old('jam_selesai', '') }}',
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
    }" x-init="if (jamMulai) updateJamSelesaiOptions()">
        
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- ALERT ERROR VALIDASI -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs font-semibold space-y-1">
                    <div class="font-bold uppercase tracking-wider mb-1">⚠️ Terjadi Kesalahan Input:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CARD FORM CONTAINER -->
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl relative">
                
                <!-- HEADER FORM -->
                <div class="pb-4 border-b border-gray-800 mb-6">
                    <h3 class="text-lg font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-5 bg-[#22C55E] rounded-full inline-block"></span>
                        Input Reservasi Lapangan
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Isi formulir di bawah ini untuk mengamankan slot waktu main Anda.</p>
                </div>

                <!-- FORM SIMPAN RESERVASI -->
                <form action="{{ route('reservasi.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- PILIH LAPANGAN -->
                    <div>
                        <label for="lapangan_id" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Pilih Lapangan
                        </label>
                        <select id="lapangan_id" name="lapangan_id" required
                                class="w-full bg-black/60 border border-gray-800 rounded-xl px-4 py-3 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition cursor-pointer">
                            <option value="" disabled selected>-- Pilih Lapangan --</option>
                            @if(isset($lapangans) && $lapangans->count() > 0)
                                @foreach($lapangans as $lapangan)
                                    <option value="{{ $lapangan->id }}" {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }} class="bg-gray-900">
                                        {{ $lapangan->nama_lapangan }} (Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam)
                                    </option>
                                @endforeach
                            @else
                                <option value="1" class="bg-gray-900">Lapangan Badminton 1 (Rp 75.000 / jam)</option>
                                <option value="2" class="bg-gray-900">Lapangan Futsal A (Rp 120.000 / jam)</option>
                                <option value="3" class="bg-gray-900">Lapangan Badminton 2 (Rp 50.000 / jam)</option>
                            @endif
                        </select>
                    </div>

                    <!-- TANGGAL MAIN -->
                    <div>
                        <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Tanggal Main
                        </label>
                        <input type="date" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal', date('Y-m-d')) }}" required
                               class="w-full bg-black/60 border border-gray-800 rounded-xl px-4 py-3 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition [color-scheme:dark] [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
                    </div>

                    <!-- GRID JAM MULAI & JAM SELESAI -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- JAM MULAI -->
                        <div>
                            <label for="jam_mulai" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Jam Mulai
                            </label>
                            <select id="jam_mulai" name="jam_mulai" x-model="jamMulai" @change="updateJamSelesaiOptions()" required
                                    class="w-full bg-black/60 border border-gray-800 rounded-xl px-4 py-3 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition font-mono cursor-pointer">
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

                        <!-- JAM SELESAI (TERKUNCI TERLEBIH DAHULU MENGGUNAKAN Alpine.js) -->
                        <div>
                            <label for="jam_selesai" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Jam Selesai
                            </label>
                            <select id="jam_selesai" name="jam_selesai" x-model="jamSelesai" :disabled="!jamMulai" required
                                    class="w-full bg-black/60 border border-gray-800 rounded-xl px-4 py-3 text-white text-xs focus:border-[#22C55E] focus:ring-1 focus:ring-[#22C55E] transition font-mono disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                
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

                    <!-- FOOTER BUTTONS -->
                    <div class="pt-4 border-t border-gray-800 flex items-center justify-end space-x-3">
                        <a href="{{ route('reservasi.index') }}"
                           class="px-5 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-[#22C55E] hover:bg-[#16a34a] text-black rounded-xl text-xs font-black uppercase tracking-wider transition shadow-[0_0_15px_rgba(34,197,94,0.3)] flex items-center gap-2">
                            <span>Simpan Reservasi</span>
                            <span>➔</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>