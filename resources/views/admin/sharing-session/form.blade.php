@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
        <strong>Terdapat kesalahan input:</strong>

        <ul class="list-disc list-inside text-sm mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Narasumber <span class="text-red-500">*</span>
        </label>

        <select name="speaker_user_id"
                class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                required>
            <option value="">Pilih Narasumber</option>

            @foreach($internUsers as $user)
                <option value="{{ $user->id }}"
                    {{ old('speaker_user_id', $sharingSession->speaker_user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Moderator <span class="text-red-500">*</span>
        </label>

        <select name="moderator_user_id"
                class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                required>
            <option value="">Pilih Moderator</option>

            @foreach($internUsers as $user)
                <option value="{{ $user->id }}"
                    {{ old('moderator_user_id', $sharingSession->moderator_user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Tanggal <span class="text-red-500">*</span>
        </label>

        <input type="date"
               name="session_date"
               value="{{ old('session_date', isset($sharingSession) && $sharingSession->session_date ? \Carbon\Carbon::parse($sharingSession->session_date)->format('Y-m-d') : '') }}"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Jam Mulai
        </label>

        <input type="time"
               name="start_time"
               value="{{ old('start_time', isset($sharingSession) && $sharingSession->start_time ? \Carbon\Carbon::parse($sharingSession->start_time)->format('H:i') : '') }}"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Lokasi / Ruangan
        </label>

        <input type="text"
               name="location"
               value="{{ old('location', $sharingSession->location ?? '') }}"
               placeholder="Contoh: Ruang Rapat Lt. 2"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3">
    </div>

    <div class="md:col-span-2 mt-6">
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xl font-bold text-gray-800">
                Materi Sharing Session
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Admin dapat mengisi informasi materi sharing session.
            </p>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Judul Materi
        </label>

        <input type="text"
               name="title"
               value="{{ old('title', $sharingSession->title ?? '') }}"
               placeholder="Masukkan judul materi"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Deskripsi Materi
        </label>

        <textarea name="description"
                  rows="6"
                  placeholder="Tuliskan deskripsi atau ringkasan materi"
                  class="w-full border border-gray-200 rounded-2xl px-4 py-3">{{ old('description', $sharingSession->description ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Link Form Evaluasi
        </label>

        <input type="url"
               name="evaluation_form_link"
               value="{{ old('evaluation_form_link', $sharingSession->evaluation_form_link ?? '') }}"
               placeholder="https://forms.gle/..."
               class="w-full border border-gray-200 rounded-2xl px-4 py-3">

        <p class="text-xs text-gray-400 mt-2">
            Link ini akan tampil pada halaman sharing session peserta magang.
        </p>
    </div>

    <div class="md:col-span-2 mt-6">
        <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xl font-bold text-gray-800">
                Dokumentasi Sharing Session
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Admin dapat mengupload dokumentasi tanpa harus login sebagai moderator.
            </p>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Upload Dokumentasi
        </label>

        <input type="file"
               name="documentation_photo"
               accept="image/jpeg,image/png,image/webp"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3">

        <p class="text-xs text-gray-400 mt-2">
            Format: JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
        </p>

        @if(isset($sharingSession) && $sharingSession->documentation_photo_url)
            <div class="mt-5 bg-gray-50 border border-gray-100 rounded-2xl p-4">
                <p class="text-sm font-bold text-gray-600 mb-3">
                    Dokumentasi Saat Ini
                </p>

                <a href="{{ $sharingSession->documentation_photo_url }}" target="_blank">
                    <img src="{{ $sharingSession->documentation_photo_url }}"
                         alt="Dokumentasi Sharing Session"
                         class="w-full max-w-md rounded-2xl border border-gray-200 shadow-sm">
                </a>

                <p class="text-xs text-gray-400 mt-2">
                    Upload foto baru jika ingin mengganti dokumentasi lama.
                </p>
            </div>
        @endif
    </div>

</div>


