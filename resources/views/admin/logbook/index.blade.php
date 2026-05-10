@extends('layouts.app')

@section('title', 'Pantau Logbook - Sistem Magang')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold leading-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-3 pb-2">
                Pantau Logbook Anak Magang
            </h1>
            <p class="text-gray-600">Monitoring aktivitas harian anak magang</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-filter mr-3"></i>
                    Filter Data
                </h2>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Cari (nama/institusi)</label>
                    <input type="text" name="q" value="{{ request('q') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                           placeholder="Ketik untuk mencari..." />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Hingga Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                </div>
                <div class="flex items-end gap-2">
                    <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center" 
                            type="submit">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'date_from', 'date_to']))
                        <a href="{{ route('admin.logbook.index') }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Logbook Table -->
        <div class="bg-white rounded-2xl shadow-md border border-blue-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-book mr-3"></i>
                    Data Logbook
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto overflow-y-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">Nama</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Institusi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">Foto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($logbooks as $l)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($l->intern->photo_path)
                                                <img src="{{ url('storage/' . $l->intern->photo_path) }}"
                                                        class="w-10 h-10 rounded-full object-cover border-2 border-blue-200 mr-3"
                                                        alt="{{ $l->intern->name }}">
                                             @else
                                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3 border-2 border-blue-200">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $l->intern->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $l->intern->institution }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($l->date)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $l->activity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($l->photo_path)
                                            @php $logbookFilename = basename($l->photo_path); @endphp
                                            @php $logbookUrl = URL::temporarySignedRoute('admin.logbook.photo', now()->addSeconds(30), ['filename' => $logbookFilename]); @endphp
                                            <img src="{{ $logbookUrl }}" 
                                                alt="Logbook" 
                                                class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all" 
                                                onclick="window.open('{{ $logbookUrl }}', '_blank')" 
                                                title="Klik untuk melihat full size">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-sm">Tidak ada data logbook.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $logbooks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection