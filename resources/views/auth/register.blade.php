<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-extrabold uppercase tracking-wider text-white">Buat Akun Baru</h2>
        <p class="text-xs text-gray-400 mt-1">Daftar sekarang untuk mulai memesan lapangan impian Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 px-4 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition duration-200 text-xs tracking-wider">
                DAFTAR SEKARANG
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-2 text-xs text-gray-400">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-[#22C55E] hover:underline font-bold">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>