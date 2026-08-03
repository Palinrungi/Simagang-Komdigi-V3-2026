# Tempat Menyimpan APK Aplikasi Mobile Simagang

Untuk menyediakan file unduhan (APK Android) di portal intern, silakan masukkan file APK hasil build dari Flutter ke dalam folder ini dengan nama file:

**`simagang-mobile.apk`**

### Cara Build APK dari project Flutter:
1. Masuk ke folder proyek Flutter (`simagang_mobile`):
   ```bash
   flutter build apk --release
   ```
2. File hasil build akan berada di `build/app/outputs/flutter-apk/app-release.apk`.
3. Salin dan ubah nama file tersebut menjadi `simagang-mobile.apk` ke folder ini (`storage/app/releases/simagang-mobile.apk`) atau ke `public/apk/simagang-mobile.apk`.

### Lokasi Yang Didukung Sistem:
Sistem akan otomatis mendeteksi keberadaan file APK di salah satu lokasi berikut:
1. `storage/app/releases/simagang-mobile.apk` *(Disarankan)*
2. `storage/app/public/apk/simagang-mobile.apk`
3. `public/apk/simagang-mobile.apk`

Jika file tersedia di salah satu lokasi tersebut, tombol download di Dasbor Pemagang (`/intern/dashboard`) akan otomatis siap diunduh!
