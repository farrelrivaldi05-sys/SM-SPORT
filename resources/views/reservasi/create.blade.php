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

    <!-- WRAPPER ALPINE JS STATE -->
    <div class="py-8" x-data="{ 
        tanggal: '{{ old('tanggal', date('Y-m-d')) }}',
        jamMulai: '{{ old('jam_mulai', '') }}',
        jamSelesai: '{{ old('jam_selesai', '') }}',
        jamSelesaiOptions: [],
        listJamMulai: [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
            '20:00', '20:30', '21:00'
        ],

        // Cek apakah opsi jam tertentu sudah lewat (berdasarkan waktu laptop)
        isJamPast(timeStr) {
            const sekarang = new Date();
            const tahun = sekarang.getFullYear();
            const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
            const tgl = String(sekarang.getDate()).padStart(2, '0');
            const tanggalHariIni = `${tahun}-${bulan}-${tgl}`;

            // Jika pilih tanggal besok/lusa, maka tidak ada jam yang terlewati
            if (this.tanggal !== tanggalHariIni) return false;

            const [jamOpt, menitOpt] = timeStr.split(':').map(Number);
            const jamNow = sekarang.getHours();
            const menitNow = sekarang.getMinutes();

            if (jamOpt < jamNow) return true;
            if (jamOpt === jamNow && menitOpt <= menitNow) return true;

            return false;
        },

        // Update jam selesai berdasarkan jam mulai yang dipilih
        updateJamSelesaiOptions() {
            this.jamSelesai = '';
            this.jamSelesaiOptions = [];
            if (!this.jamMulai) return;

            let [hour, minute] = this.jamMulai.split(':').map(Number);
            let startHour = hour + 1;

            for (let h = startHour; h <= 22; h++) {
                let formattedHour = h.toString().padStart(2, '0');
                let formattedMinute = minute.toString().padStart(2, '0');
                this.jamSelesaiOptions.push(`${formattedHour}:${formattedMinute}`);
            }
        },

        // Reset jam mulai jika jam yang dipilih ternyata terlewati saat ganti tanggal
        onTanggalChange() {
            if (this.jamMulai && this.isJamPast(this.jamMulai)) {
                this.jamMulai = '';
                this.updateJamSelesaiOptions();
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
                        <input type="date" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" x-model="tanggal" @change="onTanggalChange()" required
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
                                <template x-for="time in listJamMulai" :key="time">
                                    <option :value="time" 
                                            :disabled="isJamPast(time)" 
                                            :class="isJamPast(time) ? 'text-gray-600 bg-gray-900' : 'text-white bg-gray-900'"
                                            x-text="time + ' WIB' + (isJamPast(time) ? ' (Lewat)' : '')">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <!-- JAM SELESAI -->
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