@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">คำร้องแจ้งอุปกรณ์ชำรุด</h1>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($damageRequests->isEmpty())
        <p class="text-center text-gray-600">ไม่มีคำร้องแจ้งอุปกรณ์ชำรุดในขณะนี้</p>
    @else
        <table class="table-auto w-full border border-gray-300">
            <thead class="bg-green-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">ชื่ออุปกรณ์</th>
                    <th class="px-4 py-2">จำนวนที่ชำรุด</th>
                    <th class="px-4 py-2">รายละเอียด</th>
                    <th class="px-4 py-2">รูปภาพ</th>
                    <th class="px-4 py-2">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($damageRequests as $request)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $request->id }}</td>
                    <td class="border px-4 py-2 text-center">{{ $request->equipment->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $request->quantity }}</td>
                    <td class="border px-4 py-2 text-center">{{ $request->description }}</td>
                    <td class="border px-4 py-2 text-center">
                        @if($request->image_path)
                            <img src="{{ asset('storage/' . $request->image_path) }}" alt="รูปภาพอุปกรณ์ชำรุด" class="w-32 h-32 object-cover mx-auto rounded shadow">
                        @else
                            <p class="text-gray-500">ไม่มีรูปภาพ</p>
                        @endif
                    </td>
                    <td class="border px-4 py-2 text-center">
                        <div class="flex justify-center items-center space-x-2">
                            <form action="{{ route('admin.damage.approve', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    ยืนยัน
                                </button>
                            </form>
                            <form action="{{ route('admin.damage.reject', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    ปฏิเสธ
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
