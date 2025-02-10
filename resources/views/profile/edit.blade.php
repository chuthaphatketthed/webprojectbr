@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">แก้ไขข้อมูลส่วนตัว</h1>

    @if (session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="mb-6">
        @csrf
        @method('PATCH')

        <!-- ชื่อ -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- อีเมล -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล:</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- หมายเลขโทรศัพท์ -->
        <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">หมายเลขโทรศัพท์:</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- ที่อยู่ -->
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">ที่อยู่:</label>
            <textarea name="address" id="address" rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('address', $user->address) }}</textarea>
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            บันทึกการเปลี่ยนแปลง
        </button>
    </form>

    <!-- แสดงสิ่งของที่กำลังยืมอยู่ -->
    <h2 class="text-xl font-bold mb-4">สิ่งของที่กำลังยืมอยู่</h2>
    @if ($currentBorrows->isEmpty())
        <p class="text-gray-500">คุณยังไม่มีการยืมสิ่งของในขณะนี้</p>
    @else
        <table class="table-auto w-full border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">ชื่ออุปกรณ์</th>
                    <th class="px-4 py-2 border">จำนวน</th>
                    <th class="px-4 py-2 border">วันที่ยืม</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currentBorrows as $borrow)
                <tr>
                    <td class="px-4 py-2 border">{{ $borrow->equipment->name }}</td>
                    <td class="px-4 py-2 border text-center">{{ $borrow->quantity }}</td>
                    <td class="px-4 py-2 border text-center">{{ $borrow->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
