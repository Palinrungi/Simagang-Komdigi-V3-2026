@extends('layouts.app')

@section('title', 'Manajemen Situs')

@section('content')
<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">
    
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-2">Admin Panel / Pengaturan</p>
                    <h1 class="text-3xl font-bold">Manajemen Situs</h1>
                    <p class="text-blue-100 mt-2">Kelola logo, identitas web, dan pengaturan tampilan lainnya.</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informasi Umum -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Umum</h2>
                
                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Situs</label>
                    <input type="text" name="site_name" value="{{ \App\Models\SystemSetting::get('site_name', 'SIMAGANG') }}" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Nama yang akan tampil di navbar atau judul.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Chatbot AI</label>
                    <input type="text" name="chatbot_name" value="{{ \App\Models\SystemSetting::get('chatbot_name', 'SIMA') }}" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Nama dari asisten virtual AI.</p>
                </div>
            </div>

            <!-- Logo Utama -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Logo Utama</h2>
                
                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Logo Komdigi</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ \App\Models\SystemSetting::get('logo_komdigi', url('storage/vendor/logo_komdigi.png')) }}" alt="Logo Komdigi" class="w-16 h-16 object-contain border p-2 rounded-lg bg-gray-50">
                        <input type="file" name="logo_komdigi" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-blue-500" accept="image/*">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Logo Simagang</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ \App\Models\SystemSetting::get('logo_simagang', url('storage/vendor/logo_simagang.png')) }}" alt="Logo Simagang" class="w-16 h-16 object-contain border p-2 rounded-lg bg-gray-50">
                        <input type="file" name="logo_simagang" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-blue-500" accept="image/*">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Icon Chatbot AI</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ \App\Models\SystemSetting::get('chatbot_icon', asset('storage/vendor/logo_komdigi.png')) }}" alt="Chatbot Icon" class="w-16 h-16 object-contain border p-2 rounded-lg bg-gray-50">
                        <input type="file" name="chatbot_icon" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-blue-500" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Logo Footer / Mitra -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Logo Partner / Kampanye</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Logo BerAKHLAK</label>
                        <div class="flex flex-col gap-3">
                            <img src="{{ \App\Models\SystemSetting::get('logo_berakhlak', url('storage/vendor/logo_berakhlak.png')) }}" alt="BerAKHLAK" class="h-20 object-contain border p-2 rounded-lg bg-gray-50">
                            <input type="file" name="logo_berakhlak" class="w-full px-2 py-1 text-sm border rounded focus:outline-none" accept="image/*">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Logo Bangga Melayani</label>
                        <div class="flex flex-col gap-3">
                            <img src="{{ \App\Models\SystemSetting::get('logo_banggamelayani', url('storage/vendor/logo_banggamelayani.png')) }}" alt="Bangga Melayani" class="h-20 object-contain border p-2 rounded-lg bg-gray-50">
                            <input type="file" name="logo_banggamelayani" class="w-full px-2 py-1 text-sm border rounded focus:outline-none" accept="image/*">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Logo Anti Korupsi</label>
                        <div class="flex flex-col gap-3">
                            <img src="{{ \App\Models\SystemSetting::get('logo_antikorupsi', url('storage/vendor/logo_antikorupsi.png')) }}" alt="Anti Korupsi" class="h-20 object-contain border p-2 rounded-lg bg-gray-50">
                            <input type="file" name="logo_antikorupsi" class="w-full px-2 py-1 text-sm border rounded focus:outline-none" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
