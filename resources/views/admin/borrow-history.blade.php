@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">ประวัติการยืม-คืนครุภัณฑ์</h1>

        <div class="flex space-x-4">
            <!-- Dropdown กรองสถานะ -->
            <form method="GET" action="{{ route('admin.borrow.history') }}" class="flex items-center space-x-2">
                <label for="status" class="text-gray-700 font-medium">กรองตามสถานะ:</label>
                <select name="status" id="status" class="border border-gray-300 rounded-md shadow-sm px-3 py-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>กำลังรออนุมัติ</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                    <option value="return_pending" {{ request('status') == 'return_pending' ? 'selected' : '' }}>รอการคืน</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>คืนแล้ว</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ปฏิเสธคำขอ</option>
                    <option value="damage_pending" {{ request('status') == 'damage_pending' ? 'selected' : '' }}>รอการตรวจสอบการชำรุด</option>
                    <option value="damage_approved" {{ request('status') == 'damage_approved' ? 'selected' : '' }}>อนุมัติคำขอซ่อม</option>
                    <option value="damage_rejected" {{ request('status') == 'damage_rejected' ? 'selected' : '' }}>ปฏิเสธคำขอซ่อม</option>
                </select>
            </form>

            <!-- ปุ่มพิมพ์รายงาน -->
            <a href="{{ url('/admin/borrow-history/pdf') }}" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                พิมพ์รายงาน
            </a>    
        </div>    
    </div>

    <!-- ตารางข้อมูล -->
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
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('admin.user.profile', ['id' => $request->user->id]) }}" class="text-blue-500 hover:underline">
                        {{ $request->user->name }}
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->reason }}</td>
                <td class="border px-4 py-2">
                    @switch($request->status)
                        @case('return_pending') {{ 'รอการคืน' }} @break
                        @case('returned') {{ 'คืนแล้ว' }} @break
                        @case('pending') {{ 'กำลังรออนุมัติ' }} @break
                        @case('approved') {{ 'อนุมัติแล้ว' }} @break
                        @case('rejected') {{ 'ปฏิเสธคำขอ' }} @break
                        @case('damage_pending') {{ 'รอการตรวจสอบการชำรุด' }} @break
                        @case('damage_approved') {{ 'อนุมัติคำขอซ่อม' }} @break
                        @case('damage_rejected') {{ 'ปฏิเสธคำขอซ่อม' }} @break
                        @default {{ ucfirst($request->status) }}
                    @endswitch
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
