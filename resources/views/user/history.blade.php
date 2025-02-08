@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">ประวัติการยืม-คืนครุภัณฑ์</h1>
    <table class="table-auto w-full border border-blue-300">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="px-4 py-2">จำนวน</th>
                <th class="px-4 py-2">สถานะ</th>
                <th class="px-4 py-2">เหตุผลในการยืม</th>
                <th class="px-4 py-2">วันที่</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $request)
            <tr>
                <td class="border px-4 py-2">{{ $request->id }}</td>
                <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border px-4 py-2">{{ $request->quantity }}</td>
                <td class="border px-4 py-2">
                    @if ($request->status === 'return_pending')
                        คืนแล้ว
                    @elseif ($request->status === 'pending')
                        กำลังรออนุมัติ
                    @elseif ($request->status === 'approved')
                        อนุมติแล้ว
                    @elseif ($request->status === 'rejected')
                        ปฏิเสธคำขอ
                    @elseif ($request->status === 'damage_pending')
                        รอการตรวจสอบการชำรุด
                    @else
                        {{ ucfirst($request->status) }}
                    @endif
                </td>
                <td class="border px-4 py-2">{{ $request->reason }}</td>
                <td class="border px-4 py-2">{{ $request->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
