 @extends('layouts.template') 
 
@section('content') 
    <div class="card">
      <div class="card-header">
          <h3 class="card-title">Daftar User</h3>
          <div class="card-tools">
              <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-info">Import User</button>
              <a href="{{ url('/user/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export User</a>
              <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export User</a>
              <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn  btn-success">Tambah Ajax</button>
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
                  <select name="filter_user" class="form-control form-control-sm filter_user">
                    <option value="">- Semua -</option>
                    @foreach($level as $l)
                      <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table-user">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
  </div>
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
  data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('js') 
  <script> 
  function modalAction(url = '') {
        $('#myModal').load(url, function () {
          $('#myModal').modal('show');
        });
      }
      
    var tableUser;
    $(document).ready(function () {
      tableUser = $('#table-user').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('user/list') }}",
          type: "POST",
          dataType: "json",
          data: function (d) {
            d.level_id = $('.filter_user').val();
            d._token = "{{ csrf_token() }}";
          }
        },
          columns: [ 
            { 
            // nomor urut dari laravel datatable addIndexColumn() 
              data: "DT_RowIndex",             
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
              data: "username",                
              className: "",   
              orderable: true,     
              searchable: true     
            },{ 
              data: "nama",                
              className: "", 
              orderable: true,     
              searchable: true     
            },{ 
              // mengambil data level hasil dari ORM berelasi 
              data: "level.level_nama",                
              className: "", 
              orderable: false,     
              searchable: false     
            },{ 
              data: "aksi",                
              className: "", 
              orderable: false,     
              searchable: false     
            } 
          ] 
      });
       // Enter untuk search manual
       $('#table-user_filter input').unbind().bind().on('keyup', function (e) {
        if (e.keyCode == 13) {
          tableUser.search(this.value).draw();
        }
      }); 

      // Filter dropdown
      $('.filter_user').change(function () {
        tableUser.draw();
      });
    });
  </script> 
@endpush  