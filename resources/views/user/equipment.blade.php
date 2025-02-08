@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">ครุภัณฑ์ทางการแพทย์ของโรงพยาบาล</h1>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mt-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">ชื่ออุปกรณ์</th>
                <th class="border border-gray-300 px-4 py-2">จำนวน</th>
                <th class="border border-gray-300 px-4 py-2">รายละเอียด</th>
                <th class="border border-gray-300 px-4 py-2">กรอกรายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipments as $equipment)
            <tr class="hover:bg-gray-100">
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $equipment->details }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <div class="flex flex-col space-y-2">
                        <!-- Borrow Form -->
                        @if ($equipment->quantity > 0)
                        <form method="POST" action="{{ route('user.borrow') }}">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                            <input type="number" name="quantity" placeholder="จำนวน" min="1" max="{{ $equipment->quantity }}" required class="border border-gray-300 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500">
                            <textarea name="reason" placeholder="เหตุผลในการยืม" required class="border border-gray-300 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500"></textarea>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full">
                                ต้องการยืม
                            </button>
                        </form>
                        @else
                        <span class="text-red-500">Out of stock</span>
                        @endif

                        <!-- Return Form -->
                        <form method="GET" action="{{ route('user.return.form', $equipment->id) }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                                ต้องการคืน
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
