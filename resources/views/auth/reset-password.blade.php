<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - SIMAGANG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * { box-sizing: border-box; margin:0; padding:0; }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #193097;
        }

        .bg-wrapper {
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(135deg, #193097 10%, #628ECB 30%, #D5DEEF 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-wrapper::before,
        .bg-wrapper::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .bg-wrapper::before {
            width: 400px;
            height: 400px;
            top: -120px;
            right: -120px;
        }

        .bg-wrapper::after {
            width: 300px;
            height: 300px;
            bottom: -100px;
            left: -100px;
        }

        @font-face {
            font-family: 'Etna';
            src: url('/fonts/Etna-Free-Font.otf') format('opentype');
        }

        .font-etna {
            font-family: 'Etna', sans-serif;
        }

        .login-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,.2);
            padding: 48px 40px;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>
<div class="bg-wrapper">

    <div class="login-container">
        <div class="text-center mb-6 space-y-3">
            <div class="flex flex-col items-center p-4">
                <img src="{{ url('storage/vendor/logo_komdigi.png') }}"
                     alt="Logo"
                     class="object-contain"
                     style="width: 80px; height: 80px"/>

                <h1 class="text-3xl font-extrabold font-etna">
                    <span style="color: #9d272a">SI</span><span style="color: #086bb0">MA</span><span style="color: #2dabe2">GA</span><span style="color: #efc400">NG</span>
                </h1>

                <p class="font-etna" style="color: #626161; font-size:10px">
                    Sistem Manajemen Magang
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-800">Reset Password</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Masukkan password baru untuk akun Anda.
                </p>
            </div>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email', $email) }}"
                       placeholder="email@contoh.com"
                       required
                       readonly
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-gray-600">

                @error('email')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">
                    Password Baru
                </label>

                <input type="password"
                       name="password"
                       placeholder="••••••••"
                       required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition">

                @error('password')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">
                    Konfirmasi Password Baru
                </label>

                <input type="password"
                       name="password_confirmation"
                       placeholder="••••••••"
                       required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold text-white
                    bg-gradient-to-r from-blue-600 to-blue-700
                    hover:from-blue-700 hover:to-blue-800
                    shadow-lg shadow-blue-500/30 transition duration-200">
                Simpan Password Baru
            </button>
        </form>

        <div class="text-center mt-5">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                Kembali ke Login
            </a>
        </div>

        <div class="text-center mt-6 text-xs text-gray-500">
            © 2026 Simagang. Sistem manajemen magang BBLSDM Komdigi Makassar.
        </div>
    </div>

</div>
</body>
</html>