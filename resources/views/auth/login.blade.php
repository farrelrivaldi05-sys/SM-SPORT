<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-extrabold uppercase tracking-wider text-white">Masuk Akun</h2>
        <p class="text-xs text-gray-400 mt-1">Masukkan kredensial Anda untuk mengakses sistem reservasi.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-[#22C55E] text-xs font-semibold" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] placeholder-gray-500 focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl bg-gray-800/80 border border-gray-700 text-[#E5E7EB] placeholder-gray-500 focus:border-[#22C55E] focus:ring-[#22C55E] text-sm py-2.5 px-3.5 transition">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" 
                       class="rounded bg-gray-800 border-gray-700 text-[#22C55E] shadow-sm focus:ring-[#22C55E] focus:ring-offset-gray-900">
                <span class="ms-2 text-gray-400 font-medium">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-gray-400 hover:text-[#22C55E] transition font-semibold" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full bg-[#22C55E] hover:bg-[#16a34a] text-black font-extrabold uppercase py-3 px-4 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.3)] transition duration-200 text-xs tracking-wider">
                LOG IN
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-2 text-xs text-gray-400">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-[#22C55E] hover:underline font-bold">Daftar Sekarang</a>
        </div>
    </form>
</x-guest-layout>