@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">ประวัติการยืม-คืนครุภัณฑ์</h1>

    <!-- Dropdown Filter -->
    <div class="flex justify-end mb-4">
        <form method="GET" action="{{ route('user.history') }}">
            <label for="status" class="mr-2">กรองตามสถานะ:</label>
            <select name="status" id="status" class="border border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="" {{ request('status') === '' ? 'selected' : '' }}>ทั้งหมด</option>
                <option value="return_pending" {{ request('status') === 'return_pending' ? 'selected' : '' }}>คืนแล้ว</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>กำลังรออนุมัติ</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ปฏิเสธคำขอ</option>
                <option value="damage_pending" {{ request('status') === 'damage_pending' ? 'selected' : '' }}>รอการตรวจสอบการชำรุด</option>
                <option value="damage_approved" {{ request('status') === 'damage_approved' ? 'selected' : '' }}>อนุมัติคำขอซ่อม</option>
                <option value="damage_rejected" {{ request('status') === 'damage_rejected' ? 'selected' : '' }}>ปฏิเสธคำขอซ่อม</option>
            </select>
        </form>
    </div>

    <!-- Table -->
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
            @forelse ($borrowRequests as $request)
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2 text-center">{{ $request->id }}</td>
                    <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ $request->quantity }}</td>
                    <td class="border px-4 py-2">
                        @switch($request->status)
                            @case('return_pending')
                                {{ 'คืนแล้ว' }}
                                @break
                            @case('pending')
                                {{ 'กำลังรออนุมัติ' }}
                                @break
                            @case('approved')
                                {{ 'อนุมัติแล้ว' }}
                                @break
                            @case('rejected')
                                {{ 'ปฏิเสธคำขอ' }}
                                @break
                            @case('damage_pending')
                                {{ 'รอการตรวจสอบการชำรุด' }}
                                @break
                            @case('damage_approved')
                                {{ 'อนุมัติคำขอซ่อม' }}
                                @break
                            @case('damage_rejected')
                                {{ 'ปฏิเสธคำขอซ่อม' }}
                                @break
                            @default
                                {{ ucfirst($request->status) }}
                        @endswitch
                    </td>
                    <td class="border px-4 py-2">{{ $request->reason }}</td>
                    <td class="border px-4 py-2 text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center">ไม่มีข้อมูล</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
