@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12">
                <div class="card card-outline card-{{ $theme->card_outline }}">
                    <div class="card-header">
                        <h3 class="card-title mt-1">
                            <i class="fas fa-angle-double-right text-md text-{{ $theme->card_outline }} mr-1"></i>
                            {!! $page->title !!}
                        </h3>
                        <div class="card-tools">
                            @if($allowAccess->create)
                                <a class="btn btn-sm btn-info text-light mt-1" href="{{ asset('Template-Data_Dosen.xlsx') }}"><i class="fas fa-file-download"></i> Download Template Data Dosen</a>&nbsp;
                                <button type="button" data-block="body" class="btn btn-sm btn-{{ $theme->button }} mt-1 ajax_modal" data-url="{{ $page->url }}/import"><i class="fas fa-file-import"></i> Import</button>&nbsp;
                                <button type="button" data-block="body" class="btn btn-sm btn-{{ $theme->button }} mt-1 ajax_modal" data-url="{{ $page->url }}/create"><i class="fas fa-plus"></i> Tambah</button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">

                        <!-- untuk Filter data -->
                        <div id="filter" class="form-horizontal filter-date p-2 border-bottom">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm row text-sm mb-0">
                                        <label class="col-md-1 col-form-label">Filter</label>
                                        <div class="col-md-4">
                                            <select class="form-control form-control-sm w-100 filter_combobox filter_prodi">
                                                <option value="">- Semua -</option>
                                                @foreach($prodi as $d)
                                                    <option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Prodi</small>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control form-control-sm w-100 filter_combobox filter_jenis">
                                                <option value="">- Semua -</option>
                                                <option value="P">PNS</option>
                                                <option value="T">Tetap Non-PNS</option>
                                                <option value="K">Kontrak</option>
                                                <option value="L">LB (Luar Biasa)</option>
                                                <option value="X">Lainnya</option>
                                            </select>
                                            <small class="form-text text-muted">Jenis Dosen</small>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control form-control-sm w-100 filter_combobox filter_status">
                                                <option value="">- Semua -</option>
                                                <option value="AK">Aktif</option>
                                                <option value="IB">Izin Belajar</option>
                                                <option value="TB">Tugas Belajar</option>
                                                <option value="CT">Cuti</option>
                                                <option value="NA">Lainnya</option>
                                            </select>
                                            <small class="form-text text-muted">Status Dosen</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-striped table-hover table-full-width" id="table_master">
                            <thead>
                                <tr><th>No</th>
                                    <th>NIDN</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img src="" class="image_preview" style="width: 100%;" >
                </div>
            </div>
        </div>
    </div>
@endsection
@push('content-js')
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.filter_combobox').select2();

            dataMaster = $('#table_master').DataTable({
                "bServerSide": true,
                "bAutoWidth": false,
                "ajax": {
                    "url": "{{ $page->url }}/list",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi_id = $('.filter_prodi').val();
                        d.dosen_jenis = $('.filter_jenis').val();
                        d.dosen_status = $('.filter_status').val();
                    },
                },
                "aoColumns": [{
                        "mData": "no",
                        "sClass": "text-center",
                        "sWidth": "5%",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "dosen_nidn",
                        "sWidth": "12%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "dosen_name",
                        "sClass": "",
                        "sWidth": "37%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "prodi_code",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": false,
                    },
                    {
                        "mData": "dosen_jenis",
                        "sClass": "",
                        "sWidth": "12%",
                        "bSortable": true,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            return getDosenJenis(data, true);
                        }
                    },
                    {
                        "mData": "dosen_status",
                        "sClass": "",
                        "sWidth": "8%",
                        "bSortable": true,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            return getDosenStatus(data, true);
                        }
                    },
                    {
                        "mData": "masa_tugas",
                        "sClass": "",
                        "sWidth": "8%",
                        "bSortable": true,
                        "bSearchable": false,
                    },
                    {
                        "mData": "dosen_id",
                        "sClass": "text-center pr-2",
                        "sWidth": "8%",
                        "bSortable": false,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            return  ''
                                @if($allowAccess->update) + `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/edit" class="ajax_modal btn btn-xs btn-warning tooltips text-secondary" data-placement="left" data-original-title="Edit Data" ><i class="fa fa-edit"></i></a> ` @endif
                                @if($allowAccess->delete) + `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/delete" class="ajax_modal btn btn-xs btn-danger tooltips text-light" data-placement="left" data-original-title="Hapus Data" ><i class="fa fa-trash"></i></a> ` @endif
                                ;
                        }
                    }
                ],
                "fnDrawCallback": function ( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $( 'a', this.fnGetNodes() ).tooltip();
                }
            });

            $('.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataMaster.search($(this).val()).draw();
                }
            });

            $('.filter_prodi, .filter_jenis, .filter_status').change(function (){
                dataMaster.draw();
            });
        });

    </script>

@endpush
