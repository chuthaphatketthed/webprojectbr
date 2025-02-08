@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Return Equipment</h1>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
            {{ session('success') }}
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

    <form method="POST" action="{{ route('user.return') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="id" class="block text-sm font-medium text-gray-700">Equipment:</label>
            <select name="id" id="id" required class="border border-gray-300 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500">
                @foreach ($borrowedItems as $item)
                    <option value="{{ $item->id }}">{{ $item->equipment->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="return_proof" class="block text-sm font-medium text-gray-700">Upload Return Proof:</label>
            <input type="file" name="return_proof" id="return_proof" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Submit Return
        </button>
    </form>
</div>
@endsection
