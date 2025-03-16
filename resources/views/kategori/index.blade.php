@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('kategori/create') }}" class="btn btn-sm btn-primary mt-1">Tambah</a>
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
                    <label for="kategori_id" class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                      <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">- Semua -</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->kategori_nama }}">{{ $item->kategori_nama }}</option>
                        @endforeach
                      </select>
                      <small class="form-text text-muted">Kategori Nama</small>
                    </div>
                  </div>
                </div>
              </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama</th>
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
    let table = $('#table_kategori').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('kategori/list') }}",
            type: "POST",
            dataType: "json",
            data: function(d) {
                d.kategori_nama = $('#kategori_id').val(); 
                d._token = "{{ csrf_token() }}"; // Laravel membutuhkan CSRF token
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "kategori_kode", orderable: true, searchable: true },
            { data: "kategori_nama", orderable: true, searchable: true },  
            { data: "aksi", orderable: false, searchable: false }
        ]
    });

    
    $('#kategori_id').change(function() {
        table.ajax.reload(); // Reload DataTables saat filter berubah
    });
});

    </script>
@endpush