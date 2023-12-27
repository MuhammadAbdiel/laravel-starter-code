<form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-master" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>
                <div class="form-group row mb-2">
                    <label for="mulai" class="col-sm-2 col-form-label">File Import</label>
                    <div class="col-md-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="berkas" id="customFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="message-detail"></div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

    <script>
        $(document).ready(function(){
            bsCustomFileInput.init();
            $("#form-master").validate({
                rules: {
                    berkas: {
                        required: true,
                        accept: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel",
                    },
                    major_id:{
                        required: true
                    }
                },
                submitHandler: function(form) {
                    $('.form-message, .message-detail').html('');
                    blockUI('#form-master', 'progress', 7);
                    $(form).ajaxSubmit({
                        dataType:  'json',
                        beforeSubmit: function(arr, $form, options) {
                            return true;
                        },
                        success: function(data){
                            setFormMessage('.form-message', data, 10000);
                            if(data.stat){
                                dataMaster.draw();
                                resetForm(form)
                            }
                            closeModal($modal, data);
                        }
                    });
                },
                validClass: "valid--feedback",
                errorElement: "div",
                errorClass: 'invalid-feedback',
                errorPlacement: erp,
                highlight: hl,
                unhighlight: uhl,
                success: sc
            });
        });
    </script>
