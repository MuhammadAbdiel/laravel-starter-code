<?php
    $is_edit = false;
    if(isset($data)){ // jika $data ada ISI-nya maka actionnya adalah edit, jika KOSONG : insert
        $is_edit = true;
    }
?>


<form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-master" enctype="multipart/form-data">
    @csrf
    {!! ($is_edit)? method_field('PUT') : '' !!}
    <div id="modal-master" class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>

                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Program Studi</label>
                    <div class="col-sm-10">
                        <select multiple id="prodi_id" name="prodi_id[]" class="form-control form-control-sm select2_combobox">
                            <option value="">- Pilih -</option>
                            @foreach ($prodi as $r)
                                <option value="{{ $r->id }}">{{ $r->code.' - '.$r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Tahapan</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Nomor tahapan/gelombang" class="form-control form-control-sm" id="tahapan_proposal" name="tahapan_proposal" value="{{ isset($data->tahapan_proposal) ? $data->tahapan_proposal : '' }}" />
                        <small class="form-text text-muted">Tahapan/Gelombang Tahapan Sidang Proposal</small>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Jumlah minimal bimbingan" class="form-control form-control-sm" id="min_bimbingan" name="min_bimbingan" value="{{ isset($data->min_bimbingan) ? $data->min_bimbingan : '' }}" />
                        <small class="form-text text-muted">Syarat ikut tahapan harus memiliki minimal x bimbingan</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Tanggal</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm" id="tgl_awal" name="tgl_awal" value="{{ isset($data->tgl_awal) ? $data->tgl_awal : '' }}" />
                        <small class="form-text text-muted">Tanggal Mulai</small>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm" id="tgl_akhir" name="tgl_akhir" value="{{ isset($data->tgl_akhir) ? $data->tgl_akhir : '' }}" />
                        <small class="form-text text-muted">Tanggal Selesai</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea class="form-control form-control-sm summernote" id="keterangan" name="keterangan">{!! isset($data->keterangan) ? $data->keterangan : '' !!}</textarea>
                        <small class="form-text text-muted">Keterangan Tahapan</small>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-2 control-label col-form-label">Batas Acc</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm" id="tgl_batas_acc" name="tgl_batas_acc" value="{{ isset($data->tgl_batas_acc) ? $data->tgl_batas_acc : '' }}" />
                        <small class="form-text text-muted">Tanggal Batas Dosen Pembimbing bisa Acc Pendaftaran Seminar Proposal</small>
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

    @if($is_edit)
        $('#prodi_id').val([{{$data->prodi_id}}]).trigger('change');
    @endif

    $('.select2_combobox').select2();

    $('#tgl_awal, #tgl_akhir').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}});
    $('#tgl_batas_acc').daterangepicker({drops:"up", parentEl: ".modal-body",singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}});
    /*$('#tgl_awal').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'})
        .on('change', function () {
            $('#tgl_akhir').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}, minDate: $(this).val()});
            $('#tgl_batas_acc').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}, minDate: $(this).val()});
        });

    $('#tgl_akhir').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}})
        .on('change', function () {
            $('#tgl_awal').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}, minDate: moment().format('YYYY-MM-DD'), maxDate: $(this).val()});
            $('#tgl_batas_acc').daterangepicker({singleDatePicker: true, autoUpdateInput: true, locale:{format: 'YYYY-MM-DD'}, minDate: $(this).val()});
        });*/



    $('#keterangan').summernote({
        tabsize: 2,
        height: 200,
        dialogsInBody: true,
        codeviewFilter: true,
        codeviewIframeFilter: true,
        popover: {
            air: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        }
    });

    $("#form-master").validate({
        rules: {
            'prodi_id[]': {required: true},
            tgl_awal: {required: true, date: true},
            tgl_akhir: {required: true, date: true},
            tgl_batas_acc: {required: true, date: true},
            keterangan: {required: true},
            tahapan_proposal: {required: true, digits: true, min:1, max:50},
            min_bimbingan: {required: true, digits:true, min:1, max:50}
        },
        submitHandler: function(form) {
            if ($('#keterangan').summernote('isEmpty')) {
                setFormMessage('.form-message', {stat: false, msg: 'Keterangan harus diisi', msgField:{'keterangan': 'Keterangan harus diisi'}});
                return false;
            }

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
