<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">รีเซ็ตรหัสผ่าน</h1>

        <p class="text-gray-700 text-center mb-4">
            หากคุณลืมรหัสผ่าน โปรดกรอกอีเมลที่คุณใช้ลงทะเบียน ระบบจะส่งลิงก์สำหรับตั้งค่ารหัสผ่านใหม่ไปยังอีเมลของคุณ
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">อีเมล</label>
                <input id="email" type="email" name="email" required autofocus
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                ส่งลิงก์รีเซ็ตรหัสผ่าน
            </button>
        </form>
    </div>
</x-guest-layout>
