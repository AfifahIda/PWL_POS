<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;


class LevelController extends Controller
{
    public function index()
    {
        //DB::insert('insert into m_level(level_id, level_kode, level_nama, created_at) values(?, ?, ?, ?)', [4, 'CUS', 'Customer-1', now()]);
        //return 'Insert data baru berhasil';

        //$row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
        //return 'Update data berhasil. Jumlah data yang diupdate: ' . $row.' baris';

        //$row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
        //return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row.' baris';
        
        //$data = DB::select('select * from m_level');
        //return view('level', ['data' => $data]);
        //return view('level', ['level' => $level]);

        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';
        $level = LevelModel::all();

        return view('level.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu
        ]);
    }


    public function list(Request $request)
    {
        $level = LevelModel::all();
        return DataTables::of($level)
        
        ->addIndexColumn()
        ->addColumn('aksi', function ($level) {
            $btn = '<a href="' . url('/level/' . htmlspecialchars($level->level_id)) . '" class="btn btn-info btn-sm">Detail</a> ';
            $btn .= '<a href="' . url('/level/' . htmlspecialchars($level->level_id) . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . htmlspecialchars($level->level_id)) . '">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>
                    </form>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah level baru'
        ];
        $level = LevelModel::all();
        $activeMenu = 'level';
        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ]);
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }
    public function show(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail level'
        ];
        $activeMenu = 'level';
        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function edit(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit level'
        ];
        $activeMenu = 'level';
        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3|max:10|unique:m_level,level_kode,' . $id .',level_id',
            'level_nama' => 'required|string|max:100',
        ]);
        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', "Data level berhasil diubah");
    }
    public function destroy(string $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }
        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}