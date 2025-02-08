<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบยืมคืนครุภัณฑ์โรงพยาบาลส่งเสริมสุขภาพตำบลบ้านเกาะ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-blue-600">ยินดีต้อนรับสู่ระบบยืมคืนครุภัณฑ์</h1>
            <p class="text-lg text-gray-600 mt-2">โรงพยาบาลส่งเสริมสุขภาพตำบลบ้านเกาะ</p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-10/12 lg:w-8/12">
            <!-- Card 1 -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-blue-500 mb-4">บริการของระบบ</h2>
                <ul class="list-disc list-inside text-gray-600">
                    <li>ตรวจสอบครุภัณฑ์ที่มีให้ยืม</li>
                    <li>ส่งคำขอยืมครุภัณฑ์</li>
                    <li>ติดตามประวัติการยืมและคืน</li>
                </ul>
            </div>

            <!-- Card 2 -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-blue-500 mb-4">วิธีใช้งาน</h2>
                <ol class="list-decimal list-inside text-gray-600">
                    <li>เข้าสู่ระบบด้วยบัญชีผู้ใช้</li>
                    <li>เลือกครุภัณฑ์ที่ต้องการยืม</li>
                    <li>ส่งคำขอยืมเพื่อรออนุมัติ</li>
                </ol>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-10 space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">เข้าสู่ระบบ</a>
            <a href="{{ route('register') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg shadow hover:bg-gray-400">ลงทะเบียน</a>
        </div>
    </div>
</body>
</html>
