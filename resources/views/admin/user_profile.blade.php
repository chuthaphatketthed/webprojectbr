@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white shadow rounded-lg">
    <h1 class="text-2xl font-bold mb-4">ข้อมูลผู้ใช้</h1>

    <div class="bg-gray-100 p-4 rounded-md mb-6">
        <p><strong>ชื่อ:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>เบอร์โทร:</strong> {{ $user->phone_number }}</p>
    </div>

    <h2 class="text-xl font-bold mb-4">อุปกรณ์ที่กำลังยืม</h2>

    @if ($user->borrowRequests->isNotEmpty())
        <table class="table-auto w-full border border-gray-300 rounded-lg">
            <thead class="bg-green-500 text-white">
                <tr>
                    <th class="border px-4 py-2">ชื่ออุปกรณ์</th>
                    <th class="border px-4 py-2">จำนวน</th>
                    <th class="border px-4 py-2">สถานะ</th>
                    <th class="border px-4 py-2">วันที่ยืม</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->borrowRequests as $request)
                    <tr>
                        <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                        <td class="border px-4 py-2">{{ $request->quantity }}</td>
                        <td class="border px-4 py-2 text-red-500 font-bold">
                            {{ $request->status == 'return_pending' ? 'รอคืน' : 'ยืมอยู่' }}
                        </td>
                        <td class="border px-4 py-2">{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500 text-center py-4">ไม่มีอุปกรณ์ที่กำลังยืมอยู่ในขณะนี้</p>
    @endif
</div>
@endsection
