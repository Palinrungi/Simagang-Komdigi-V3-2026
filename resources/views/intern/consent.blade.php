@extends('layouts.app')

@section('title', 'Persetujuan Data - Sistem Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background: #f0f4ff; }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        
        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 mx-auto">
            <i class="fas fa-shield-alt"></i>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">
            Persetujuan Penggunaan Data
        </h2>
        <p class="text-gray-500 text-center text-sm mb-8">
            Harap baca dan setujui persyaratan berikut untuk melanjutkan ke Dashboard.
        </p>

        <form action="{{ route('intern.consent.submit') }}" method="POST">
            @csrf
            
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 mb-6 text-gray-700 text-sm leading-relaxed">
                <p>
                    Sebagai bagian dari persyaratan program magang, kami membutuhkan persetujuan Anda mengenai penggunaan data.
                </p>
                <div class="mt-4 flex items-start gap-3">
                    <input type="checkbox" id="agree" name="agree" value="1" required class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <label for="agree" class="font-medium text-gray-800 cursor-pointer">
                        Saya menyetujui bahwa data pribadi saya yang ada dalam sistem ini digunakan sepenuhnya untuk keperluan administrasi dan pelaporan program magang.
                    </label>
                </div>
                @error('agree')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                <span>Setuju dan Lanjutkan</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
</div>
@endsection
