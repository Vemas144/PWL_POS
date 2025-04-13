<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SupplierController extends Controller
{
    public function index ()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
    
        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar di supplier'
        ];
    
        $activeMenu = 'supplier';
        $supplier = SupplierModel::all(); // Ambil data dengan model
    
        return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu', 'supplier'));
    }

    public function list(Request $request)
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama', 'supplier_alamat', 'supplier_telepon', 'supplier_email');

        if ($request->supplier_nama) {
            $supplier = $supplier->where('supplier_nama', 'like', '%' . $request->supplier_nama . '%');
        }
        

        return DataTables::of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                /*$btn = '<a href="' . url('supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('supplier/' . $supplier->supplier_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                    </form>';*/
                    //$btn = '<a href="'.url('/supplier/'.$supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a>';
                    $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
            'title' => 'Tambah Supplier Baru'
        ];

        $activeMenu = 'supplier';
        $supplier = SupplierModel::all();

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu', 'supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required', // TEXT tidak butuh max length
            'supplier_telepon' => 'required|string|max:15',
            'supplier_email' => 'required|string|unique:m_supplier,supplier_email',
        ]);
    
        SupplierModel::create([
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
            'supplier_telepon' => $request->supplier_telepon,
            'supplier_email' => $request->supplier_email
        ]);
    
        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }
    

    public function show($id)
    {
      $supplier = SupplierModel::find($id);
      
      $breadcrumb = (object) [
          'title' => 'Detail Supplier',
          'list' => ['Home', 'Supplier', 'Detail']
      ];
  
      $page = (object) [
          'title' => 'Detail Supplier',
      ];
  
      $activeMenu = 'supplier'; //set menu yang sedang aktif
  
      return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];

        $activeMenu = 'supplier';
        $supplier = SupplierModel::findOrFail($id);
        return view('supplier.edit', compact('breadcrumb', 'page', 'activeMenu', 'supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required', // TEXT tidak butuh max length
            'supplier_telepon' => 'required|string|max:15',
            'supplier_email' => 'required|string|unique:m_supplier,supplier_email,' . $id . ',supplier_id',
        ]);

        SupplierModel::findOrFail($id)->update($request->all());

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah!');
    }

    public function destroy($id)
    {
        $check = SupplierModel::find($id);
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        try {
            SupplierModel::destroy($id);
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        $supplier = SupplierModel::all();

        return view('supplier.create_ajax',compact('supplier'));
    }

    public function store_ajax(Request $request)
    {
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required', // TEXT tidak butuh max length
            'supplier_telepon' => 'required|string|max:15',
            'supplier_email' => 'required|string|unique:m_supplier,supplier_email',
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

            SupplierModel::create($request->all());
            return response()->json([
                'status' => True, 
                'message' => 'Data level berhasil disimpan'
            ]);
        }  
        redirect('/');
    }
    
    public function edit_ajax(string $id){
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', compact('supplier'));
    }

    public function update_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required', 
            'supplier_telepon' => 'required|string|max:15',
            'supplier_email' => 'required|string|unique:m_supplier,supplier_email,' . $id . ',supplier_id',
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
        $check = SupplierModel::find($id);
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
        $supplier = SupplierModel::find($id);
         return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax (Request $request, $id){
    // cek apakah request dari ajax
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
    }
    return redirect("/");
    }

    public function show_ajax($id){
        $supplier = SupplierModel::find($id);
        
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }

    public function import() 
    { 
        return view('supplier.import'); 
    } 
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            }
            $file = $request->file('file_supplier');  // ambil file dari request 

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
                            'supplier_nama'    => $value['A'], 
                            'supplier_alamat'  => $value['B'], 
                            'supplier_telepon' => $value['C'], 
                            'supplier_email'   => $value['D'], 
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    SupplierModel::insertOrIgnore($insert);    
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
}
