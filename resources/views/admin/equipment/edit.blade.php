@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">แก้ไขข้อมูลอุปกรณ์</h1>

    <form method="POST" action="{{ route('admin.equipment.update', $equipment->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700">ชื่ออุปกรณ์:</label>
            <input type="text" name="name" id="name" value="{{ $equipment->name }}" 
                   class="w-full border border-gray-300 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">จำนวนคงเหลือ:</label>
            <input type="number" name="quantity" id="quantity" value="{{ $equipment->quantity }}" 
                   class="w-full border border-gray-300 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="details" class="block text-gray-700">รายละเอียด:</label>
            <textarea name="details" id="details" rows="4" 
                      class="w-full border border-gray-300 p-2 rounded">{{ $equipment->details }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            บันทึกการเปลี่ยนแปลง
        </button>
    </form>
</div>
@endsection
