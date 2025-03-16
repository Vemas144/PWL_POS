@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('supplier/create') }}" class="btn btn-sm btn-primary mt-1">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label for="supplier_id" class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                      <select class="form-control" id="supplier_id" name="supplier_id" required>
                        <option value="">- Semua -</option>
                        @foreach ($supplier as $item)
                            <option value="{{ $item->supplier_nama }}">{{ $item->supplier_nama }}</option>
                        @endforeach
                      </select>
                      <small class="form-text text-muted">Supplier Nama</small>
                    </div>
                  </div>
                </div>
              </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
       $(document).ready(function() {
    let table = $('#table_supplier').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('supplier/list') }}",
            type: "POST",
            dataType: "json",
            data: function(d) {
                d.supplier_nama = $('#supplier_id').val(); 
                d._token = "{{ csrf_token() }}"; // Laravel membutuhkan CSRF token
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "supplier_nama", orderable: true, searchable: true },
            { data: "supplier_alamat", orderable: true, searchable: true },
            { data: "supplier_telepon", orderable: true, searchable: true }, 
            { data: "supplier_email", orderable: true, searchable: true },   
            { data: "aksi", orderable: false, searchable: false }
        ]
    });

    
    $('#supplier_id').change(function() {
        table.ajax.reload(); // Reload DataTables saat filter berubah
    });
});

    </script>
@endpush