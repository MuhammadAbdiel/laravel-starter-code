<?php
    $is_edit = false;
    if(isset($data)){ // jika $data ada ISI-nya maka actionnya adalah edit, jika KOSONG : insert
        $is_edit = true;
    }
?>


<form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-master" enctype="multipart/form-data">
    @csrf
    {!! ($is_edit)? method_field('PUT') : '' !!}
    <div id="modal-master" class="modal-dialog modal-92" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>

                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Posisi</label>
                    <div class="col-sm-3">
                        <select id="department_id" name="department_id" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($department as $r)
                                <option value="{{ $r->id }}">{{ $r->code.' - '.$r->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Departemen</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="section_id" name="section_id" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($section as $r)
                                <option value="{{ $r->id }}">{{ $r->code.' - '.$r->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Section</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="jabatan_id" name="jabatan_id" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($jabatan as $r)
                                <option value="{{ $r->id }}">{{ $r->code.' - '.$r->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Jabatan</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Status Karyawan</label>
                    <div class="col-sm-3">
                        <select id="employee_status" name="employee_status" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            <option value="TE">Tetap</option>
                            <option value="K1">Kontrak 1</option>
                            <option value="K2">Kontrak 2</option>
                            <option value="MG">Magang</option>
                        </select>
                        <small class="form-text text-muted">Status Karyawan</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="employee_shift" name="employee_shift" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="NS">NS</option>
                        </select>
                        <small class="form-text text-muted">Status Shift</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="marital_status" name="marital_status" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            <option value="S">Single</option>
                            <option value="K">Kawin/Menikah</option>
                            <option value="C">Cerai</option>
                        </select>
                        <small class="form-text text-muted">Status Menikah</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Nomor Identitas</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="employee_ktp" name="employee_ktp" value="{{ isset($data->employee_ktp) ? $data->employee_ktp : '' }}" />
                        <small class="form-text text-muted">No. KTP</small>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="employee_nim" name="employee_nim" value="{{ isset($data->employee_nim) ? $data->employee_nim : '' }}" />
                        <small class="form-text text-muted">NIM</small>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="employee_nik" name="employee_nik" value="{{ isset($data->employee_nik) ? $data->employee_nik : '' }}" />
                        <small class="form-text text-muted">NIK JAI</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Nama</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="employee_name" name="employee_name" value="{{ isset($data->employee_name) ? $data->employee_name : '' }}" />
                        <small class="form-text text-muted">Nama Karyawan</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="gender" name="gender" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <small class="form-text text-muted">Jenis Kelamin</small>
                    </div>
                    <div class="col-sm-3">
                        <select id="religion" name="religion" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            <option value="IS">Islam</option>
                            <option value="KR">Kristen</option>
                            <option value="KA">Katolik</option>
                            <option value="HI">Hindu</option>
                            <option value="BD">Budha</option>
                            <option value="KH">Konghucu</option>
                        </select>
                        <small class="form-text text-muted">Agama</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">TTL & Telpon</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="birthplace" name="birthplace" value="{{ isset($data->birthplace) ? $data->birthplace : '' }}" />
                        <small class="form-text text-muted">Tempat Lahir</small>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="birthdate" name="birthdate" value="{{ isset($data->birthdate) ? $data->birthdate : '' }}" />
                        <small class="form-text text-muted">Tanggal Lahir</small>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="phone" name="phone" value="{{ isset($data->phone) ? $data->phone : '' }}" />
                        <small class="form-text text-muted">Telepon</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label for="" class="col-sm-2 control-label col-form-label">Alamat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="address" name="address" value="{{ isset($data->address) ? $data->address : '' }}" />
                        <small class="form-text text-muted">Alamat</small>
                    </div>
                </div>
                <hr>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Status</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="entry_date" name="entry_date" value="{{ isset($data->entry_date) ? $data->entry_date : '' }}" />
                        <small class="form-text text-muted">Tanggal Masuk</small>
                    </div>
                    <div class="col-sm-6 mt-1">
                        <div class="icheck-success d-inline mr-3">
                            <input type="radio" id="radioActive" name="mp_status" value="A" <?php echo isset($data->mp_status)? (($data->mp_status == 'A')? 'checked' : '') : 'checked' ?>>
                            <label for="radioActive">Active </label>
                        </div>
                        <div class="icheck-danger d-inline mr-3">
                            <input type="radio" id="radioFailed" name="mp_status" value="F" <?php echo isset($data->mp_status)? (($data->mp_status == 'F')? 'checked' : '') : '' ?>>
                            <label for="radioFailed">Failed</label>
                        </div>
                        <div class="icheck-warning d-inline mr-3">
                            <input type="radio" id="radioResign" name="mp_status" value="F" <?php echo isset($data->mp_status)? (($data->mp_status == 'F')? 'checked' : '') : '' ?>>
                            <label for="radioResign">Resign</label>
                        </div>
                        <small class="form-text text-muted">Status Aktif Karyawan</small>
                    </div>
                </div>
                <hr>
                <div class="form-group @if(!$is_edit) required @endif row mb-1">
                    <label class="col-sm-2 control-label col-form-label">Foto</label>
                    <div class="col-sm-9">
                        @if(isset($data->photo_url) && !empty($data->photo_url))
                            <div class="row mb-1">
                                <div class="col-sm-4">
                                    <img src="{{ url($data->photo_url) }}" class="img-fluid img-thumbnail pop_preview" alt="Responsive image">
                                </div>
                                <div class="col-sm-8 text-xs"><strong>Keterangan:</strong><br> Silahkan pilih foto baru jika ingin mengganti foto disamping.</div>
                            </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="gambar" id="gambar" accept="image/jpeg,image/jpg,image/png">
                            <label class="col-form-label-sm custom-file-label" for="gambar">Choose file</label>
                        </div>
                        <small class="form-text text-muted">Foto Karyawan. Ukuran maksimal 2MB</small>
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
    $(document).ready(function() {
        unblockUI();
        bsCustomFileInput.init();

        $('.datepicker').daterangepicker({parentEl: ".modal-body",singleDatePicker: true, locale:{format: 'YYYY-MM-DD'}});

        @if($is_edit)
            $('#department_id').val({{$data->department_id}}).trigger('change');
            $('#section_id').val({{$data->section_id}}).trigger('change');
            $('#jabatan_id').val({{$data->jabatan_id}}).trigger('change');

            $('#employee_status').val('{{$data->employee_status}}').trigger('change');
            $('#employee_shift').val('{{$data->employee_shift}}').trigger('change');
            $('#marital_status').val('{{$data->marital_status}}').trigger('change');

            $('#religion').val('{{$data->religion}}').trigger('change');
            $('#gender').val('{{$data->gender}}').trigger('change');

            $('.pop_preview').on('click', function() {
                $('.image_preview').attr('src', $(this).attr('src'));
                $('#image_modal').modal('show');
            });
        @endif

        $('.select2_combobox').select2();

        $("#form-master").validate({
            rules: {
                department_id: {required: true, digits: true},
                section_id: {required: true, digits: true},
                jabatan_id: {required: true, digits: true},
                employee_ktp: {number: true, minlength: 4, maxlength: 16},
                employee_nim: {number: true, minlength: 4, maxlength: 16},
                employee_nik: {required: true, number: true, minlength: 4, maxlength: 16},
                employee_status: {required: true},
                employee_shift: {required: true},
                marital_status: {required: true},
                employee_name: {required: true, minlength: 4, maxlength: 50},
                gender: {required: true},
                religion: {required: true},
                birthplace: {required: true, minlength: 4, maxlength: 50},
                birthdate: {required: true, date: true},
                phone: {required: true, number: true, minlength: 6, maxlength: 15},
                address: {required: true, maxlength: 255},
                mp_status: {required: true},
                gambar:{
                    @if(!$is_edit) required: true, @endif
                    extension: 'jpg|jpeg|png',
                    filesize: 2 // 2MB
                }
            },
            submitHandler: function(form) {
                $('.form-message').html('');
                blockUI(form);
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(data) {
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

<select id="employee_type" name="employee_type" class="form-control form-control-sm w-100 select2_combobox">
    <option value="">- Pilih -</option>
    <option value="1">Karyawan Tetap</option>
    <option value="2">Karyawan Kontrak</option>
    <option value="3">Magang</option>
</select>
<small class="form-text text-muted">Jenis Karyawan</small>
