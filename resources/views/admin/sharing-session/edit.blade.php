@extends('layouts.app')

@section('title', 'Edit Sharing Session')

@section('content')
<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">
    <div class="mb-8 bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
        <h1 class="text-3xl font-bold">Edit Sharing Session</h1>
        <p class="text-blue-100 mt-2">Perbarui data sharing session.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.sharing-session.update', $sharingSession) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.sharing-session.form')

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.sharing-session.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 font-semibold">
                    Batal
                </a>
                <button class="px-6 py-3 rounded-2xl bg-blue-600 text-white font-semibold">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection