<?php
// jika $data ada ISI-nya maka actionnya adalah edit, jika KOSONG : insert
$is_edit = isset($data);
?>

<form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-master">
    @csrf
    {!! ($is_edit)? method_field('PUT') : '' !!}
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Jurusan</label>
                    <div class="col-sm-10">
                        <select @if($is_edit) readonly @endif id="jurusan_id" name="jurusan_id" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($jurusan as $r)
                                <option value="{{ $r->jurusan_id }}">{{ $r->jurusan_code.' - '.$r->jurusan_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="circle_name" name="circle_name" value="{{ isset($data->circle_name) ? $data->circle_name : '' }}"/>
                        <small class="form-text text-muted">Nama kelompok</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Member</label>
                    <div class="col-sm-10">
                        <select multiple id="dosen_id" name="dosen_id[]" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($dosen as $r)
                                <option value="{{ $r->dosen_id }}">{{ $r->dosen_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        unblockUI();

        $('.select2_combobox').select2();

        @if($is_edit)
            $('#jurusan_id').val('{{ $data->jurusan_id }}').trigger('change');
            $('#dosen_id').val(['{!! str_replace(",", "','", $data->anggota_id) !!}']).trigger('change');
        @endif

        $("#form-master").validate({
            rules: {
                jurusan_id: {
                    required: true
                },
                circle_name: {
                    required: true,
                    maxlength: 255
                },
                'dosen_id[]': {
                    required: true
                }
            },
            submitHandler: function (form) {
                $('.form-message').html('');
                blockUI(form);
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (data) {
                        unblockUI(form);
                        setFormMessage('.form-message', data);
                        if (data.stat) {
                            resetForm('#form-master');
                            dataMaster.draw(false);
                        }
                        closeModal($modal, data);
                    }
                });
            },
            validClass: "valid-feedback",
            errorElement: "div",
            errorClass: 'invalid-feedback',
            errorPlacement: erp,
            highlight: hl,
            unhighlight: uhl,
            success: sc
        });
    });
</script>
