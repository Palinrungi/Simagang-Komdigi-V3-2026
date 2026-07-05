@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
        <strong>Terdapat kesalahan input:</strong>

        <ul class="mt-2 list-inside list-disc text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    {{-- Nama Pemateri / Narasumber --}}
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Nama Pemateri / Narasumber <span class="text-red-500">*</span>
        </label>

        <input type="text"
               name="speaker"
               value="{{ old('speaker', $sharingSession->speaker ?? $sharingSession->speakerUser?->name ?? '') }}"
               placeholder="Masukkan nama pemateri"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
               required>
    </div>

    {{-- Nama Moderator --}}
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Nama Moderator <span class="text-red-500">*</span>
        </label>

        <input type="text"
               name="moderator"
               value="{{ old('moderator', $sharingSession->moderator ?? $sharingSession->moderatorUser?->name ?? '') }}"
               placeholder="Masukkan nama moderator"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
               required>
    </div>

    {{-- Tanggal --}}
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Tanggal <span class="text-red-500">*</span>
        </label>

        <input type="date"
               name="session_date"
               value="{{ old('session_date', isset($sharingSession) && $sharingSession->session_date ? \Carbon\Carbon::parse($sharingSession->session_date)->format('Y-m-d') : '') }}"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
               required>
    </div>

    {{-- Jam Mulai --}}
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Jam Mulai
        </label>

        <input type="time"
               name="start_time"
               value="{{ old('start_time', isset($sharingSession) && $sharingSession->start_time ? \Carbon\Carbon::parse($sharingSession->start_time)->format('H:i') : '') }}"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
    </div>

    {{-- Lokasi --}}
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Lokasi / Ruangan
        </label>

        <input type="text"
               name="location"
               value="{{ old('location', $sharingSession->location ?? '') }}"
               placeholder="Masukkan lokasi atau ruangan"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
    </div>

    {{-- Materi Sharing Session --}}
    <div class="mt-6 md:col-span-2">
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xl font-bold text-gray-800">
                Materi Sharing Session
            </h3>

            <p class="mt-1 text-sm text-gray-400">
                Admin dapat mengisi informasi materi sharing session.
            </p>
        </div>
    </div>

    {{-- Judul Materi --}}
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Judul Materi
        </label>

        <input type="text"
               name="title"
               value="{{ old('title', $sharingSession->title ?? '') }}"
               placeholder="Masukkan judul materi"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
    </div>

    {{-- Deskripsi Materi --}}
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Deskripsi Materi
        </label>

        <textarea name="description"
                  rows="6"
                  placeholder="Tuliskan deskripsi atau ringkasan materi"
                  class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description', $sharingSession->description ?? '') }}</textarea>
    </div>

    {{-- Form Evaluasi --}}
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Link Form Evaluasi
        </label>

        <input type="url"
               name="evaluation_form_link"
               value="{{ old('evaluation_form_link', $sharingSession->evaluation_form_link ?? '') }}"
               placeholder="Masukkan link form evaluasi"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

        <p class="mt-2 text-xs text-gray-400">
            Link ini akan ditampilkan pada halaman sharing session peserta magang.
        </p>
    </div>

    {{-- Dokumentasi --}}
    <div class="mt-6 md:col-span-2">
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xl font-bold text-gray-800">
                Dokumentasi Sharing Session
            </h3>

            <p class="mt-1 text-sm text-gray-400">
                Admin dapat mengunggah dokumentasi kegiatan sharing session.
            </p>
        </div>
    </div>

    {{-- Upload Dokumentasi --}}
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-600">
            Upload Dokumentasi
        </label>

        <input type="file"
               name="documentation_photo"
               accept="image/jpeg,image/png,image/webp"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

        <p class="mt-2 text-xs text-gray-400">
            Format file: JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
        </p>

        @if(isset($sharingSession) && $sharingSession->documentation_photo_url)
            <div class="mt-5 rounded-2xl border border-gray-100 bg-gray-50 p-4">
                <p class="mb-3 text-sm font-bold text-gray-600">
                    Dokumentasi Saat Ini
                </p>

                <a href="{{ $sharingSession->documentation_photo_url }}" target="_blank">
                    <img src="{{ $sharingSession->documentation_photo_url }}"
                         alt="Dokumentasi Sharing Session"
                         class="w-full max-w-md rounded-2xl border border-gray-200 shadow-sm">
                </a>

                <p class="mt-2 text-xs text-gray-400">
                    Upload foto baru jika ingin mengganti dokumentasi lama.
                </p>
            </div>
        @endif
    </div>

</div>
