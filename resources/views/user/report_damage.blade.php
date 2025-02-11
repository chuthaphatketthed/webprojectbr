@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">แจ้งอุปกรณ์ชำรุด</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.report.damage') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <!-- เลือกอุปกรณ์ -->
        <div class="mb-4">
            <label for="equipment_id" class="block text-sm font-medium text-gray-700">เลือกอุปกรณ์</label>
            <select id="equipment_id" name="equipment_id" class="border w-full px-4 py-2 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                @foreach ($equipments as $equipment)
                    <option value="{{ $equipment->id }}">{{ $equipment->name }} (เหลือ {{ $equipment->quantity }} ชิ้น)</option>
                @endforeach
            </select>
        </div>

        <!-- จำนวนที่ชำรุด -->
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">จำนวนที่ชำรุด</label>
            <input type="number" id="quantity" name="quantity" min="1" class="border w-full px-4 py-2 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <!-- รายละเอียดเพิ่มเติม -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียดเพิ่มเติม</label>
            <textarea id="description" name="description" class="border w-full px-4 py-2 rounded-md shadow-sm focus:ring focus:ring-blue-200" rows="3" required></textarea>
        </div>

        <!-- อัปโหลดภาพ -->
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">อัปโหลดรูปภาพ</label>
            <input type="file" id="image" name="image" class="border w-full px-4 py-2 rounded-md shadow-sm focus:ring focus:ring-blue-200" accept="image/*" required>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            แจ้งชำรุด
        </button>
    </form>
</div>
@endsection
