@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Flash Message -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">ครุภัณฑ์ทางการแพทย์ (Admin)</h1>

    <!-- ปุ่มเพิ่มอุปกรณ์ -->
    <div class="mb-4">
        <button onclick="document.getElementById('addEquipmentForm').classList.toggle('hidden')" 
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            เพิ่มอุปกรณ์
        </button>
    </div>

    <!-- ฟอร์มเพิ่มอุปกรณ์ -->
    <div id="addEquipmentForm" class="hidden bg-gray-100 p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.equipment.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">ชื่ออุปกรณ์</label>
                <input type="text" name="name" id="name" required 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">จำนวน</label>
                <input type="number" name="quantity" id="quantity" required 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="mb-4">
                <label for="details" class="block text-sm font-medium text-gray-700">รายละเอียด</label>
                <textarea name="details" id="details" required 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                บันทึก
            </button>
        </form>
    </div>

    <!-- ตารางอุปกรณ์ -->
    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-green-500 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">ชื่อ</th>
                <th class="border border-gray-300 px-4 py-2">จำนวนคงเหลือ</th>
                <th class="border border-gray-300 px-4 py-2">รายละเอียด</th>
                <th class="border border-gray-300 px-4 py-2">การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipments as $equipment)
            <tr class="hover:bg-gray-100">
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->details }}</td>
                <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                    <!-- ปุ่มแก้ไข -->
                    <a href="{{ route('admin.equipment.edit', $equipment->id) }}" 
                        class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                        แก้ไข
                    </a>
                    <!-- ปุ่มลบ -->
                    <form method="POST" action="{{ route('admin.equipment.destroy', $equipment->id) }}" onsubmit="return confirm('คุณต้องการลบอุปกรณ์นี้หรือไม่?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                            ลบ
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
