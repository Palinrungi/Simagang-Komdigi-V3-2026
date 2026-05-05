@extends('layouts.app')

@section('title', 'Tambah Anak Magang - Sistem Magang')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 min-h-screen p-5">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 mb-6">
            <div>
                <h1 class="text-3xl pb-1 font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Tambah Anak Magang
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan data anak magang baru
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" action="{{ route('admin.intern.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="p-8 border-b">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Informasi Pribadi</h2>
                            <p class="text-sm text-gray-600">Data diri dan kontak anak magang</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <select id="calonSelect" name="name" class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Calon Anak Magang</option>

                                @foreach($calonMagang as $c)
                                    <option value="{{ $c->nama }}"
                                        data-id="{{ $c->id }}"
                                        data-name="{{ $c->nama }}"
                                        data-email="{{ $c->email }}"
                                        data-gender="{{ $c->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}"
                                        data-phone="{{ $c->no_telp }}"
                                        @if ($c->pengajuan->institusi->jenis_institusi == 'sekolah')
                                            data-education="SMA/SMK"
                                        @elseif ($c->pengajuan->institusi->jenis_institusi == 'kampus')
                                            data-education="S1/D4"
                                        @endif
                                        data-major="{{ $c->jurusan }}"
                                        data-institution="{{ $c->pengajuan->institusi->nama_institusi }}"
                                        data-purpose="{{ $c->pengajuan->keperluan }}"
                                        data-startdate="{{ $c->pengajuan->start_date }}"
                                        data-enddate="{{ $c->pengajuan->end_date }}"
                                    >
                                        {{ $c->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <input type="hidden" name="pengajuan_detail_id" id="detailIdInput">
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="emailInput" name="email" value="{{ old('email') }}" required
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" required id="genderInput"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih</option>
                                <option value="Laki-laki" {{ old('gender')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender')=='Perempuan'?'selected':'' }}>Perempuan</option>
                            </select>
                            @error('gender')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" id="phoneInput" name="phone" value="{{ old('phone') }}"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('phone')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-gray-50 border-b">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Informasi Akademik</h2>
                            <p class="text-sm text-gray-600">Data pendidikan dan institusi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Jenjang Pendidikan <span class="text-red-500">*</span>
                            </label>
                            <select name="education_level" required id="educationInput"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih</option>
                                <option value="SMA/SMK" {{ old('education_level')=='SMA/SMK'?'selected':'' }}>SMA/SMK</option>
                                <option value="S1/D4" {{ old('education_level')=='S1/D4'?'selected':'' }}>S1/D4</option>
                            </select>
                            @error('education_level')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">Jurusan</label>
                            <input type="text" name="major" value="{{ old('major') }}" id="majorInput"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('major')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Institusi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="institution" value="{{ old('institution') }}" id="institutionInput" required
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('institution')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-blue-900 mb-2">Keperluan</label>
                            <select name="purpose" id="purposeInput"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih</option>
                                @foreach(['Magang','KKN Profesi','PKL','Praktek Industri','Magang Industri','Guru Magang Industri','Job on Training'] as $p)
                                    <option value="{{ $p }}" {{ old('purpose')==$p?'selected':'' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('purpose')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Penempatan -->
                <div class="p-8 border-b">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Penempatan</h2>
                            <p class="text-sm text-gray-600">Mentor dan tim yang ditugaskan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Mentor Dropdown --}}
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Mentor <span class="text-red-500">*</span>
                            </label>

                            <select name="mentor_id" id="mentorSelect" required
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                                <option value="">Pilih Mentor</option>

                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}"
                                            data-team="{{ $mentor->team?->name ?? 'Belum masuk dalam tim' }}"
                                            {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>
                                        {{ $mentor->name }}
                                        @if($mentor->position)
                                            - {{ $mentor->position }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('mentor_id')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tim Otomatis --}}
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Tim
                            </label>

                            <input type="text"
                                id="teamDisplay"
                                readonly
                                value="Pilih mentor terlebih dahulu"
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                        bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>

                    </div>
                </div>

                <div class="p-8 bg-gray-50 border-b">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Foto & Periode Magang</h2>
                            <p class="text-sm text-gray-600">Upload foto dan tentukan periode magang</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Pass Foto <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="photo" accept="image/*" required
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('photo')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Tanggal Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required id="startDateInput"
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">Tanggal Keluar <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required id="endDateInput"
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('end_date')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

        
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Keamanan & Status</h2>
                            <p class="text-sm text-gray-600">Pengaturan password dan status aktif</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-blue-900 mb-2">
                                Status Aktif
                            </label>
                            <div class="flex items-center mt-1 w-full bg-blue-50 rounded-xl px-4 py-3.5 border border-gray-300">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm font-semibold text-gray-700">
                                    Tandai sebagai aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

        
                <div class="px-8 py-6 bg-gray-50 flex flex-col-reverse md:flex-row md:items-center md:justify-between gap-4">
                    <a href="{{ route('admin.intern.index') }}" class="inline-flex justify-center md:justify-start items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold transition">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const mentorSelect = document.getElementById('mentorSelect');
        const teamDisplay = document.getElementById('teamDisplay');

        function updateTeamDisplay() {
            const selectedOption = mentorSelect.options[mentorSelect.selectedIndex];

            if (mentorSelect.value === "") {
                teamDisplay.value = "Pilih mentor terlebih dahulu";
                return;
            }

            const teamName = selectedOption.getAttribute('data-team');
            teamDisplay.value = teamName ?? "Belum masuk dalam tim";
        }

        mentorSelect.addEventListener('change', updateTeamDisplay);

        // Jalankan saat halaman load (untuk old value)
        document.addEventListener('DOMContentLoaded', updateTeamDisplay);


        const calonSelect = document.getElementById('calonSelect');

        calonSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];

            if (this.value === "") return;

            document.getElementById('detailIdInput').value = selected.dataset.id || '';
            document.getElementById('emailInput').value = selected.dataset.email || '';
            document.getElementById('phoneInput').value = selected.dataset.phone || '';
            document.getElementById('majorInput').value = selected.dataset.major || '';
            document.getElementById('institutionInput').value = selected.dataset.institution || '';

            // select dropdown
            const genderSelect = document.getElementById('genderInput');
            const gender = (selected.dataset.gender || '').trim();

            [...genderSelect.options].forEach(option => {
                option.selected = option.value === gender;
            });
            document.getElementById('purposeInput').value = selected.dataset.purpose || '';
            document.getElementById('educationInput').value = selected.dataset.education || '';

            document.getElementById('startDateInput').value = selected.dataset.startdate || '';
            document.getElementById('endDateInput').value = selected.dataset.enddate || '';
        });

    </script>
@endpush
@endsection
