<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Reservasi - {{ $reservasi->kode_reservasi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md border border-gray-200">
        <!-- Header Nota -->
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-wider">SM Sport Center</h1>
            <p class="text-xs text-gray-500">Jl. Olahraga No. 123, Kota Kamu</p>
            <p class="text-xs text-gray-500">Telp: 0812-3456-7890</p>
        </div>

        <div class="text-center mb-6">
            <span class="text-xs text-gray-400 uppercase tracking-widest">Bukti Reservasi</span>
            <h2 class="text-xl font-extrabold text-blue-600">{{ $reservasi->kode_reservasi }}</h2>
        </div>

        <!-- Detail Transaksi -->
        <div class="space-y-3 text-sm border-b pb-4 mb-4">
            <div class="flex justify-between">
                <span class="text-gray-500">Nama Pemesan:</span>
                <span class="font-semibold text-gray-800">{{ $reservasi->user->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal Sewa:</span>
                <span class="font-semibold text-gray-800">{{ date('d F Y', strtotime($reservasi->tanggal)) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Waktu / Jam:</span>
                <span class="font-semibold text-gray-800">
                    {{ date('H:i', strtotime($reservasi->jam_mulai)) }} - {{ date('H:i', strtotime($reservasi->jam_selesai)) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Lapangan:</span>
                <span class="font-semibold text-gray-800">{{ $reservasi->lapangan->nama_lapangan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="font-bold uppercase text-xs px-2 py-0.5 rounded
                    {{ $reservasi->status == 'approved' ? 'bg-green-100 text-green-700' : 
                       ($reservasi->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $reservasi->status }}
                </span>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <div class="flex justify-between items-center text-base font-bold text-gray-900 border-b pb-4 mb-6">
            <span>TOTAL BAYAR:</span>
            <span class="text-lg text-blue-600">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span>
        </div>

        <!-- Footer / Catatan -->
        <p class="text-center text-xs text-gray-400 mb-6">
            *Tunjukkan nota ini kepada petugas saat berada di lokasi. Terima kasih atas kunjungannya!
        </p>

        <!-- Tombol Aksi (Hilang saat diprint) -->
        <div class="flex space-x-2 no-print">
            <button onclick="window.print()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded text-sm shadow">
                🖨️ Cetak / Simpan PDF
            </button>
            <a href="{{ route('reservasi.index') }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 rounded text-sm text-center shadow">
                Kembali
            </a>
        </div>
    </div>

</body>
</html>