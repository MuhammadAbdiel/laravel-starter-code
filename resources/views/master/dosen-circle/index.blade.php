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
                                            <select class="form-control form-control-sm w-100 filter_combobox filter_jurusan">
                                                <option value="">- Semua -</option>
                                                @foreach($jurusan as $d)
                                                    <option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Jurusan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-full-width" id="table_master">
                                <thead>
                                <tr><th>No</th>
                                    <th>Jurusan</th>
                                    <th>Nama Kelompok</th>
                                    <th>Jumlah</th>
                                    <th>Jml Member</th>
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
                        d.jurusan_id = $('.filter_jurusan').val();
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
                        "mData": "jurusan_code",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "circle_name",
                        "sClass": "",
                        "sWidth": "25%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "jumlah",
                        "sClass": "",
                        "sWidth": "7%",
                        "bSortable": true,
                        "bSearchable": false,
                    },
                    {
                        "mData": "anggota_nama",
                        "sClass": "",
                        "sWidth": "45%",
                        "bSortable": true,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            let h = '';
                            $.each(data.split(','), function (i, v) {
                                h += '<span class="badge badge-secondary opacity-75 mr-1">'+v+'</span>';
                            });
                            return h;
                        }
                    },
                    {
                        "mData": "dosen_circle_id",
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

            $('.filter_jurusan').change(function (){
                dataMaster.draw();
            });
        });

    </script>

@endpush
