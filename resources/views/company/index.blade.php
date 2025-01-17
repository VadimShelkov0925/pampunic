@extends('layouts.companies')

@section('content')
<style>
    .select-editable {
        position: relative;
        background-color: white;
        border: solid grey 1px;
        width: 113px;
        height: 38px;
    }

    .select-editable select {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 14px;
        border: none;
        width: 110px;
        margin: 0;
        height: 36px;
    }

    .select-editable input {
        position: absolute;
        top: 0;
        left: {{app()->getLocale() == 'he' ? 'auto' : '0'}};
        right: {{app()->getLocale() == 'he' ? '0' : 'auto'}};
        width: 80%;
        height: 100%;
        padding: 1px;
        font-size: 14px;
        border: none;
        padding: 0 3px
    }

    .select-editable select:focus, .select-editable input:focus {
        outline: none;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row" style="direction:ltr;">
                <div class="col-md-4">
                    <div class="input-group">
                        <button type="button" class="btn btn-success add-link-btn">{{__('Add')}}</button>
                        <div class="select-editable">
                            <select class="custom-select" id="user-name-select"
                                    aria-label="Example select with button addon">
                                <option selected>{{__('Name')}}</option>
                            </select>
                            <input type="text" name="format" value="" id="format" placeholder="{{__('Name')}}"/>
                        </div>
                        <select class="custom-select" id="user-type-select"
                                aria-label="Example select with button addon">
                            <option selected>{{__('User type')}}</option>
                            @foreach($usersType as $type)
                                <option value="{{ $type->id }}">{{ __($type->title) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <textarea id="companyRepresentativeName" rows="1" class="form-control" style="font-size: 10px" placeholder="{{__('Company representative name')}}"></textarea>

                        <select class="custom-select" id="sub-site-select" aria-label="Example select with button addon" disabled> 
                            <option value="0" selected>{{__('Sub-site')}}</option>
                        </select>
                        <div class="input-group-append">
                            <button id="edit-sub-site-btn-a" class="btn btn-outline-secondary" type="button"
                                    data-toggle="modal" data-target="#editSubSiteModal" disabled><i
                                    class="fas fa-edit"></i></button>
                            <button id="delete-sub-site-btn-a" class="btn btn-outline-secondary btn-delete-sub-site"
                                    type="button" disabled><i class="far fa-trash-alt"></i></button>
                            <button id="add-sub-site-btn-a" class="btn btn-outline-secondary" type="button"
                                    data-toggle="modal" data-target="#addSubSiteModal" disabled><i
                                    class="far fa-plus-square"></i></button>
                        </div>

                        <select class="custom-select" id="site-select" aria-label="Example select with button addon">
                            <option value="0" selected>{{__('Site')}}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" data-representative="{{$site->representative}}">{{ $site->title }}</option>
                                @endforeach
                        </select>

                        <div class="input-group-append">
                            <button id="edit-site-btn-a" class="btn btn-outline-secondary" type="button"
                                    data-toggle="modal" data-target="#editSiteModal"><i
                                    class="fas fa-edit"></i></button>
                            <button id="delete-site-btn-a" class="btn btn-outline-secondary btn-delete-site"
                                    type="button"><i class="far fa-trash-alt"></i></button>
                            <button id="add-site-btn-a" class="btn btn-outline-secondary" type="button"
                                    data-toggle="modal" data-target="#addSiteModal"><i
                                    class="far fa-plus-square"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center text-primary">{{__('Site linking')}}</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="users-table" class="display" style="width:100%; direction:ltr;">
                        <thead>
                            <tr>
                                <th>{{__('Action')}}</th>
                                <th>{{__('User name')}}</th>
                                <th>{{__('Sub-site')}}</th>
                                <th>{{__('Site')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($links as $link)
                            <tr id="{{ $link->id }}">
                                <td style="display: flex; align-items: center;">
                                    <button class="btn btn-danger" onclick="deleteLink({{ $link->id }})"><i class="fas fa-trash-alt"></i></button>
                                </td>
                                <td>
                                    @if(isset($link->user->name))
                                        {{ $link->user->name }}
                                    @else
                                        Name
                                    @endif
                                </td>
                                <td>@isset($link->subSite){{ $link->subSite->title }}@endisset</td>
                                <td>{{ $link->site->title ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyModalLabel">New company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="company" placeholder="Company name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-add-company">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyModalLabel">Edit company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="company-edit" placeholder="Company name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-edit-company">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Site Modal -->
<div class="modal fade" id="addSiteModal" tabindex="-1" role="dialog" aria-labelledby="addSiteModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiteModalLabel">{{__('New site')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="site" placeholder="{{__('Site name')}}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="representative" placeholder="{{__('Company representative name')}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-add-site">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Site Modal -->
<div class="modal fade" id="editSiteModal" tabindex="-1" role="dialog" aria-labelledby="editSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiteModalLabel">{{__('Edit site')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="site-edit" placeholder="{{__('Site name')}}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="site-edit-representative" placeholder="{{__('Company representative name')}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-edit-site">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Sub Site Modal -->
<div class="modal fade" id="addSubSiteModal" tabindex="-1" role="dialog" aria-labelledby="addSubSiteModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubSiteModalLabel">{{__('New Sub-site')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="sub-site" placeholder="{{__('Sub-site name')}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="sub-site-representative" placeholder="{{__('Company representative name')}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-add-sub-site">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sub Site Modal -->
<div class="modal fade" id="editSubSiteModal" tabindex="-1" role="dialog" aria-labelledby="editSubSiteModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubSiteModalLabel">{{__('Edit Sub-site')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="sub-site-edit" placeholder="{{__('Site name')}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="sub-site-edit-representative" placeholder="{{__('Company representative name')}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-edit-sub-site">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Company Repres -->
<div class="modal fade" id="addCompanyRepres" tabindex="-1" role="dialog" aria-labelledby="addSiteModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiteModalLabel">{{__('New site')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="representative"
                            placeholder="Company representative name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-add-company-repres">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Company Repres Modal -->
<div class="modal fade" id="editCompanyRepres" tabindex="-1" role="dialog" aria-labelledby="editSubSiteModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubSiteModalLabel">Edit Sub Site</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="company-representative"
                            placeholder="Company representative name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary btn-edit-company-repres">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#users-table').DataTable({
                responsive: true,
                "oLanguage": {
                    "oAria": {
                    "sSortAscending": "{{__('sorting ascending')}}",
                    "sSortDescending": "{{__('sorting descending')}}"
                    },
                    "oPaginate": {
                    "sFirst": "{{__('First')}}",
                    "sLast": "{{__('Last')}}",
                    "sNext": "{{__('Next')}}",
                    "sPrevious": "{{__('Previous')}}"
                    },
                    "sEmptyTable": "{{__('No data available in table')}}",
                    "sLengthMenu": "{{__('show _MENU_ entries')}}",
                    "sLoadingRecords": "{{__('Loading...')}}",
                    "sProcessing": "{{__('Processing...')}}",
                    "sSearch": "{{__('Search(site linking)')}}",
                    "sZeroRecords": "{{__('No matching records found')}}",
                    "sInfo": "{{__('Showing _START_ to _END_ of _TOTAL_ entries')}}",
                    "sInfoEmpty": "{{__('No entries to show')}}",
                    "sInfoFiltered": "{{__('filtered from _MAX_ total records')}}"
                }
            });

            $('#user-name-select').on('change', function () {
                var id = $(this).val();
                $('#format').val($('#' + id).text());
                $("#format").attr('class', id);
            });

            $('#format').on('keyup', function () {
                id = $('#format').attr('class');
                value = $('#format').val();
                $('#user-name-select option#' + id).html(value);

                $.ajax({
                    url: "{{action('CompanyController@change')}}",
                    type: 'post',
                    data: {id: id, value: value},
                    success: function () {
                    }
                });
            });
        });

        function changeSiteState(disabled) {
            let siteSelectEl = $("#site-select");
            let addSiteBtnEl = $("#add-site-btn-a");
            let deleteSiteBtnEl = $("#delete-site-btn-a");
            let editSiteBtnEl = $("#edit-site-btn-a");

            siteSelectEl.attr('disabled', disabled);
            addSiteBtnEl.attr('disabled', disabled);
            deleteSiteBtnEl.attr('disabled', disabled);
            editSiteBtnEl.attr('disabled', disabled);
        }

        function changeSubSiteState(disabled) {
            let subSiteSelectEl = $("#sub-site-select");
            let addSubSiteBtnEl = $("#add-sub-site-btn-a");
            let deleteSubSiteBtnEl = $("#delete-sub-site-btn-a");
            let editSubSiteBtnEl = $("#edit-sub-site-btn-a");

            subSiteSelectEl.attr('disabled', disabled);
            addSubSiteBtnEl.attr('disabled', disabled);
            deleteSubSiteBtnEl.attr('disabled', disabled);
            editSubSiteBtnEl.attr('disabled', disabled);
        }

        $("#user-type-select").change(function () {
            let id = $("#user-type-select").val();

            $.ajax({
                type: 'GET',
                url: "{!! route('users-by-type', ['id' => '']) !!}" + "/" + id,
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {"_token": "{{ csrf_token() }}"},

                success: function (data) {
                    $('#user-name-select option').remove();                   

                    let res = "<option selected>{{__('Name')}}</option>";
                    data.forEach(function (item) {
                        res = res + "<option value='" + item.id + "' id='" + item.id + "'>" + item.name + "</option>"
                    });
                    $("#user-name-select").append(res);
                },
                error: function (data) {
                    console.log('data ', data);
                }
            });
        });

        $("#site-select").change(function () {
            let siteId = $(this).val();
            let siteName = $("#site-select option[value=" + siteId + "]").html();
            let representative = $("#site-select option[value=" + siteId + "]").data('representative');
            representative = representative !== 'undefined' ? representative : '';
            // let companyId = $("#company-select").val();

            if (siteId == 0) {
                changeSubSiteState(true);
                $("#companyRepresentativeName").val('');
            } else {
                changeSubSiteState(false);
                $("#companyRepresentativeName").val(representative);
            }

            $("input[name=site-edit]").val(siteName);
            $("input[name=site-edit]").data('id', siteId);
            $("input[name=site-edit-representative]").val(representative);
            let subSiteRepresentative = $("#sub-site-select option:selected");
            if (subSiteRepresentative.val() > 0) {
                $("input[name=site-edit-representative]").attr('disabled', true);
                $("input[name=site-edit-representative]").val(subSiteRepresentative.data('representative'));
            }
            // $("#edit-site-company-name-select").val(companyId);

            $.ajax({
                type: 'GET',
                url: "{!! route('subsites-by-site', ['id' => '']) !!}" + "/" + siteId,
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {"_token": "{{ csrf_token() }}"},

                success: function (data) {

                    let res = "<option value='0' selected>{{__('Sub-site')}}</option>",
                        k = 0;
                    data.forEach(function (item) {
                        k++;
                        res = res + "<option value='" + item.id + "' data-representative='" + item.representative + "'>" + item.title + "</option>"
                    });
                    $("#sub-site-select").html(res);
                    if (k < 1) {
                        $('#sub-site-select').attr('disabled', true);
                    }
                },
                error: function (data) {
                    console.log('error ', data);
                }
            });

        });

        $(".btn-add-site").on('click', function () {
            let siteName = $("input[name=site]").val();
            if (!siteName || 0 === siteName.trim().length) {
                alert("{{__('Site name is required')}}");
                return;
            }

            // let companyId = $("#company-select").val(),
            let representative = $("input[name=representative]").val();

            $.ajax({
                type: 'POST',
                url: "{!! route('sites.store') !!}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "_token": "{{ csrf_token() }}",
                    "title": siteName,
                    // "company_id": companyId,
                    "representative": representative
                },
                //
                success: function (data) {
                    $("#site-select").append("<option value='" + data.id + "' data-representative='" + representative + "'>" + data.title + "</option>");
                    $("#companyRepresentativeName").val(representative);
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "{{__('Created!')}}",
                        showConfirmButton: false,
                        timer: 1500
                    })

                },
                error: function (data) {
                    swal({
                        title: "{{__('Error!')}}",
                        type:'error',
                        confirmButtonText: "{{__('Ok')}}"
                    })
                }
            });

        });

        $(".btn-delete-site").on('click', function () {
            let id = $("#site-select").val();

            if (!isNaN(parseFloat(id)) && isFinite(id)) {
                swal({
                    title: "{{__('Are you sure?(site linking)')}}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, delete it(site linking)')}}",
                    cancelButtonText: "{{__('No, cancel(site linking)')}}"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{!! route('sites.destroy', ['id' => '']) !!}" + "/" + id,
                            dataType: 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {"_token": "{{ csrf_token() }}"},

                            success: function (data) {
                                $("#site-select > option[value=" + id + "]").remove();
                                $("#companyRepresentativeName").val('');
                                swal({
                                    title: "{{__('Deleted!')}}",
                                    type:'success',
                                    confirmButtonText: "{{__('Ok')}}"
                                })
                            },
                            error: function (data) {
                                swal({
                                    title: "{{__('Error!')}}",
                                    type:'error',
                                    confirmButtonText: "{{__('Ok')}}"
                                })
                            }
                        });
                    }
                })
            } else {
                return;
            }
        });

        $(".btn-edit-site").on('click', function () {
            let companyId = $("#company-select").val();
            let siteName = $("input[name=site-edit]").val();
            let representative = $("input[name=site-edit-representative]").val();
            let id = $("input[name=site-edit]").data('id');
            // let companyName = $("#edit-site-company-name-select option[value=" + companyId + "]").html();

            if (!siteName || 0 === siteName.trim().length) {
                alert("{{__('Site name is required')}}");
                return;
            }

            $.ajax({
                type: 'PUT',
                url: "{!! route('sites.update', ['id' => '']) !!}" + "/" + id,
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "_token": "{{ csrf_token() }}",
                    "title": siteName,
                    // "company_id": companyId,
                    "representative": representative
                },

                success: function (data) {
                    $("#site-select option[value=" + data.id + "]").html(data.title);
                    // $("#company-select").val(data.company_id);
                    $("#companyRepresentativeName").val(representative);
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "{{__('Updated!')}}",
                        showConfirmButton: false,
                        timer: 1500
                    })

                },
                error: function (data) {
                    swal({
                        title: "{{__('Error!')}}",
                        type:'error',
                        confirmButtonText: "{{__('Ok')}}"
                    })
                }
            });

        });

        $(".btn-add-sub-site").on('click', function () {
            let siteName = $("input[name=sub-site]").val();
            if (!siteName || 0 === siteName.trim().length) {
                alert("{{__('Site name is required')}}");
                return;
            }

            let siteId = $("#site-select").val();
            let siteRepresentative = $("input[name=sub-site-representative]").val();

            $.ajax({
                type: 'POST',
                url: "{!! route('subsites.store') !!}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "_token": "{{ csrf_token() }}",
                    "title": siteName,
                    "site_id": siteId,
                    "representative": siteRepresentative
                },

                success: function (data) {
                    $("#sub-site-select").append("<option value='" + data.id + "' data-representative='" + siteRepresentative + "'>" + data.title + "</option>");

                    if (siteRepresentative.length > 0) {
                        $("input[name=site-edit-representative]").attr('disabled', true);
                        $("input[name=site-edit-representative]").val(siteRepresentative);
                        $("#companyRepresentativeName").val(siteRepresentative);
                    }
                    changeSubSiteState(false);
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "{{__('Created!')}}",
                        showConfirmButton: false,
                        timer: 1500
                    })

                },
                error: function (data) {
                    swal({
                        title: "{{__('Error!')}}",
                        type:'error',
                        confirmButtonText: "{{__('Ok')}}"
                    })
                }
            });

        });

        $(".btn-delete-sub-site").on('click', function () {
            let id = $("#sub-site-select").val();

            if (!isNaN(parseFloat(id)) && isFinite(id)) {
                swal({
                    title: "{{__('Are you sure?(site linking)')}}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, delete it(site linking)')}}",
                    cancelButtonText: "{{__('No, cancel(site linking)')}}"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{!! route('subsites.destroy', ['id' => '']) !!}" + "/" + id,
                            dataType: 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {"_token": "{{ csrf_token() }}"},

                            success: function (data) {
                                $("#sub-site-select > option[value=" + id + "]").remove();
                                let representativeName = $("#site-select option:selected").data('representative');
                                if (representativeName === 'undefined') {
                                    representativeName = '';
                                }
                                $("#companyRepresentativeName").val(representativeName);
                                $("input[name=site-edit-representative]").removeAttr('disabled');
                                swal({
                                    title: "{{__('Deleted!')}}",
                                    type:'success',
                                    confirmButtonText: "{{__('Ok')}}"
                                })
                            },
                            error: function (data) {
                                swal({
                                    title: "{{__('Error!')}}",
                                    type:'error',
                                    confirmButtonText: "{{__('Ok')}}"
                                })
                            }
                        });
                    }
                })
            } else {
                return;
            }
        });

        $("#sub-site-select").change(function () {
            let subSiteId = $(this).val();
            let subSiteName = $("#sub-site-select option[value=" + subSiteId + "]").html();
            let subSiteRepresentative = $("#sub-site-select option[value=" + subSiteId + "]").data('representative');
            let siteId = $("#site-select").val();
            if(typeof subSiteRepresentative == 'undefined'){
                subSiteRepresentative = $("#site-select option[value=" + siteId + "]").data('representative');
            }
            $("input[name=sub-site-edit]").val(subSiteName);
            $("input[name=sub-site-edit]").data('id', subSiteId);
            $("input[name=sub-site-edit-representative]").val(subSiteRepresentative);
            $("#edit-sub-site-company-name-select").val(siteId);
            $("#companyRepresentativeName").val(subSiteRepresentative);

            if (subSiteRepresentative !== 'undefined') {
                $("input[name=site-edit-representative]").attr('disabled', true);
                $("input[name=site-edit-representative]").val(subSiteRepresentative);
            }
        });

        $(".btn-edit-sub-site").on('click', function () {
            let siteId = $("#site-select").val();
            let subSiteName = $("input[name=sub-site-edit]").val();
            let subSiteRepresentative = $("input[name=sub-site-edit-representative]").val();
            let id = $("input[name=sub-site-edit]").data('id');
            // let siteName = $("#edit-sub-site-company-name-select option[value=" + siteId + "]").html();

            if (!subSiteName || 0 === subSiteName.trim().length) {
                alert("{{__('Site name is required')}}");
                return;
            }

            $.ajax({
                type: 'PUT',
                url: "{!! route('subsites.update', ['id' => '']) !!}" + "/" + id,
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "_token": "{{ csrf_token() }}",
                    "title": subSiteName,
                    "site_id": siteId,
                    "representative": subSiteRepresentative
                },

                success: function (data) {
                    $("#sub-site-select option[value=" + data.id + "]").html(data.title);
                    $("#site-select").val(data.site_id);
                    if (subSiteRepresentative.length > 0) {
                        $("input[name=site-edit-representative]").attr('disabled', true);
                        $("input[name=site-edit-representative]").val(subSiteRepresentative);
                        $("#companyRepresentativeName").val(subSiteRepresentative);
                    }
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "{{__('Updated!')}}",
                        showConfirmButton: false,
                        timer: 1500
                    })

                },
                error: function (data) {
                    swal({
                        title: "{{__('Error!')}}",
                        type:'error',
                        confirmButtonText: "{{__('Ok')}}"
                    })
                }
            });
        });

        $(".add-link-btn").on('click', function () {
            let userId = $("#user-name-select").val();
            // let companyId = $("#company-select").val();
            let siteId = $("#site-select").val();
            let subSiteId = $("#sub-site-select").val();

            $.ajax({
                type: 'POST',
                url: "{!! route('links.store') !!}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    "_token": "{{ csrf_token() }}",
                    "user_id": userId,
                    // "company_id": companyId,
                    "site_id": siteId,
                    "sub_site_id": subSiteId
                },

                success: function (data) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "{{__('Created!')}}",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location = "{{ url('/companies') }}";
                },
                error: function (data) {
                    swal({
                        title: "{{__('Error!')}}",
                        type:'error',
                        confirmButtonText: "{{__('Ok')}}"
                    })
                }
            });

        });

        function deleteLink(id) {
            swal({
                title: "{{__('Are you sure?(site linking)')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, delete it(site linking)')}}",
                cancelButtonText: "{{__('No, cancel(site linking)')}}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{!! route('links.destroy', ['id' => '']) !!}" + "/" + id,
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {"_token": "{{ csrf_token() }}"},

                        success: function (data) {
                            $("#" + id).remove();
                            swal({
                                title: "{{__('Deleted!')}}",
                                type:'success',
                                confirmButtonText: "{{__('Ok')}}"
                            })
                        },
                        error: function (data) {
                            swal({
                                title: "{{__('Error!')}}",
                                type:'error',
                                confirmButtonText: "{{__('Ok')}}"
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection
