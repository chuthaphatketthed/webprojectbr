<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">เข้าสู่ระบบ</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">อีเมล</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">รหัสผ่าน</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4 flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    name="remember">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">จำฉันไว้ในระบบ</label>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline text-sm">ลืมรหัสผ่าน?</a>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
