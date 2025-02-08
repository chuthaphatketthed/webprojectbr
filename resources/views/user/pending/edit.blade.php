@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">แก้ไขคำขอที่รอการอนุมัติ</h1>

    <form method="POST" action="{{ route('user.pending.update', $borrowRequest->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">จำนวน:</label>
            <input type="number" name="quantity" id="quantity" value="{{ $borrowRequest->quantity }}" 
                   class="w-full border border-gray-300 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="reason" class="block text-gray-700">เหตุผลในการยืม:</label>
            <textarea name="reason" id="reason" rows="4" 
                      class="w-full border border-gray-300 p-2 rounded">{{ $borrowRequest->reason }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            บันทึกการเปลี่ยนแปลง
        </button>
    </form>
</div>
@endsection
