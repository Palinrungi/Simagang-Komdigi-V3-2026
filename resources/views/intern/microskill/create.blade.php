@extends('layouts.app')

@section('title', 'Upload Bukti Mikro Skill - Sistem Magang')

@push('styles')

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

*{
    font-family:'Plus Jakarta Sans',sans-serif;
}

body.page-microskill{
    background:linear-gradient(135deg,#edf4ff,#f8fbff,#eef5ff);
}

/* HERO */

.hero-strip{
    background:linear-gradient(120deg,#0f2878,#2945d9,#4f46e5);
    border-radius:24px;
    overflow:hidden;
    position:relative;
}

.hero-strip::before{
    content:'';
    position:absolute;
    width:320px;
    height:320px;
    right:-90px;
    top:-120px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
}

.hero-strip::after{
    content:'';
    position:absolute;
    width:280px;
    height:280px;
    left:20%;
    bottom:-150px;
    border-radius:50%;
    background:rgba(255,255,255,.04);
}

.card-box{
    background:white;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 15px 35px rgba(30,64,175,.08);
}

/* ========= SEARCH ========= */

/* ==========================================
   TOM SELECT MODERN
========================================== */

.ts-wrapper{
    width:100%;
}

.ts-control{
    display:flex!important;
    align-items:center;
    flex-wrap:wrap;
    gap:8px;
    min-height:60px;
    padding:10px 16px!important;
    border:2px solid #dbeafe!important;
    border-radius:18px!important;
    background:#fff!important;
    box-shadow:none!important;
    transition:.25s;
}

.ts-control.focus{
    border-color:#2563eb!important;
    box-shadow:0 0 0 5px rgba(37,99,235,.12)!important;
}

/* INPUT PENCARIAN */

.ts-control input{
    flex:1 1 auto!important;
    width:100%!important;
    min-width:220px!important;
    font-size:15px!important;
    color:#111827!important;
    padding:6px 0!important;
    margin:0!important;
    border:none!important;
    background:transparent!important;
}

.ts-control input::placeholder{
    color:#94a3b8!important;
}

/* item */

.ts-control .item{
    background:#2563eb!important;
    color:#fff!important;
    border-radius:10px!important;
    padding:5px 12px!important;
}

/* dropdown */

.ts-dropdown{
    margin-top:8px;
    border:none!important;
    border-radius:18px!important;
    overflow:hidden;
    box-shadow:0 20px 45px rgba(0,0,0,.15);
}

.ts-dropdown-content{
    max-height:320px;
    overflow:auto;
}

.ts-dropdown .option{
    padding:14px 18px;
}

.ts-dropdown .active{
    background:#2563eb!important;
    color:white!important;
}

.preview-card{
    display:none;
    margin-top:20px;
    border-radius:18px;
    border:1px solid #dbeafe;
    background:linear-gradient(135deg,#eff6ff,#ffffff);
    padding:22px;
}

.preview-icon{
    width:60px;
    height:60px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#2563eb;
    color:white;
    font-size:22px;
}

.btn-open{

    display:inline-flex;
    align-items:center;
    gap:10px;
    margin-top:14px;
    background:#2563eb;
    color:white;
    padding:10px 18px;
    border-radius:12px;
    text-decoration:none;
    transition:.25s;
}

.btn-open:hover{

    background:#1d4ed8;
    color:white;
}

@media(max-width:768px){

.hero-strip h1{

font-size:28px;

}

.preview-flex{

flex-direction:column;

}

}
.ts-control > input{
    width:100%!important;
    min-width:250px!important;
    flex:1!important;
}

.ts-wrapper.single .ts-control input{
    width:100%!important;
}

.ts-control.has-items input{
    min-width:220px!important;
}

.ts-control input:focus{
    outline:none!important;
}
</style>

@endpush

@section('content')

<div class="min-h-screen py-8">

<div class="max-w-5xl mx-auto px-5">

<div class="hero-strip mb-7">

<div class="relative z-10 px-8 py-8 flex justify-between items-center flex-wrap gap-4">

<div>

<h1 class="text-3xl font-bold text-white">
Upload Bukti Mikro Skill
</h1>

<p class="text-blue-100 mt-2">
Pilih Mikro Skill yang telah diselesaikan kemudian unggah bukti penyelesaiannya.
</p>

</div>

<a href="{{ route('intern.microskill.index') }}"
class="px-5 py-3 rounded-xl bg-white text-blue-700 font-semibold shadow">

<i class="fas fa-arrow-left mr-2"></i>

Kembali

</a>

</div>

</div>

<div class="card-box">

<div class="bg-blue-700 px-7 py-5">

<h2 class="text-white text-xl font-bold">

<i class="fas fa-graduation-cap mr-2"></i>

Data Mikro Skill

</h2>

</div>

<form
method="POST"
action="{{ route('intern.microskill.store') }}"
enctype="multipart/form-data"
class="p-8 space-y-7">

@csrf

<div>

<label class="block font-semibold text-gray-700 mb-3">

Pilih Mikro Skill

<span class="text-red-500">*</span>

</label>

<select
id="micro_skill_select"
name="title"
required>

<option value="">Cari Mikro Skill...</option>

@foreach($microSkills as $skill)

<option
value="{{ $skill->judul_micro }}"
data-link="{{ $skill->link_micro }}"
{{ old('title',$suggestedTitle ?? '')==$skill->judul_micro?'selected':'' }}>

{{ $skill->judul_micro }}

</option>

@endforeach

</select>

@error('title')

<p class="text-red-600 mt-2">

{{ $message }}

</p>

@enderror

<div
id="skillPreview"
class="preview-card">

<div class="flex preview-flex gap-5">

<div class="preview-icon">

<i class="fas fa-book-open"></i>

</div>

<div class="flex-1">

<h3
id="previewTitle"
class="text-xl font-bold text-gray-800">
</h3>

<p class="text-gray-500 mt-2">

Digital Talent • Komdigi

</p>

<a
id="previewLink"
target="_blank"
class="btn-open">

<i class="fas fa-external-link-alt"></i>

Buka Pelatihan

</a>

</div>

</div>

</div>

</div>

<!-- ========================= -->
<!-- Deskripsi -->
<!-- ========================= -->

<div>

    <label class="block font-semibold text-gray-700 mb-3">

        <i class="fas fa-align-left mr-2 text-blue-600"></i>

        Deskripsi

        <span class="text-gray-400 text-sm">(Opsional)</span>

    </label>

    <textarea
        name="description"
        rows="5"
        placeholder="Contoh: Saya telah menyelesaikan pelatihan ini dan memperoleh sertifikat kelulusan."
        class="w-full rounded-2xl border-2 border-blue-100 focus:border-blue-500 focus:ring-blue-500 px-5 py-4 resize-none transition">{{ old('description') }}</textarea>

    @error('description')

        <p class="mt-2 text-red-600 text-sm">

            {{ $message }}

        </p>

    @enderror

</div>

<!-- ========================= -->
<!-- Upload Bukti -->
<!-- ========================= -->

<div>

<label class="block font-semibold text-gray-700 mb-4">

<i class="fas fa-camera mr-2 text-blue-600"></i>

Upload Bukti

<span class="text-red-500">*</span>

</label>

<div
id="uploadArea"
class="relative border-2 border-dashed border-blue-300 rounded-2xl bg-blue-50 hover:bg-blue-100 transition cursor-pointer p-10 text-center">

<input
type="file"
name="photo"
id="photo-input"
accept="image/*"
required
class="absolute inset-0 opacity-0 cursor-pointer">

<div>

<div class="mx-auto w-20 h-20 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl">

<i class="fas fa-cloud-upload-alt"></i>

</div>

<h3 class="mt-5 text-lg font-bold text-gray-800">

Klik atau Drag & Drop

</h3>

<p class="text-gray-500 mt-2">

Format JPG atau PNG

</p>

<p class="text-gray-400 text-sm mt-1">

Ukuran maksimal 4 MB

</p>

</div>

</div>

@error('photo')

<p class="mt-3 text-red-600 text-sm">

{{ $message }}

</p>

@enderror

</div>

<!-- ========================= -->
<!-- Preview Foto -->
<!-- ========================= -->

<div
id="imagePreview"
style="display:none">

<label class="block font-semibold text-gray-700 mb-3">

Preview Bukti

</label>

<div class="rounded-2xl overflow-hidden border border-blue-200 shadow">

<img
id="previewImage"
class="w-full max-h-[450px] object-contain bg-gray-50">

</div>

</div>

<!-- ========================= -->
<!-- Informasi -->
<!-- ========================= -->

<div
class="rounded-2xl bg-blue-50 border border-blue-200 p-5">

<div class="flex gap-3">

<div class="text-blue-600 text-xl">

<i class="fas fa-circle-info"></i>

</div>

<div>

<h4 class="font-bold text-blue-800">

Informasi

</h4>

<ul class="text-sm text-blue-700 mt-2 space-y-1">

<li>• Pilih Mikro Skill yang sudah selesai.</li>

<li>• Pastikan bukti yang diunggah jelas.</li>

<li>• Bukti dapat berupa sertifikat atau screenshot kelulusan.</li>

<li>• Ukuran file maksimal 4 MB.</li>

</ul>

</div>

</div>

</div>

<!-- ========================= -->
<!-- Tombol -->
<!-- ========================= -->

<div
class="border-t border-gray-200 pt-7 flex flex-col sm:flex-row justify-between items-center gap-4">

<a
href="{{ route('intern.microskill.index') }}"
class="text-blue-700 font-semibold hover:text-blue-900">

<i class="fas fa-arrow-left mr-2"></i>

Kembali

</a>

<button
type="submit"
class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition">

<i class="fas fa-paper-plane mr-2"></i>

Kirim Bukti

</button>

</div>

</form>

</div>

</div>

</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>

document.body.classList.add('page-microskill');

/* ===============================
   Tom Select
================================ */

const ts = new TomSelect("#micro_skill_select",{

    create:false,

    searchField:["text"],

    valueField:"value",

    labelField:"text",

    maxOptions:1000,

    allowEmptyOption:true,

    hideSelected:false,

    closeAfterSelect:true,

    placeholder:"Ketik nama Mikro Skill...",

    render:{

        option:function(data,escape){

            return `

            <div class="flex gap-3 items-start py-2">

                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">

                    <i class="fas fa-book text-blue-600"></i>

                </div>

                <div class="overflow-hidden">

                    <div class="font-semibold text-gray-800 break-words">

                        ${escape(data.text)}

                    </div>

                    <div class="text-xs text-gray-500">

                        Digital Talent Komdigi

                    </div>

                </div>

            </div>

            `;

        },

        item:function(data,escape){

            return `

            <div class="truncate">

                ${escape(data.text)}

            </div>

            `;

        }

    }

});

const inputSearch = ts.control_input;

inputSearch.style.width = "100%";
inputSearch.style.minWidth = "250px";
inputSearch.style.flex = "1";
inputSearch.style.padding = "6px 0";


/* ===============================
   Preview Mikro Skill
================================ */

const select=document.getElementById('micro_skill_select');

const preview=document.getElementById('skillPreview');

function updatePreview(){

    const option=select.options[select.selectedIndex];

    if(!option || option.value==""){

        preview.style.display="none";

        return;

    }

    preview.style.display="block";

    document.getElementById("previewTitle").innerHTML=option.text;

    document.getElementById("previewLink").href=option.dataset.link;

}

select.addEventListener("change",updatePreview);

window.addEventListener("load",updatePreview);


/* ===============================
   Preview Foto
================================ */

const fileInput=document.getElementById("photo-input");

const imagePreview=document.getElementById("imagePreview");

const previewImage=document.getElementById("previewImage");

fileInput.addEventListener("change",function(){

    if(!this.files.length){

        imagePreview.style.display="none";

        return;

    }

    const reader=new FileReader();

    reader.onload=function(e){

        previewImage.src=e.target.result;

        imagePreview.style.display="block";

    }

    reader.readAsDataURL(this.files[0]);

});


/* ===============================
   Drag & Drop
================================ */

const uploadArea=document.getElementById("uploadArea");

["dragenter","dragover"].forEach(eventName=>{

    uploadArea.addEventListener(eventName,function(e){

        e.preventDefault();

        uploadArea.classList.remove("border-blue-300");

        uploadArea.classList.add("border-blue-600","bg-blue-100");

    });

});

["dragleave","drop"].forEach(eventName=>{

    uploadArea.addEventListener(eventName,function(e){

        e.preventDefault();

        uploadArea.classList.remove("border-blue-600","bg-blue-100");

        uploadArea.classList.add("border-blue-300");

    });

});

uploadArea.addEventListener("drop",function(e){

    const files=e.dataTransfer.files;

    fileInput.files=files;

    fileInput.dispatchEvent(new Event("change"));

});


/* ===============================
   Animasi
================================ */

document.querySelector("form").addEventListener("submit",function(){

    const btn=this.querySelector("button[type=submit]");

    btn.disabled=true;

    btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

});

</script>

@endpush

@endsection