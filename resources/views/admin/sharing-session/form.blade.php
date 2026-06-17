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
            Narasumber
        </label>

        <select name="speaker_user_id"
                class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                required>
            <option value="">Pilih Narasumber</option>

            @foreach($internUsers as $user)
                <option value="{{ $user->id }}"
                    {{ old('speaker_user_id', $sharingSession->speaker_user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} - {{ $user->email }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Moderator
        </label>

        <select name="moderator_user_id"
                class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                required>
            <option value="">Pilih Moderator</option>

            @foreach($internUsers as $user)
                <option value="{{ $user->id }}"
                    {{ old('moderator_user_id', $sharingSession->moderator_user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} - {{ $user->email }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Tanggal
        </label>

        <input type="date"
               name="session_date"
               value="{{ old('session_date', isset($sharingSession) ? $sharingSession->session_date->format('Y-m-d') : '') }}"
               class="w-full border border-gray-200 rounded-2xl px-4 py-3"
               required>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">
            Jam Mulai
        </label>

        <input type="time"
               name="start_time"
               value="{{ old('start_time', $sharingSession->start_time ?? '') }}"
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

</div>