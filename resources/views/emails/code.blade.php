@extends('beautymail::templates.widgets')

@section('content')

    @include('beautymail::templates.widgets.newfeatureStart')

    <p class="secondary"><strong>Hello {{$data->name}}, </strong></p>
    <p>Kindly use the code {{$data->code}} for your verification. It expires in 30 minutes.</p>
    <p>Thank You.</p>
    <p>Powered By OneflareTech.</p>

    @include('beautymail::templates.widgets.newfeatureEnd')

@stop