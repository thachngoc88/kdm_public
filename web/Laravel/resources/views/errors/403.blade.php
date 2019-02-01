@extends('layout')

@section('pageTitle', "")
@section('bodyClass', 'challenge')

@section('breadcrumb')
@endsection


@section('sidebar')
@stop

@section('content')

<div class="body_error">
    <div class="bad_request">アクセスできません</div>
    <div class="number_error">403</div>
    <div class="message_error">このページにはアクセスできません</div>
</div>

@endsection