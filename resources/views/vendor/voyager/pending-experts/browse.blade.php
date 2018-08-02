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
                                <th>Name</th>
                                <th>Age</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Profession(s)</th>
                                <th>Address</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataTypeContent as $data)
                            <tr>
                                <td>
                                    <img src="{{$data->avatar}}" style="width:100px">
                                </td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->age}}</td>
                                <td>{{$data->mobile}}</td>
                                <td>{{$data->email}}</td>
                                <td>
                                    @foreach($data->professions as $profession)
                                    {{$profession->profession->name}}<br/>
                                    @endforeach
                                </td>
                                <td>{{$data->address}}</td>    
                                <td class="no-sort no-click" id="bread-actions">
                                    @if (Voyager::can('read_'.$dataType->name))
                                    <a href="/admin/experts/{{$data->id}}" title="View" class="btn btn-sm btn-warning pull-right">
                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View Expert</span>
                                    </a>
                                    @endif

                                    <a href="/admin/expert/approve/{{$data->id}}" title="Approve" class="btn btn-sm btn-primary pull-right delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                        <i class="voyager-check"></i> <span class="hidden-xs hidden-sm">Approve Expert</span>
                                    </a>

                                    @if (Voyager::can('edit_'.$dataType->name))
                                    <a href="javascript:;" title="Block" data-expertID="{{$data->id}}" class="btn btn-sm btn-danger pull-right block">
                                        <i class="voyager-edit"></i> 
                                        <span class="hidden-xs hidden-sm" data-expertID="{{$data->id}}">Block Expert</span>
                                    </a>
                                    @endif
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
</script>
@stop
