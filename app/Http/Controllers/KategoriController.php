<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;  
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    public function index()
    {
        /* $data = [
            'kategori_kode' => 'SNK',
            'kategori_nama' => 'Snack/Makanan Ringan',
            'created_at' => now()
        ];
        DB::table('m_kategori')->insert($data);
        return 'Insert data baru berhasil'; */

        //$row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        //return 'Update data berhasil. Jumlah data yang diupdate: ' . $row.' baris';

        //$row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        //return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row.' baris';
    
        //$data = DB::table('m_kategori')->get();
        // return view('kategori', ['data' => $data]);
    
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list'  => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];
        $activeMenu = 'kategori'; // set menu yang sedang aktif

        $kategori = KategoriModel::all();

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'      => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $kategori = KategoriModel::all();

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id)) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id) . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id)) . '">
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
            'title' => 'Tambah Kategori',
            'list'  => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $kategori = KategoriModel::all(); 
        $activeMenu = 'kategori'; 

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request){
    // Validasi input
    $request->validate([
        'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode',
        'kategori_nama' => 'required|string|max:100',
    ]);

    // Membuat data user baru dengan data yang sudah divalidasi
    KategoriModel::create([
        'kategori_kode' => $request->kategori_kode,
        'kategori_nama' => $request->kategori_nama,
    ]);

    // Redirect ke halaman user dengan pesan sukses
    return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    // menampilkan detail user
    public function show(string $id)
{
    // Ambil data user beserta levelnya berdasarkan ID
    $kategori = KategoriModel::find($id);

    // Siapkan data untuk breadcrumb (navigasi)
    $breadcrumb = (object) [
        'title' => 'Detail Kategori',
        'list' => ['Home', 'Kategori', 'Detail']
    ];

    // Siapkan data untuk halaman (page)
    $page = (Object) [
        'title' => 'Detail Kategori'
    ];

    // Set menu yang aktif
    $activeMenu = 'kategori';

    // Kembalikan view 'user.show' dengan data yang telah disiapkan
    return view('kategori.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'kategori' => $kategori,
        'activeMenu' => $activeMenu
    ]);
}

    // Fungsi untuk menampilkan form edit user
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (Object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Fungsi untuk menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        // Validasi input dari user
        $request->validate([
            'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100'
        ]);

        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    // Fungsi untuk menghapus data pengguna berdasarkan ID
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);

        if (!$check) {
            return redirect('/kategori')->with('error', "Data kategori tidak ditemukan");
        }

        try {
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (QueryException $e) { 

            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}