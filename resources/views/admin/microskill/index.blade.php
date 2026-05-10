@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold leading-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-3 pb-2">
                Mikro Skill Intern
            </h1>
            <p class="text-gray-600">Monitoring mikro skill anak magang</p>
        </div>

        {{-- Filter Form --}}
        <div class="bg-white rounded-2xl shadow-lg mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-filter mr-3"></i>
                    Filter Data
                </h2>
            </div>
            <form method="GET" class="flex justify-between gap-4 p-6">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Cari (nama/institusi)</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Ketik untuk mencari..." />
                </div>
                <div class="flex items-end">
                    <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center" type="submit">Filter</button>
                </div>
                <div class="flex items-end">
                    @if(request()->anyFilled(['q']))
                        <a href="{{ route('admin.microskill.index') }}" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Micro Skill Data --}}
        <div class="bg-white rounded-2xl shadow-md border border-blue-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    Data Mikro Skill
                </h2>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto overflow-y-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">Nama</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Institusi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Dikirim</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($submissions as $s)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($s->intern->photo_path)
                                                <img src="{{ url('storage/' . $s->intern->photo_path) }}"
                                                        class="w-10 h-10 rounded-full object-cover border-2 border-blue-200 mr-3"
                                                        alt="{{ $s->intern->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3 border-2 border-blue-200">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $s->intern->name }}
                                            </span>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $s->intern->institution }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $s->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('d M Y H:i') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($s->photo_path)
                                            @php $microSkillFilename = basename($s->photo_path); @endphp
                                            @php $microSkillUrl = URL::temporarySignedRoute('admin.microskill.photo', now()->addSeconds(30), ['filename' => $microSkillFilename]); @endphp
                                            <img src="{{ $microSkillUrl }}" class="w-12 h-12 object-cover rounded border cursor-pointer" onclick="window.open('{{ $microSkillUrl }}','_blank')">
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
                <div class="mt-4">{{ $submissions->links() }}</div>
            </div>
        </div>

    </div>
</div>
@endsection


