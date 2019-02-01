@extends('layout')

@section('pageTitle', '県教委担当者')
@section('bodyClass', 'admin')

@section('breadcrumb')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('home.index', ['action'=>'admin']) }}">ダッシュボード</a></li>
        <li class="active">県教委担当者</li>
    </ol>
@endsection

@section('content')

    <!-- begin row -->
    <div class="row">
        <div class="row">
            <!-- begin col-3 -->
            <div class="col-md-3 col-md-offset-3 col-sm-6">
                <a href="{{ route('messages') }}">
                    <div class="widget widget-stats bg-green">
                        <div class="stats-icon stats-icon-lg"><i class="fa fa-comment-o fa-fw"></i></div>
                        <div class="stats-title">励ましメッセージ編集</div>
                        <div class="stats-desc">励ましメッセージの時間や内容を編集</div>
                    </div>
                </a>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('users') }}">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon stats-icon-lg"><i class="fa fa-user fa-fw"></i></div>
                        <div class="stats-title">ID/パスワードの編集</div>
                        <div class="stats-desc">児童・保護者用IDのパスワードを編集</div>
                    </div>
                </a>
            </div>
            <!-- end col-3 -->
        </div>
        {{--<div class="row">--}}
            {{--<!-- begin col-3 -->--}}
            {{--<div class="col-md-2 col-md-offset-3 col-sm-3">--}}
                {{--<a href="{{ route('backupdownload') }}" id="backup-download-0">--}}
                    {{--<div class="widget widget-stats bg-purple">--}}
                        {{--<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-o fa-fw"></i></div>--}}
                        {{--<div class="stats-title">backup download 0</div>--}}
                        {{--<div class="stats-desc"></div>--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
            {{--<!-- end col-3 -->--}}
            {{--<!-- begin col-3 -->--}}
            {{--<div class="col-md-2 col-sm-3">--}}
                {{--<a href="{{ route('backupdownload',['count'=>1]) }}" id="backup-download-1">--}}
                    {{--<div class="widget widget-stats bg-purple">--}}
                        {{--<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-o fa-fw"></i></div>--}}
                        {{--<div class="stats-title">backup download 1</div>--}}
                        {{--<div class="stats-desc"></div>--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
            {{--<!-- end col-3 -->--}}
            {{--<!-- begin col-3 -->--}}
            {{--<div class="col-md-2 col-sm-3">--}}
                {{--<a href="{{ route('backupdownload',['count'=>2]) }}" id="backup-download-1">--}}
                    {{--<div class="widget widget-stats bg-purple">--}}
                        {{--<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-o fa-fw"></i></div>--}}
                        {{--<div class="stats-title">backup download 2</div>--}}
                        {{--<div class="stats-desc"></div>--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
            {{--<!-- end col-3 -->--}}
        {{--</div>--}}
        <div class="row">
            <!-- begin col-3 -->
            <div class="col-md-3 col-md-offset-3 col-sm-4">
                <a href="javascript:void(0)" id="uploadpdf">
                    <div class="widget widget-stats bg-purple">
                        <div class="stats-icon stats-icon-lg"><i class="fa fa-refresh fa-fw"></i></div>
                        <div class="stats-title">PDF配置状態更新</div>
                        <div class="stats-desc refresh">更新中・・・</div>
                        <div class="stats-desc">やったねシートの表示を現在のPDF配置状態に更新</div>
                    </div>
                </a>
            </div>
            <!-- end col-3 -->
        </div>
    </div>
    <!-- end row -->

@endsection

@push('scripts')
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
@endpush

@section('extraScript')
<script>
        var showGritterNotification = function($title, $text) {
            $.gritter.add({
                title: $title,
                text: $text,
                sticky: false,
                time: ''
            });
        }
        $(document).ready(function() {
            $('#uploadpdf').click(function () {
                $.ajax({
                    method: 'post',
                    url: '{{route("setting.save")}}',
                    data: {id:1,name:'title'},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        console.log(data);
                        if (!data.error) {
                           showGritterNotification('更新完了','PDF配置状態を更新しました');
                        } else {
                            if (data.status_code == 422) {
                                showGritterNotification('更新失敗', 'エラーによりPDF配置状態を更新できませんでした(1)');
                            }
                        }
                        $('#uploadpdf').find('.stats-desc').hide().filter(':not(.refresh)').show();
                    },
                    error: function (data) {
                        console.log(data);
                        showGritterNotification('更新失敗', 'エラーによりPDF配置状態を更新できませんでした(2)');
                        $('#uploadpdf').find('.stats-desc').hide().filter(':not(.refresh)').show();
                    }

                });
                $(this).find('.stats-desc').hide().filter('.refresh').show();
                return false;
            });
        });
</script>
@endsection