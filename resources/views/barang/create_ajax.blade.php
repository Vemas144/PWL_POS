<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode</label>
                    <input type="text" name="barang_kode" id="barang_kode" class="form-control" required>
                    <small id="error-barang_kode" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="barang_nama" id="barang_nama" class="form-control" required>
                    <small id="error-barang_nama" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="text" name="harga_jual" id="harga_jual" class="form-control" required inputmode="numeric" pattern="[0-9]*">
                    <small id="error-harga_jual" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="text" name="harga_beli" id="harga_beli" class="form-control" required inputmode="numeric" pattern="[0-9]*">
                    <small id="error-harga_beli" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>


<script>
$(document).ready(function() {
    $("#form-tambah").validate({
        rules: {
            kategori_id: {required: true, number: true},
            barang_nama: {required: true, minlength: 3, maxlength: 100},
            barang_kode: {required: true, minlength: 3, maxlength: 20},
            harga_jual: {required: true, number: true},
            harga_beli: {required: true, number: true}
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    console.log(response); // Log the response
                    if (response.status) {
                        $('#myModal').modal('hide'); // Hide the modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        // Refresh the DataTable
                        $('#table_barang').DataTable().ajax.reload(); // Ensure this matches your DataTable initialization
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>