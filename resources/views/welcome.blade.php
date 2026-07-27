<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <!-- Favicon Tab Browser -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SM Sport Center - Sewa Lapangan Olahraga Premium</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-[#111827] text-[#E5E7EB] antialiased selection:bg-[#22C55E] selection:text-black min-h-screen flex flex-col justify-between">
        
        <!-- NAVBAR -->
        <nav class="border-b border-gray-800/80 bg-gray-900/60 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Brand Logo -->
                <a href="/" class="flex items-center space-x-2 font-black text-xl tracking-wider text-white uppercase">
                    <img src="{{ asset('images/logo.png') }}" class="h-9 w-auto object-contain" alt="Logo SM Sport">
                    <span>SM <span class="text-[#22C55E]">SPORT</span></span>
                </a>

                <!-- Navigation Action -->
                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-2.5 px-5 rounded-xl text-xs tracking-wider shadow-[0_0_15px_rgba(34,197,94,0.3)] transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-[#E5E7EB] hover:text-[#22C55E] font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-lg transition">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-2.5 px-5 rounded-xl text-xs tracking-wider shadow-[0_0_15px_rgba(34,197,94,0.3)] transition">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- HERO SECTION -->
        <main class="relative overflow-hidden py-16 lg:py-24 my-auto">
            <!-- Glow Accent Background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#22C55E]/10 blur-[150px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                
                <!-- Badge Sporty -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-gray-900 border border-[#22C55E]/40 mb-8 shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-ping"></span>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#22C55E]">Premium Sport Venue</span>
                </div>

                <!-- Main Heading (Diubah Sesuai Permintaan) -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white uppercase tracking-tight leading-tight mb-6">
                    Mau Olahraga Tanpa Antre,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#22C55E] to-emerald-400">Sewa Lapangan Mudah!</span>
                </h1>

                <p class="max-w-2xl mx-auto text-sm sm:text-base text-gray-400 font-medium mb-10 leading-relaxed">
                    Pesan lapangan badminton standar internasional dengan fasilitas lampu LED ultra-bright, karpet standar BWF, dan sistem reservasi real-time instan.
                </p>

                <!-- CTA Button -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('reservasi.index') }}" 
                           class="w-full sm:w-auto bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-4 px-8 rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] transition text-sm tracking-wider">
                            ⚡ Reservasi Lapangan
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="w-full sm:w-auto bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-4 px-8 rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] transition text-sm tracking-wider">
                            ⚡ Reservasi Lapangan Sekarang
                        </a>
                        <a href="{{ route('login') }}" 
                           class="w-full sm:w-auto bg-gray-900 hover:bg-gray-800 text-white border border-gray-800 font-bold uppercase py-4 px-8 rounded-xl transition text-sm tracking-wider">
                            Lihat Jadwal
                        </a>
                    @endauth
                </div>

                <!-- FEATURES HIGHLIGHT CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20 text-left">
                    <div class="bg-gray-900/80 border border-gray-800 p-6 rounded-2xl hover:border-[#22C55E]/40 transition group">
                        <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-xl mb-4 group-hover:bg-[#22C55E] group-hover:text-black transition">
                            🏆
                        </div>
                        <h3 class="font-bold text-white uppercase text-base tracking-wide">Standar BWF</h3>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Karpet lapangan vinyl kualitas kompetisi internasional untuk kenyamanan & keamanan pergelangan kaki.</p>
                    </div>

                    <div class="bg-gray-900/80 border border-gray-800 p-6 rounded-2xl hover:border-[#22C55E]/40 transition group">
                        <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-xl mb-4 group-hover:bg-[#22C55E] group-hover:text-black transition">
                            ⚡
                        </div>
                        <h3 class="font-bold text-white uppercase text-base tracking-wide">Booking Instan</h3>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Cek ketersediaan jam secara real-time dan langsung dapatkan konfirmasi nota digital otomatis.</p>
                    </div>

                    <div class="bg-gray-900/80 border border-gray-800 p-6 rounded-2xl hover:border-[#22C55E]/40 transition group">
                        <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-xl mb-4 group-hover:bg-[#22C55E] group-hover:text-black transition">
                            💡
                        </div>
                        <h3 class="font-bold text-white uppercase text-base tracking-wide">Lampu Ultra-Bright</h3>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Pencahayaan LED khusus olahraga yang terang benderang dan tidak menyilaukan pandangan mata.</p>
                    </div>
                </div>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-gray-800/80 bg-gray-900/40 py-6">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-500 font-semibold uppercase tracking-widest">
                &copy; {{ date('Y') }} SM Sport Center. Built with Precision & Speed.
            </div>
        </footer>

    </body>
</html>