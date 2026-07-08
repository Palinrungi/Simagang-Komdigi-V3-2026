<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industri;
use App\Models\Institusi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminMitraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jenis = $request->get('jenis', 'semua');

        $dataMitra = collect();

        if ($jenis === 'semua' || $jenis === 'industri') {
            $industri = Industri::with('user')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_industri', 'like', '%' . $search . '%')
                            ->orWhere('email_industri', 'like', '%' . $search . '%')
                            ->orWhere('bidang_industri', 'like', '%' . $search . '%')
                            ->orWhere('kota_kabupaten', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('email', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'id' => $item->id,
                        'jenis_key' => 'industri',
                        'nama' => $item->nama_industri ?? '-',
                        'email' => $item->email_industri ?? optional($item->user)->email ?? '-',
                        'jenis' => 'Industri',
                        'kategori' => $item->bidang_industri ?? '-',
                        'kontak' => $item->nomor_telepon_industri ?? '-',
                        'is_active' => (bool) ($item->is_active ?? true),
                        'created_at' => $item->created_at,
                    ];
                });

            $dataMitra = $dataMitra->merge($industri);
        }

        if ($jenis === 'semua' || $jenis === 'institusi') {
            $institusi = Institusi::with('user')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_institusi', 'like', '%' . $search . '%')
                            ->orWhere('jenis_institusi', 'like', '%' . $search . '%')
                            ->orWhere('no_hp', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('email', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'id' => $item->id,
                        'jenis_key' => 'institusi',
                        'nama' => $item->nama_institusi ?? '-',
                        'email' => optional($item->user)->email ?? '-',
                        'jenis' => 'Institusi',
                        'kategori' => ucfirst($item->jenis_institusi ?? '-'),
                        'kontak' => $item->no_hp ?? '-',
                        'is_active' => (bool) ($item->is_active ?? true),
                        'created_at' => $item->created_at,
                    ];
                });

            $dataMitra = $dataMitra->merge($institusi);
        }

        $dataMitra = $dataMitra->sortByDesc('created_at')->values();

        $perPage = 10;
        $page = (int) $request->get('page', 1);

        $mitra = new LengthAwarePaginator(
            $dataMitra->forPage($page, $perPage),
            $dataMitra->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.mitra.index', compact('mitra'));
    }

    public function create()
{
    return view('admin.mitra.create');
}

