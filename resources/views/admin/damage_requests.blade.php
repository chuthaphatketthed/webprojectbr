@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">คำร้องแจ้งชำรุด</h1>
    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-gray-500 text-white">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="px-4 py-2">รายละเอียด</th>
                <th class="px-4 py-2">รูปภาพ</th>
                <th class="px-4 py-2">การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($damageRequests as $request)
            <tr>
                <td class="border px-4 py-2">{{ $request->id }}</td>
                <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border px-4 py-2">{{ $request->description }}</td>
                <td class="border px-4 py-2">
                    <img src="{{ asset('storage/' . $request->image_path) }}" alt="Damage Image" class="w-32">
                </td>
                <td class="border px-4 py-2">
                    <form action="{{ route('admin.damage.approve', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">ยืนยัน</button>
                    </form>
                    <form action="{{ route('admin.damage.reject', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">ปฏิเสธ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
