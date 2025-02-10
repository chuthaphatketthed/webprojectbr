@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">แก้ไขคำขอที่รออนุมัติ</h1>
    <form method="POST" action="{{ route('user.pending.update', $borrowRequest->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium">จำนวน</label>
            <input type="number" id="quantity" name="quantity" class="border w-full px-4 py-2"
                value="{{ $borrowRequest->quantity }}" required>
        </div>

        <div class="mb-4">
            <label for="reason" class="block text-sm font-medium">เหตุผลในการยืม</label>
            <textarea id="reason" name="reason" class="border w-full px-4 py-2" rows="3" required>{{ $borrowRequest->reason }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">บันทึก</button>
    </form>
</div>
@endsection