public function store(Request $request)
{
    $request->validate([
        'jenis_mitra' => ['required', Rule::in(['institusi', 'industri'])],

        'nama_admin' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'no_hp' => ['nullable', 'string', 'max:50'],
        'password' => [
            'required',
            Password::min(8)->mixedCase()->numbers()->symbols(),
        ],

        'nama_institusi' => [
            Rule::requiredIf($request->jenis_mitra === 'institusi'),
            'nullable',
            'string',
            'max:255',
        ],
        'jenis_institusi' => [
            Rule::requiredIf($request->jenis_mitra === 'institusi'),
            'nullable',
            Rule::in(['sekolah', 'kampus']),
        ],
        'nomor_identitas' => ['nullable', 'string', 'max:100'],

        'nama_industri' => [
            Rule::requiredIf($request->jenis_mitra === 'industri'),
            'nullable',
            'string',
            'max:255',
        ],
        'bidang_industri' => [
            Rule::requiredIf($request->jenis_mitra === 'industri'),
            'nullable',
            'string',
            'max:255',
        ],
        'alamat_industri' => ['nullable', 'string'],
        'kota_kabupaten' => ['nullable', 'string', 'max:255'],
    ]);

    DB::transaction(function () use ($request) {
        $role = $request->jenis_mitra === 'institusi' ? 'institusi' : 'industri';

        $user = User::create([
            'name' => $request->nama_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        try {
            if (method_exists($user, 'syncRoles')) {
                $user->syncRoles([$role]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika role Spatie belum dibuat.
        }

        if ($request->jenis_mitra === 'institusi') {
            Institusi::create([
                'user_id' => $user->id,
                'nama_institusi' => $request->nama_institusi,
                'jenis_institusi' => $request->jenis_institusi,
                'nomor_identitas' => $request->nomor_identitas,
                'no_hp' => $request->no_hp,
                'is_active' => true,
            ]);
        }

        if ($request->jenis_mitra === 'industri') {
            Industri::create([
                'user_id' => $user->id,
                'nama_industri' => $request->nama_industri,
                'bidang_industri' => $request->bidang_industri,
                'deskripsi_industri' => null,
                'alamat_industri' => $request->alamat_industri ?? '-',
                'kota_kabupaten' => $request->kota_kabupaten ?? '-',
                'email_industri' => $request->email,
                'nomor_telepon_industri' => $request->no_hp ?? '-',
                'nib' => null,
                'status' => 'disetujui',
                'is_active' => true,
            ]);
        }
    });

    return redirect()
        ->route('admin.mitra.index')
        ->with('success', 'Data mitra dan akun admin berhasil ditambahkan.');
}

    public function edit($jenis, $id)
    {
        if ($jenis === 'industri') {
            $mitra = Industri::with('user')->findOrFail($id);

            return view('admin.mitra.edit', [
                'jenis' => 'industri',
                'mitra' => $mitra,
            ]);
        }

        if ($jenis === 'institusi') {
            $mitra = Institusi::with('user')->findOrFail($id);

            return view('admin.mitra.edit', [
                'jenis' => 'institusi',
                'mitra' => $mitra,
            ]);
        }

        abort(404);
    }

    public function update(Request $request, $jenis, $id)
    {
        if ($jenis === 'industri') {
            $mitra = Industri::with('user')->findOrFail($id);
        } elseif ($jenis === 'institusi') {
            $mitra = Institusi::with('user')->findOrFail($id);
        } else {
            abort(404);
        }

        $userId = optional($mitra->user)->id;

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'kategori' => ['nullable', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($jenis === 'industri') {
            $rules['email'][] = Rule::unique('industris', 'email_industri')->ignore($mitra->id);
        }

        $validated = $request->validate($rules);

        if ($jenis === 'industri') {
            $mitra->nama_industri = $validated['nama'];
            $mitra->bidang_industri = $validated['kategori'] ?? null;
            $mitra->nomor_telepon_industri = $validated['kontak'] ?? null;
            $mitra->is_active = $request->boolean('is_active');

            if ($request->filled('email')) {
                $mitra->email_industri = $validated['email'];
            }

            $mitra->save();

            if ($mitra->user) {
                $userData = [
                    'name' => $validated['nama'],
                ];

                if ($request->filled('email')) {
                    $userData['email'] = $validated['email'];
                }

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $mitra->user->update($userData);
            }

            return redirect()
                ->route('admin.mitra.index')
                ->with('success', 'Data mitra industri berhasil diperbarui.');
        }

        if ($jenis === 'institusi') {
            $mitra->nama_institusi = $validated['nama'];
            $mitra->jenis_institusi = $validated['kategori'] ?? null;
            $mitra->no_hp = $validated['kontak'] ?? null;
            $mitra->is_active = $request->boolean('is_active');
            $mitra->save();

            if ($mitra->user) {
                $userData = [
                    'name' => $validated['nama'],
                ];

                if ($request->filled('email')) {
                    $userData['email'] = $validated['email'];
                }

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $mitra->user->update($userData);
            }

            return redirect()
                ->route('admin.mitra.index')
                ->with('success', 'Data mitra institusi berhasil diperbarui.');
        }

        abort(404);
    }

    public function destroy($jenis, $id)
    {
        if ($jenis === 'industri') {
            $mitra = Industri::with('user')->findOrFail($id);
            $user = $mitra->user;

            $mitra->delete();

            if ($user) {
                $user->delete();
            }

            return redirect()
                ->route('admin.mitra.index')
                ->with('success', 'Data mitra industri berhasil dihapus.');
        }

        if ($jenis === 'institusi') {
            $mitra = Institusi::with('user')->findOrFail($id);
            $user = $mitra->user;

            $mitra->delete();

            if ($user) {
                $user->delete();
            }

            return redirect()
                ->route('admin.mitra.index')
                ->with('success', 'Data mitra institusi berhasil dihapus.');
        }

        abort(404);
    }
}