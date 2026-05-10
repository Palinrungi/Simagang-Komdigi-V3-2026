@extends('layouts.app')

@section('title', 'Monitoring Absensi - Sistem Magang')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold leading-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-3 pb-2">
                Monitoring Absensi
            </h1>
            <p class="text-gray-600">Pantau kehadiran dan aktivitas check in/out anak magang</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 border-t-4 border-blue-500">
            <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
            </h2>
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Anak Magang</label>
                    <select name="intern_id" id="intern_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Semua</option>
                        @foreach($interns as $intern)
                            <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                {{ $intern->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all ">
                        <option value="">Semua</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alfa" {{ request('status') == 'alfa' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Hingga Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <div class="flex items-end gap-2">
                    <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center" 
                            type="submit">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    @if(request()->query())
                        <a href="{{ route('admin.attendance.index') }}"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-700
                                font-bold py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif

                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-2xl shadow-md border border-blue-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Data Absensi
                </h2>
            </div>
            <div class="p-6">
                <div class="max-h-[500px] overflow-x-auto overflow-y-auto pr-2">
                    <table class="min-w-[1000px] w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Foto Check In</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Foto Check Out</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Status Dokumen</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            {{-- Baris virtual: intern belum absen hari ini --}}
                            @foreach($todayAbsentInterns as $absentIntern)
                                <tr class="bg-red-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($todayWita)->format('d M Y') }}
                                            <span class="text-xs text-gray-400">(Hari ini)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $absentIntern->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Hadir
                                        </span>
                                    </td>
                                    <td colspan="6" class="px-6 py-4 text-sm text-gray-400 italic text-center">
                                        Belum melakukan absensi hari ini
                                    </td>
                                </tr>
                            @endforeach

                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap ">
                                        <div class="text-sm text-center font-medium text-gray-900">{{ $attendance->date->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-center font-medium text-gray-900">{{ $attendance->intern->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap ">
                                        <span class="px-3 py-1 inline-flex text-center text-xs leading-5 font-semibold rounded-full
                                            @if($attendance->status == 'hadir') bg-green-100 text-green-800
                                            @elseif($attendance->status == 'izin') bg-yellow-100 text-yellow-800
                                            @elseif($attendance->status == 'sakit') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($attendance->status == 'alfa') Tidak Hadir
                                            @else {{ ucfirst($attendance->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 text-center">
                                            {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center flex justify-center">
                                        @if($attendance->photo_path)
                                            @php
                                                $photoUrl = URL::temporarySignedRoute(
                                                    'admin.attendance.photo',
                                                    now()->addSeconds(30),
                                                    ['filename' => basename($attendance->photo_path)]
                                                );
                                            @endphp
                                            <img src="{{ $photoUrl }}" 
                                                alt="Check In" 
                                                data-secure="true"
                                                class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all" 
                                                onclick="window.open('{{ $photoUrl }}', '_blank')" 
                                                title="Klik untuk melihat full size">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 text-center">
                                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center flex justify-center">
                                        @if($attendance->photo_checkout)
                                            @php
                                                $photoUrl = URL::temporarySignedRoute(
                                                    'admin.attendance.photo',
                                                    now()->addSeconds(30),
                                                    ['filename' => basename($attendance->photo_checkout)]
                                                );
                                            @endphp
                                            <img src="{{ $photoUrl }}" 
                                                    alt="Check Out" 
                                                    data-secure="true"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all" 
                                                    onclick="window.open('{{ $photoUrl }}', '_blank')" 
                                                    title="Klik untuk melihat full size">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($attendance->document_status)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($attendance->document_status == 'approved') bg-green-100 text-green-800
                                                @elseif($attendance->document_status == 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($attendance->document_status) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.attendance.show', $attendance) }}" 
                                            class="text-blue-600 hover:text-blue-900 inline-block transition-colors" 
                                                title="Lihat detail">
                                                <i class="fas fa-eye"></i>
                                        </a>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center ">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-sm">Belum ada data absensi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection