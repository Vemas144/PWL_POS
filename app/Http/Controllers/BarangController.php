<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    { 
      $activeMenu = 'barang';
      $breadcrumb = (object) [
          'title' => 'Daftar Barang',
          'list' => ['Home', 'Barang']
      ];

      $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'kategori' => $kategori
        ]);
    }

     
    public function list (Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')
            ->with('kategori');
        
        $kategori_id = $request->input('filter_kategori');
            if(!empty($kategori_id)){
            $barang->where('kategori_id', $kategori_id);
        }
            

        return DataTables::of($barang)
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
               /* $btn = '<a href="'.url('/barang/'.$barang->barang_id).'" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="'.url('/barang/'.$barang->barang_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/barang/'.$barang->barang_id).'">'.
                            csrf_field().
                            method_field('DELETE').
                            '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>'.
                        '</form>';*/
                        //$btn = '<a href="'.url('/barang/'.$barang->barang_id).'" class="btn btn-info btn-sm">Detail</a>';
                        $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
                        '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                        $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
                        '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                        $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
                        '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Barang Baru',
        ];

        $kategori = KategoriModel::all(); 
        $activeMenu = 'barang'; //set menu yang sedang aktif

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori'=>$kategori, 'activeMenu' => $activeMenu]);
    }

     public function store(Request $request, string $id)
     {
       $request->validate([
           'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
           'barang_nama' => 'required|string|max:100', 
           'harga_beli' => 'required|integer', 
           'harga_jual' => 'required|integer', 
           'kategori_id' => 'required|integer',
       ]);
   
       BarangModel::create([
           'barang_kode' => $request->barang_kode,
           'barang_nama' => $request->barang_nama,
           'harga_beli' => $request->harga_beli,
           'harga_jual' => $request->harga_jual,
           'kategori_id' => $request->kategori_id
       ]);
   
       return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
     }

   
    public function show($id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Barang',
        ];

        $activeMenu = 'barang'; //set menu yang sedang aktif

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    
    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    
    public function update(Request $request, string $id)
    {   
       
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100', 
            'harga_beli' => 'required|integer', 
            'harga_jual' => 'required|integer', 
            'kategori_id' => 'required|integer', 
        ]);

        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

           
    public function destroy(string $id)
    {
        $check = BarangModel::find($id);

        if (!$check) { 
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            BarangModel::destroy($id); 
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.create_ajax')
        ->with('kategori', $kategori);
    }

    public function store_ajax(Request $request)
    {
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                    'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                    'barang_kode' => ['required', 'min:3', 'max:20',
                    'unique:m_barang,barang_kode'],
                    'barang_nama' => ['required', 'string', 'max:100'],
                    'harga_beli' => ['required', 'numeric'],
                    'harga_jual' => ['required', 'numeric'],         
            ];

            //use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => False, 
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BarangModel::create($request->all());
            return response()->json([
                'status' => True, 
                'message' => 'Data barang berhasil disimpan'
            ]);
        }  
        redirect('/');
    }

    public function edit_ajax(string $id){
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'barang_kode' => ['required', 'min:3', 'max:20',
                'unique:m_barang,barang_kode, '. $id .',barang_id'],
                'barang_nama' => ['required', 'string', 'max:100'],
                'harga_beli' => ['required', 'numeric'],
                'harga_jual' => ['required', 'numeric']
            ];
        // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, // respon json, true: berhasil, false: gagal
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
        }
        $check = BarangModel::find($id);
        if ($check) {
            $check->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else{
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $barang = BarangModel::find($id);
         return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax (Request $request, $id){
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $barang = BarangModel::find($id);
        if ($barang) {
            $barang->delete();
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
    }
    return redirect("/");
    }

    public function show_ajax($id){
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::find($barang->kategori_id);
        return view('barang.show_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    public function import(){
        return view('barang.import');
    }

    public function import_ajax(Request $request) 
     { 
         if($request->ajax() || $request->wantsJson()){ 
             $rules = [ 
                 'file_barang' => ['required', 'mimes:xlsx', 'max:1024'] 
             ]; 
  
             $validator = Validator::make($request->all(), $rules); 
             if($validator->fails()){ 
                 return response()->json([ 
                     'status' => false, 
                     'message' => 'Validasi Gagal', 
                     'msgField' => $validator->errors() 
                 ]); 
             } 
  
             $file = $request->file('file_barang');
  
             $reader = IOFactory::createReader('Xlsx');
             $reader->setReadDataOnly(true);   
             $spreadsheet = $reader->load($file->getRealPath()); 
             $sheet = $spreadsheet->getActiveSheet();    
  
             $data = $sheet->toArray(null, false, true, true); 
  
             $insert = []; 
             if(count($data) > 1){
                 foreach ($data as $baris => $value) { 
                     if($baris > 1){ 
                         $insert[] = [ 
                             'kategori_id' => $value['A'], 
                             'barang_kode' => $value['B'], 
                             'barang_nama' => $value['C'], 
                             'harga_beli' => $value['D'], 
                             'harga_jual' => $value['E'], 
                             'created_at' => now(), 
                         ]; 
                     } 
                 } 
  
                 if(count($insert) > 0){ 
                     // Insert data ke database
                     BarangModel::insertOrIgnore($insert);    
                 } 
  
                 return response()->json([ 
                     'status' => true, 
                     'message' => 'Data berhasil diimport' 
                 ]); 
             }else{ 
                 return response()->json([ 
                     'status' => false, 
                     'message' => 'Tidak ada data yang diimport' 
                 ]); 
             } 
         } 
         return redirect('/'); 
    }

    public function export_excel(){
        //ambil data barang yang akan di export
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
                      ->orderBy('kategori_id')
                      ->with('kategori')
                      ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

        
        $no = 1;    // noror data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->barang_kode);
            $sheet->setCellValue('C'.$baris, $value->barang_nama);
            $sheet->setCellValue('D'.$baris, $value->harga_beli);
            $sheet->setCellValue('E'.$baris, $value->harga_jual);
            $sheet->setCellValue('F'.$baris, $value->kategori->kategori_nama);  // ambil nama kategori
            $baris++;
            $no++;
        }

        foreach(range('A','F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // auto set auto size untuk kolom
        }
        $sheet->setTitle('Data Barang'); // set title sheet

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Barang '.date('Y-m-d H:i:s').'.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer->save('php://output');
            exit;
    }

    public function export_pdf(){
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
                        ->orderBy('kategori_id')
                        ->orderBy('barang_kode')
                        ->with('kategori')
                        ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    }
}
