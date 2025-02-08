@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">คำขอรอการอนุมัติ</h1>
    <table class="table-auto w-full border border-green-300">
        <thead class="bg-green-500 text-white">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">ชื่อผู้ใช้งาน</th>
                <th class="px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="px-4 py-2">จำนวน</th>
                <th class="px-4 py-2">เหตุผลในการยืม</th>
                <th class="px-4 py-2">ทำการอนุมัติ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $request)
            <tr>
                <td class="border px-4 py-2">{{ $request->id }}</td>
                <td class="border px-4 py-2">{{ $request->user->name }}</td>
                <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border px-4 py-2">{{ $request->quantity }}</td>
                <td class="border px-4 py-2">{{ $request->reason }}</td>
                <td class="border px-4 py-2">
                    <div class="flex space-x-2">
                        <!-- ปุ่มอนุมัติ -->
                        <form action="{{ route('admin.approve', $request->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                อนุมัติ
                            </button>
                        </form>
                        <!-- ปุ่มปฏิเสธ -->
                        <form method="POST" action="{{ route('admin.reject', $request->id) }}" onsubmit="return confirm('คุณต้องการปฏิเสธคำขอนี้หรือไม่?')">
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
</div>
@endsection
