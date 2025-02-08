@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">ประวัติการยืม-คืนครุภัณฑ์</h1>
    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-green-500 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">ชื่อผู้ใช้งาน</th>
                <th class="border border-gray-300 px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="border border-gray-300 px-4 py-2">จำนวน</th>
                <th class="border border-gray-300 px-4 py-2">เหตุผลในการยืม</th>
                <th class="border border-gray-300 px-4 py-2">สถานะ</th>
                <th class="border border-gray-300 px-4 py-2">วันที่</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $request)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $request->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->user->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->reason }}</td>
                <td class="border px-4 py-2">
                    @if ($request->status === 'pending')
                        กำลังรออนุมัติ
                    @elseif ($request->status === 'approved')
                        อนุมัติแล้ว
                    @elseif ($request->status === 'return_pending')
                        คืนแล้ว
                    @endif
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
