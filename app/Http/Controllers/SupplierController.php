<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;  
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];
        $activeMenu = 'supplier';
        $supplier = SupplierModel::all();

        return view('supplier.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'supplier' => $supplier, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $supplier = SupplierModel::all();
        return DataTables::of($supplier)
        
        ->addIndexColumn()
        ->addColumn('aksi', function ($supplier) {
            /*$btn = '<a href="' . url('/supplier/' . htmlspecialchars($supplier->supplier_id)) . '" class="btn btn-info btn-sm">Detail</a> ';
            $btn .= '<a href="' . url('/supplier/' . htmlspecialchars($supplier->supplier_id) . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . htmlspecialchars($supplier->supplier_id)) . '">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>
                    </form>';*/

            $btn  = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
            '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
            '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
            '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];
        $supplier = SupplierModel::all();
        $activeMenu = 'supplier';

        return view('supplier.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu, 
            'supplier' => $supplier]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required|string|min:2|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:255',
            'supplier_telepon' => 'required|string|max:15',
        ]);

        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
            'supplier_telepon' => $request->supplier_telepon
        ]);
        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail supplier'
        ];
        $activeMenu = 'supplier';

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'supplier' => $supplier, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit supplier'
        ];
        $activeMenu = 'supplier';

        return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_kode' => 'required|string|min:2|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:255',
            'supplier_telepon' => 'required|string|max:15',

        ]);
        SupplierModel::find($id)->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
            'suplier_telepon' => $request->supplier_telepon
        ]);

        return redirect('/supplier')->with('success', "Data supplier berhasil diubah");
    }

    public function destroy(string $id)
    {
        $check = SupplierModel::find($id);

        if (!$check) {
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }
        
        try {
            SupplierModel::destroy($id);
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }

    public function create_ajax()
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();

        return view('supplier.create_ajax')->with('supplier', $supplier);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|min:2|max:10|unique:m_supplier,supplier_kode',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:255',
                'supplier_telepon' => 'required|string|max:15',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            SupplierModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function export_pdf()
{
    $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_telepon')->get();

    $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
    $pdf->setPaper('a4', 'portrait'); // Perbaikan dari "potrait" menjadi "portrait"
    $pdf->setOption("isRemoteEnabled", true);
    $pdf->render();
    // Stream hasil PDF
    return $pdf->stream('Data_Supplier_' . date('Y-m-d_H:i:s') . '.pdf');
}


    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|min:2|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:255',
                'supplier_telepon' => 'required|string|max:15',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  
                ]);
            }
            $check = SupplierModel::find($id);

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
        $supplier = SupplierModel::find($id);

        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);

            if ($supplier) {
                $supplier->delete();
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
        return view('supplier.import');
    }
    public function import_ajax(Request $request)
    {
        // Mengecek apakah request berasal dari AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input file
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024'] // hanya file .xlsx dengan max size 1MB
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
            $file = $request->file('file_supplier');
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
                            'supplier_kode' => $value['A'],
                            'supplier_nama' => $value['B'],
                            'supplier_alamat' => $value['C'], 
                            'supplier_telepon' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                // Insert ke database jika ada data yang diimport
                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert); // Insert data, abaikan jika ada duplikasi
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
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_telepon')
            ->orderBy('supplier_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Supplier Kode');
        $sheet->setCellValue('C1', 'Supplier Nama');
        $sheet->setCellValue('D1', 'Supplier Alamat');
        $sheet->setCellValue('E1', 'Supplier Telepon');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($supplier as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier_kode);
            $sheet->setCellValue('C' . $baris, $value->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->supplier_alamat);
            $sheet->setCellValue('E' . $baris, $value->supplier_telepon);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Supplier');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier' . date('Y-m-d H:i:s') . '.xlsx';
        
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