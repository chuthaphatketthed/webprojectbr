@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">คำขอที่รอการอนุมัติ</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="border border-gray-300 px-4 py-2">จำนวน</th>
                <th class="border border-gray-300 px-4 py-2">เหตุผลในการยืม</th>
                <th class="border border-gray-300 px-4 py-2">สถานะ</th>
                <th class="border border-gray-300 px-4 py-2">การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendingRequests as $request)
            <tr class="hover:bg-gray-100">
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $request->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->equipment->name }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $request->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $request->reason }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    {{ $request->status === 'pending' ? 'รออนุมัติ' : 'สถานะอื่น' }}
                </td>
                <td class="border border-gray-300 px-4 py-2 flex space-x-2 justify-center">
                    <!-- แก้ไขคำขอ -->
                    <form method="GET" action="{{ route('user.pending.edit', $request->id) }}">
                        <button type="submit" 
                                class="bg-yellow-500 text-white text-sm px-3 py-1 rounded hover:bg-yellow-600">
                            แก้ไข
                        </button>
                    </form>
                    <!-- ยกเลิกคำขอ -->
                    <form method="POST" action="{{ route('user.pending.cancel', $request->id) }}" 
                          onsubmit="return confirm('คุณต้องการยกเลิกคำขอนี้หรือไม่?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 text-white text-sm px-3 py-1 rounded hover:bg-red-600">
                            ไม่ต้องการยืมแล้ว
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center border border-gray-300 px-4 py-2">
                    ไม่มีคำขอที่รอการอนุมัติ
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection