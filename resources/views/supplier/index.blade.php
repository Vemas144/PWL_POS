@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Supplier</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info">Import Supplier</button>
            <a href="{{ url('/supplier/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Supplier</a>
            <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Supplier</a>
            <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn  btn-success">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        <!-- untuk Filter data -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-sm row text-sm mb-0">
                <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                <div class="col-md-3">
                  <select name="filter_supplier" class="form-control form-control-sm filter_supplier">
                    <option value="">- Semua -</option>
                    @foreach($supplier as $l)
                      <option value="{{ $l->supplier_nama }}">{{ $l->supplier_nama }}</option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Supplier Nama</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif 
            <table class="table table-bordered table-striped table-hover table-sm" id="table-supplier">
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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-
    backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection


@push('js')
<script>
    function modalAction(url = '') {
      $('#myModal').load(url, function () {
        $('#myModal').modal('show');
      });
    }
    
    var tableSupplier;
    $(document).ready(function () {
      tableSupplier = $('#table-supplier').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('supplier/list') }}",
          type: "POST",
          dataType: "json",
          data: function (d) {
            d.supplier_nama = $('.filter_supplier').val();
            d._token = "{{ csrf_token() }}";
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

    // Enter untuk search manual
    $('#table-supplier_filter input').unbind().bind().on('keyup', function (e) {
        if (e.keyCode == 13) {
          tableSupplier.search(this.value).draw();
        }
      });
    
      // Filter dropdown
      $('.filter_supplier').change(function () {
        tableSupplier.draw();
      });
    });
    </script>
@endpush