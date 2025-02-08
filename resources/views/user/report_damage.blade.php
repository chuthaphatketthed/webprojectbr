@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">แจ้งอุปกรณ์ชำรุด</h1>
    <form action="{{ route('user.report.damage') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="equipment_id" class="block text-sm font-medium">เลือกอุปกรณ์</label>
            <select id="equipment_id" name="equipment_id" class="border w-full px-4 py-2" required>
                @foreach ($equipments as $equipment)
                    <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium">รายละเอียดเพิ่มเติม</label>
            <textarea id="description" name="description" class="border w-full px-4 py-2" rows="3" required></textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium">อัปโหลดรูปภาพ</label>
            <input type="file" id="image" name="image" class="border w-full px-4 py-2" accept="image/*" required>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">แจ้งชำรุด</button>
    </form>
</div>
@endsection
