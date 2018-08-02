@extends('voyager::master')

@section('page_title','All '.$dataType->display_name_plural)

@section('page_header')
<h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
    @if (Voyager::can('add_'.$dataType->name))
    <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success">
        <i class="voyager-plus"></i> Add New
    </a>
    @endif
</h1>
@include('voyager::multilingual.language-selector')
@stop

@section('content')
<div class="page-content container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body table-responsive">
                    <table id="dataTable" class="row table table-hover">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name of Expert</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Address</th>
                                <!--<th>Reason</th>-->
                                <th>Reported By</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataTypeContent as $data)
                            <tr>
                                <td>
                                    <img src="{{$data->user->avatar}}" style="width:100px">
                                </td>
                                <td>{{$data->expert->name}}</td>
                                <td>{{$data->expert->age}}</td>
                                <td>{{$data->expert->gender}}</td>
                                <td>{{$data->expert->email}}</td>
                                <td>{{$data->expert->address}}</td>
                                
                                <td>{{$data->user->name}}</td>

                                <td class="no-sort no-click" id="bread-actions">
                                    @if (Voyager::can('delete_'.$dataType->name))
                                    <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Dismiss Report</span>
                                    </a>
                                    @endif
                                    @if (Voyager::can('edit_'.$dataType->name))
                                    <a href="javascript:;" title="Block" data-expertID="{{$data->expert_id}}" class="btn btn-sm btn-danger pull-right block">
                                        <i class="voyager-edit"></i> 
                                        <span class="hidden-xs hidden-sm" data-expertID="{{$data->expert_id}}">Block Expert</span>
                                    </a>

                                    @endif
                                    @if (Voyager::can('read_'.$dataType->name))
                                    <a href="{{ route('voyager.users.show', $data->expert_id) }}" title="View" class="btn btn-sm btn-warning pull-right">
                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View Expert</span>
                                    </a>
                                    @endif

                                    <a href="/admin/reported-users/{{ $data->id }}" title="View" class="btn btn-sm btn-warning pull-right">
                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View Report</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (isset($dataType->server_side) && $dataType->server_side)
                    <div class="pull-left">
                        <div role="status" class="show-res" aria-live="polite">Showing {{ $dataTypeContent->firstItem() }} to {{ $dataTypeContent->lastItem() }} of {{ $dataTypeContent->total() }} entries</div>
                    </div>
                    <div class="pull-right">
                        {{ $dataTypeContent->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete
                    this {{ strtolower($dataType->display_name_singular) }}?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="Yes">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal modal-danger fade" tabindex="-1" id="block_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to Block
                    this Expert ?</h4>
            </div>
            <div class="modal-footer">

                <a href="/expert/block" type="button" id="blockBtn" class="btn btn-default" data-dismiss="modal">Yes Block Expert</a>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
@stop

@section('javascript')
<!-- DataTables -->
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
@endif
@if($isModelTranslatable)
<script src="{{ voyager_asset('js/multilingual.js') }}"></script>
@endif
<script>
    $(document).ready(function () {
    @if (!$dataType -> server_side)
            var table = $('#dataTable').DataTable({
    "order": []
            @if (config('dashboard.data_tables.responsive')), responsive: true @endif
    });
    @endif

            @if ($isModelTranslatable)
            $('.side-body').multilingual();
    @endif
    });
    var deleteFormAction;
    $('td').on('click', '.delete', function (e) {
    var form = $('#delete_form')[0];
    if (!deleteFormAction) { // Save form action initial value
    deleteFormAction = form.action;
    }

    form.action = deleteFormAction.match(/\/[0-9]+$/)
            ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
            : deleteFormAction + '/' + $(this).data('id');
    console.log(form.action);
    $('#delete_modal').modal('show');
    });
</script>
@stop
