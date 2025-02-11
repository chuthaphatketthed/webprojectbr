@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">ประวัติการยืม-คืนครุภัณฑ์</h1>

    <!-- แสดงข้อความแจ้งเตือนเมื่อทำรายการสำเร็จ -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Dropdown Filter -->
    <div class="flex justify-end mb-4">
        <form method="GET" action="{{ route('user.history') }}">
            <label for="status" class="mr-2">กรองตามสถานะ:</label>
            <select name="status" id="status" class="border border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                <option value="all" {{ isset($selectedStatus) && $selectedStatus == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                <option value="return_pending" {{ isset($selectedStatus) && $selectedStatus == 'return_pending' ? 'selected' : '' }}>คืนแล้ว</option>
                <option value="pending" {{ isset($selectedStatus) && $selectedStatus == 'pending' ? 'selected' : '' }}>กำลังรออนุมัติ</option>
                <option value="approved" {{ isset($selectedStatus) && $selectedStatus == 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="rejected" {{ isset($selectedStatus) && $selectedStatus == 'rejected' ? 'selected' : '' }}>ปฏิเสธคำขอ</option>
                <option value="damage_pending" {{ isset($selectedStatus) && $selectedStatus == 'damage_pending' ? 'selected' : '' }}>รอการตรวจสอบการชำรุด</option>
                <option value="damage_approved" {{ isset($selectedStatus) && $selectedStatus == 'damage_approved' ? 'selected' : '' }}>อนุมัติคำขอซ่อม</option>
                <option value="damage_rejected" {{ isset($selectedStatus) && $selectedStatus == 'damage_rejected' ? 'selected' : '' }}>ปฏิเสธคำขอซ่อม</option>
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
            <tr>
                <td class="border px-4 py-2 text-center">{{ $request->id }}</td>
                <td class="border px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border px-4 py-2 text-center">{{ $request->quantity }}</td>
                <td class="border px-4 py-2">
                    @switch($request->status)
                        @case('return_pending')
                            คืนแล้ว
                            @break
                        @case('pending')
                            กำลังรออนุมัติ
                            @break
                        @case('approved')
                            อนุมัติแล้ว
                            @break
                        @case('rejected')
                            ปฏิเสธคำขอ
                            @break
                        @case('damage_pending')
                            รอการตรวจสอบการชำรุด
                            @break
                        @case('damage_approved')
                            อนุมัติคำขอซ่อม
                            @break
                        @case('damage_rejected')
                            ปฏิเสธคำขอซ่อม
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
