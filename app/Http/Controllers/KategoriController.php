<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;


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
                /*$btn = '<a href="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id)) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id) . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . htmlspecialchars($kategori->kategori_id)) . '">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>
                        </form>';*/

                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
    
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

    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('kategori.create_ajax')->with('kategori', $kategori);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);

            }
            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function export_pdf()
{
    $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')->get();

    $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
    $pdf->setPaper('a4', 'portrait'); // Perbaikan dari "potrait" menjadi "portrait"
    $pdf->setOption("isRemoteEnabled", true);
    $pdf->render();
    // Stream hasil PDF
    return $pdf->stream('Data_Kategori_' . date('Y-m-d_H:i:s') . '.pdf');
}


    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|max:100',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = KategoriModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);

            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            return redirect('/');
        }
    }

    public function import()
    {
        return view('kategori.import');
    }
    public function import_ajax(Request $request)
    {
        // Mengecek apakah request berasal dari AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input file
            $rules = [
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024'] // hanya file .xlsx dengan max size 1MB
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Memproses file
            $file = $request->file('file_kategori');
            $reader = IOFactory::createReader('Xlsx'); // Hanya mendukung xlsx sesuai validasi
            $reader->setReadDataOnly(true); // Hanya baca data
            $spreadsheet = $reader->load($file->getRealPath()); // Baca file dari path
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data dalam bentuk array

            $insert = [];

            // Jika data lebih dari 1 baris (berarti ada data selain header)
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Lewati baris pertama (header)
                        $insert[] = [
                            'kategori_kode' => $value['C'], 
                            'kategori_nama' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                // Insert ke database jika ada data yang diimport
                if (count($insert) > 0) {
                    KategoriModel::insertOrIgnore($insert); // Insert data, abaikan jika ada duplikasi
                }

                // Response sukses
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                // Response jika tidak ada data
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        // Jika bukan request AJAX
        return redirect('/');
    }

    public function export_excel()
    {
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kategori Kode');
        $sheet->setCellValue('C1', 'Kategori Nama');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kategori_kode);
            $sheet->setCellValue('C' . $baris, $value->kategori_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Kategori');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori' . date('Y-m-d H:i:s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, dMY H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        
        $writer->save('php://output');
        exit;
    }
}