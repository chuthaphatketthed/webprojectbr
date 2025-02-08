@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">แก้ไขข้อมูลส่วนตัว</h1>

    @if (session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <!-- ชื่อ -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- อีเมล -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล:</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            บันทึกการเปลี่ยนแปลง
        </button>
    </form>
</div>
@endsection
