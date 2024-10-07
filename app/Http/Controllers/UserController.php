<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;
use App\Models\LevelModel;  
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\RedirectResponse;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index1()
    {
        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);
    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request){
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);
        
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->password = Hash::make('$request->password');
            $user->level_id = $request->level_id;
        
            $user->save();

            return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        // Ambil data user dari model User, pilih kolom user_id, username, name, dan level_id
        // serta relasi dengan model Level
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
                        ->with('level');

        // Menggunakan DataTables untuk menampilkan data user
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="' . url('/user/' . htmlspecialchars($user->user_id)) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/user/' . htmlspecialchars($user->user_id) . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/' . htmlspecialchars($user->user_id)) . '">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>
                        </form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // This should now work without error
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'level'      => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request){
    // Validasi input
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username', // Username harus diisi, minimal 3 karakter, dan unik
        'nama' => 'required|string|max:100', // Nama harus diisi, maksimal 100 karakter
        'password' => 'required|string|min:5', // Password harus diisi, minimal 5 karakter
        'level_id' => 'required|integer' // Level ID harus diisi dan berupa angka
    ]);

    // Membuat data user baru dengan data yang sudah divalidasi
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => bcrypt($request->password), // Mengenkripsi password sebelum disimpan
        'level_id' => $request->level_id,
    ]);

    // Redirect ke halaman user dengan pesan sukses
    return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // menampilkan detail user
    public function show(string $id)
{
    // Ambil data user beserta levelnya berdasarkan ID
    $user = UserModel::with('level')->find($id);

    // Siapkan data untuk breadcrumb (navigasi)
    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => ['Home', 'User', 'Detail']
    ];

    // Siapkan data untuk halaman (page)
    $page = (Object) [
        'title' => 'Detail User'
    ];

    // Set menu yang aktif
    $activeMenu = 'user';

    // Kembalikan view 'user.show' dengan data yang telah disiapkan
    return view('user.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'activeMenu' => $activeMenu
    ]);
}

    // Fungsi untuk menampilkan form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (Object) [
            'title' => 'Edit User'
        ];

        $activeMenu = 'user';

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Fungsi untuk menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        // Validasi input dari user
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            "username" => "required|string|min:3|unique:m_user,username,$id,user_id",
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|string|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            "level_id" => 'required|integer' // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            "level_id" => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Fungsi untuk menghapus data pengguna berdasarkan ID
    public function destroy(string $id)
    {
        $check = UserModel::find($id);

        if (!$check) {
            return redirect('/user')->with('error', "Data user tidak ditemukan");
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (QueryException $e) { 

            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
