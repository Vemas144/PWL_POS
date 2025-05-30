<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{
    public function index()
    {
        $activeMenu = 'level';
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $level = LevelModel::all(); // Ambil data dengan model
        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'level' => $level
        ]);
    }
    
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        if ($request->level_nama) {
            $level = $level->where('level_nama', 'like', '%' . $request->level_nama . '%');
        }
        

        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                /*$btn = '<a href="' . url('level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('level/' . $level->level_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                    </form>';*/
                    //$btn = '<a href="'.url('/level/'.$level->level_id).'" class="btn btn-info btn-sm">Detail</a>';
                        $btn = '<button onclick="modalAction(\''.url('/level/' . $level->level_id .
                        '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                        $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id .
                        '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                        $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id .
                        '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';
        $level = LevelModel::all();

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);
    
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);
    
        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }
    

    public function show($id)
    {
      $level = LevelModel::find($id);
      
      $breadcrumb = (object) [
          'title' => 'Detail Level',
          'list' => ['Home', 'Level', 'Detail']
      ];
  
      $page = (object) [
          'title' => 'Detail Level',
      ];
  
      $activeMenu = 'level'; //set menu yang sedang aktif
  
      return view('level.show', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Level'
        ];

        $activeMenu = 'level';
        $level = LevelModel::findOrFail($id);

        return view('level.edit', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'level_kode' => 'required|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required'
        ]);

        LevelModel::findOrFail($id)->update($request->all());

        return redirect('/level')->with('success', 'Data level berhasil diubah!');
    }

    public function destroy($id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        $level = LevelModel::all();

        return view('level.create_ajax',compact('level'));
    }

    public function store_ajax(Request $request, string $id)
    {
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
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

            LevelModel::create($request->all());
            return response()->json([
                'status' => True, 
                'message' => 'Data level berhasil disimpan'
            ]);
        }  
        redirect('/');
    }

    public function edit_ajax(string $id){
        $level = LevelModel::find($id);
        return view('level.edit_ajax', compact('level'));
    }

    public function update_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100'
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
        $check = LevelModel::find($id);
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
        $level = LevelModel::find($id);
         return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax (Request $request, $id){
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $level = LevelModel::find($id);
        if ($level) {
            $level->delete();
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
        $level = LevelModel::find($id);
        
        return view('level.show_ajax', ['level' => $level]);
    }

    public function import() 
     { 
         return view('level.import'); 
     } 
     public function import_ajax(Request $request) 
     { 
         if($request->ajax() || $request->wantsJson()){ 
             $rules = [ 
                 // validasi file harus xls atau xlsx, max 1MB 
                 'file_level' => ['required', 'mimes:xlsx', 'max:1024'] 
             ]; 
             $validator = Validator::make($request->all(), $rules); 
             if($validator->fails()){ 
                 return response()->json([ 
                     'status' => false, 
                     'message' => 'Validasi Gagal', 
                     'msgField' => $validator->errors() 
                 ]); 
             }
             $file = $request->file('file_level');  // ambil file dari request 
 
             $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
             $reader->setReadDataOnly(true);             // hanya membaca data 
             $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
             $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
             $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
             $insert = []; 
             if(count($data) > 1){ // jika data lebih dari 1 baris 
                 foreach ($data as $baris => $value) { 
                     if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                         $insert[] = [  
                             'level_kode' => $value['A'], 
                             'level_nama' => $value['B'], 
                             'created_at' => now(), 
                         ]; 
                     } 
                 } 
                 if(count($insert) > 0){ 
                     // insert data ke database, jika data sudah ada, maka diabaikan 
                     LevelModel::insertOrIgnore($insert);    
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

        $level = LevelModel::select('level_kode', 'level_nama')
                      ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold header

        
        $no = 1;    // noror data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($level as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->level_kode);
            $sheet->setCellValue('C'.$baris, $value->level_nama);
            $baris++;
            $no++;
        }

        foreach(range('A','C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // auto set auto size untuk kolom
        }
        $sheet->setTitle('Data Level'); // set title sheet

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Level'.date('Y-m-d H:i:s').'.xlsx';

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
        $level = LevelModel::select('level_kode', 'level_nama')
                        ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Level '.date('Y-m-d H:i:s').'.pdf');
    }
}
