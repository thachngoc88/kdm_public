@extends('layout')

@section('pageTitle', 'ID/パスワードの編集')
@section('bodyClass', 'admin users')

@section('breadcrumb')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('home.index', ['action'=>'admin']) }}">ダッシュボード</a></li>
        <li><a href="{{ route('setting') }}">県教委担当者</a></li>
        <li class="active">ID/パスワードの編集</li>
    </ol>
@endsection

@section('content')

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-md-6">
            <!-- begin panel -->
            <div class="panel panel-inverse disable-draggable" data-sortable-id="id-finder-panel">
                <div class="panel-heading">
                    <div class="panel-heading-btn"></div>
                    <h4 class="panel-title">ログイン名検索</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="id-finder">

                        <div class="form-group">
                            <label class="col-md-4 control-label">ログイン名</label>
                            <div class="col-md-8">
                                <input type="text" name="login_id" value="" class="form-control filter" placeholder="ab0123" title="ログイン名を入力" />
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->

        @include('classfiltering.selectors')

    </div>
    <!-- end row -->

    <!-- begin col-12 -->
    <div class="col-md-12">
        {!! $dataTable->table() !!}
    </div>
    <!-- end col-12 -->
@include('modal')

    <!-- #modal-dialog -->
    <div class="modal fade" id="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="user-update-form">
                    <input id="user-update-target" type="hidden" name="target" value="" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title m-b-10">ユーザ情報再設定</h4>
                        <p id="target-info" class="text-success"></p>
                    </div>
                    <div class="modal-body">
                        <input id="passup" class="form-control input-lg" placeholder="パスワード" type="password" title="パスワードを入力">
                        <input id="passupcf" class="form-control input-lg m-t-5" placeholder="パスワード（確認）" type="password" title="パスワード（確認）を入力">

                        <div class="form-group m-t-10">
                            <div class="col-md-12">
                                <div class="radio radio-css radio-inline radio-success">
                                    <input type="radio" name="enabled" id="enabledRadio" value="1" checked />
                                    <label for="enabledRadio">
                                        有効
                                    </label>
                                </div>
                                <div class="radio radio-css radio-inline radio-danger">
                                    <input type="radio" name="enabled" id="disabledRadio" value="0" />
                                    <label for="disabledRadio">
                                        無効
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">キャンセル</a>
                        <a href="javascript:;" class="btn btn-sm btn-success" id="password-updater">更新</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
@endpush

@section('extraScript')
    <script>
        var csrfToken = "{{ csrf_token() }}";
        var page = "users";
        var dt = null;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {!! $dataTable->scripts() !!}

    <script>

        $(document).on('click', '#dataTableBuilder tbody tr', function(){
            $('#modal-dialog').modal('show');
        });


        $(function(){
            dt = $('#dataTableBuilder')
                .on('draw.dt', function (e) {
                   console.log('on draw.dt');
                })
                .dataTable();

            var handleFinderInput = function(e){
                console.log('begin draw.dt');
                dt.api().draw();
                console.log('after draw.dt');
            };

            $('#id-finder input').on({
                "input" : handleFinderInput
            });


        });

        var onChangeClassFiltering = function(){
            dt.api().draw();
        };
        @include('classfiltering.script')

        var userId;
        var showGritterNotification = function($title, $text) {
            $.gritter.add({
                title: $title,
                text: $text,
                sticky: false,
                time: ''
            });
        };

        $(document).on('click', '#dataTableBuilder tbody tr', function(){
            var data = window.LaravelDataTables['dataTableBuilder'].row($(this)).data();
            userId = data["id"];
            var enabled = data["enabled"] == "○" ? 1 : 0;
            $('#modal-dialog').modal('show');
            $('#passup').val('');
            $('#passupcf').val('');
            $('#user-update-form input[name=enabled]').val([enabled]);
        });

        $('#password-updater').click(function () {
            var pass = $('#passup').val();
            var passconf =  $('#passupcf').val();
            var enabled =  $('#user-update-form input[name=enabled]:checked').val();
            $.ajax({
                method: 'post',
                url: '{{route("users.update", ["id"=>''])}}' + '/' + userId,
                data: {
                    password: pass,
                    password_confirm: passconf,
                    enabled: enabled
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-Token':'{{ csrf_token() }}',
                },
                success: function(data){
                    if(!data.error){
                        showGritterNotification('更新完了','ユーザ情報を更新しました');
                        dt.api().draw();
                    } else {
                        if(data.status_code == 422){
                            showGritterNotification('更新失敗','更新できません。入力内容を再確認するか、管理者へお問い合わせください');
                        }
                    }
                },
                error: function(data){
                    console.log(data);
                }

            });
            $('#modal-dialog').modal('hide');
        });
    </script>

@endsection
