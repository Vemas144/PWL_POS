@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Level</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Level</button>
                <a href="{{ url('/level/create') }}" class="btn btn-primary">Tambah Data</a>
                <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn  btn-success">Tambah Ajax</button>
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
                      <select name="filter_level" class="form-control form-control-sm filter_level">
                        <option value="">- Semua -</option>
                        @foreach($level as $l)
                          <option value="{{ $l->level_nama }}">{{ $l->level_nama }}</option>
                        @endforeach
                      </select>
                      <small class="form-text text-muted">Level Nama</small>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table-level">
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
    
    var tableLevel;
    $(document).ready(function () {
      tableLevel = $('#table-level').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('level/list') }}",
          type: "POST",
          dataType: "json",
          data: function (d) {
            d.level_nama = $('.filter_level').val();
            d._token = "{{ csrf_token() }}";
          }
        },
        columns: [
            {
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            },{
                data: "level_kode",
                className: "",
                orderable: true,
                searchable: true
            },{
                data: "level_nama",
                className: "",
                orderable: true,
                searchable: true
            },{
                data: "aksi",
                className: "",
                orderable: false,
                searchable: false
            }
        ]
      });
    
      // Enter untuk search manual
      $('#table-level_filter input').unbind().bind().on('keyup', function (e) {
        if (e.keyCode == 13) {
          tableLevel.search(this.value).draw();
        }
      });
    
      // Filter dropdown
      $('.filter_level').change(function () {
        tableLevel.draw();
      });
    });
    </script>
@endpush