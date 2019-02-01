@extends('layout')

@section('pageTitle', '励ましメッセージ')
@section('bodyClass', 'admin')

@section('breadcrumb')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('home.index', ['action'=>'admin']) }}">ダッシュボード</a></li>
        <li><a href="{{ route('setting') }}">県教委担当者</a></li>
        <li class="active">励ましメッセージ</li>
    </ol>
@endsection

@section('content')
    @foreach ($curriculums as $curriculum)
        <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse disable-draggable">
                    <div class="panel-heading">
                        <div class="panel-heading-btn"></div>
                        <h4 class="panel-title">メッセージ一覧</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table" id="message-table">
                                <thead>
                                <tr>
                                    <th>タイミング</th>
                                    <th>条件</th>
                                    <th>メッセージ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($curriculum->timings as $timing)
                                    @php($conditions = $timing->conditions)
                                    @foreach($conditions as $condition)
                                        <tr data-target-message="{{$timing->id}}-{{$condition->id}}">
                                            @if ($loop->first)
                                                <td rowspan="{{ count($conditions)}}">{{$timing->title}}</td>
                                            @endif

                                            <td class="condition" data-timing-title="{{$timing->title}}" data-target-cond_id="{{$condition->id}}">
                                                <p>{{$condition->condition}}</p>
                                                @php($time_from = $condition->time_from)
                                                @php($time_until = $condition->time_until)
                                                @if(!empty($time_from) && !empty($time_until))
                                                    @php($isCheckedTime = "checked")
                                                    @php($displayTimepicker = "")
                                                @else
                                                    @php($isCheckedTime = "")
                                                    @php($displayTimepicker = "display:none;")
                                                @endif
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input class="time-check" type="checkbox" value="" {{$isCheckedTime}}/>
                                                            時間指定する
                                                        </label>
                                                    </div>
                                                    <div class="input-group input-timerange" style="{{$displayTimepicker}}">
                                                        <div class="input-group bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker-input timepicker-input-from" value="{{date('h:i A e', strtotime($time_from))}}" title="日時（から）を入力" />
                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                        </div>
                                                        <span class="input-group-addon to">～</span>
                                                        <div class="input-group bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker-input timepicker-input-until" value="{{date('h:i A e', strtotime($time_until))}}" title="日時（まで）を入力" />
                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                            <td class="message">
                                                    @php($messages = $condition->messages)
                                                    @foreach($messages as $message)
                                                        <a href="#modal-dialog" style="margin-bottom: 5px" class="btn btn-info btn-sm" data-toggle="modal" data-target-message-id="{{$message->id}}">{{$message->text}}</a><br/>
                                                    @endforeach
                                                </td>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-12 -->
        </div>
        <!-- end row -->

        @break
    @endforeach


    <!-- #modal-dialog -->
    <div class="modal fade" id="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="message-update-form">
                    <input id="message-update-target" type="hidden" name="target" value="" />
                    <input id="message-update-target-id" type="hidden" name="msgid" value="" />
                    <input id="message-update-target-text-before" type="hidden" name="msgBfr" value="" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title m-b-10">メッセージ編集</h4>
                        <p id="target-info" class="text-success"></p>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" placeholder="Textarea" rows="5" id="message-update-box"></textarea>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">キャンセル</a>
                        <a href="javascript:;" class="btn btn-sm btn-success" id="message-updater">更新</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
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

        var updateMsgAjax = function (msgId, msg) {
            $.ajax({
                type: "POST",
                url: "{{route('msg.update', ['id'=>''])}}"+"/"+ msgId,
                data: {msg: msg},
                dataType: 'json',
                headers: {
                    'X-CSRF-Token':'{{ csrf_token() }}',
                },
                success: function( data ) {
                    if(!data.error){
                        showGritterNotification('更新完了','メッセージを更新しました');
                        $('*[data-target-message-id='+msgId+']').text(msg);
                    } else {
                        if(data.status_code == 422){
                            showGritterNotification('更新できません', 'メッセージは255文字以内で入力する必要があります');
                        }
                    }
                },
                error: function (data) {
                    console.log('ERROR:' + data.data);
                    showGritterNotification('更新できません','メッセージ更新ができません。');
                }
            });
        };

        var updateTimeConditionAjax = function (condId, time_from, time_until){
            $.ajax({
                type: "POST",
                url: "{{route('cond.update',['id'=>''])}}"+"/"+ condId,
                data: {timeFrom: time_from, timeUntil: time_until},
                dataType: 'json',
                headers: {
                    'X-CSRF-Token':'{{ csrf_token() }}',
                },
                success: function( data ) {
                    /*if(!data.error){
                        showGritterNotification('更新完了','メッセージを更新しました');
                    } else {
                        showGritterNotification('更新できません','条件の時間指定更新ができません');
                    }*/
                },
                error: function (data) {
                    //showGritterNotification('更新できません','条件の時間指定更新ができません');
                }
            });
        };

        var handleMessageModifier = function() {
            $('#message-table a[data-toggle="modal"]').click(function () {
                var $t = $(this);
                var $tr = $t.closest('tr');
                var target = $tr.attr('data-target-message');
                var $cond = $tr.find('.condition');
                var cond = $cond.attr('data-timing-title') + ' :: ' + $cond.find('p').text();

                var msgId = $(this).attr('data-target-message-id');
                var bef = $t.text();
                $('#target-info').text(cond);
                $('#message-update-box').val(bef);
                $('#message-update-target-text-before').val(bef);
                $('#message-update-target').val(target);
                $('#message-update-target-id').val(msgId);
            });
        };

        var handleUpdateMessage = function() {
            $('#message-updater').click(function () {
                var target = $('#message-update-target').val();
                var msgBef = $('#message-update-target-text-before').val();
                var msg = $('#message-update-box').val();
                var msgId = $('#message-update-target-id').val();

                if(msg != msgBef){
                    updateMsgAjax(msgId,msg);
                } else {
                    showGritterNotification('確認',"メッセージを変更してください");
                }
                $('#modal-dialog').modal('hide');
                return false;
            });
        };

        var handleTimeCheck = function () {
            var update = function(){
                $t = $(this);
                var $tp = $t.closest('.form-group').find('.input-timerange');
                var $condId = $t.closest('td').attr('data-target-cond_id');
                if($t.prop('checked')){
                    var $time_from = $t.closest('.form-group').find('.timepicker-input-from').val();
                    var $time_until = $t.closest('.form-group').find('.timepicker-input-until').val();
                    updateTimeConditionAjax($condId, $time_from,$time_until);
                    $tp.fadeIn(300, function(){$tp.removeClass('nouse');});
                }else{
                    updateTimeConditionAjax($condId);
                    $tp.fadeOut(300, function(){$tp.addClass('nouse');});
                }
            };
            $('.time-check').click(update);
            //update();
        };

        var handleFormTimePicker = function () {
            $('.timepicker-input').timepicker();
            $( ".timepicker-input" ).change(function() {
                var changeTime = function($t){
                    var $condId = $t.closest('td').attr('data-target-cond_id');
                    var $time_from = $t.closest('.form-group').find('.timepicker-input-from').val();
                    var $time_until = $t.closest('.form-group').find('.timepicker-input-until').val();
                    updateTimeConditionAjax($condId, $time_from,$time_until);
                };
                changeTime($(this));
            });
        };

        var Nm = function () {
            "use strict";
            return {
                //main function
                init: function () {
                    handleFormTimePicker();
                    handleUpdateMessage();
                    handleTimeCheck();
                    handleMessageModifier();
                }
            };
        }();

        $(document).ready(function() {
            Nm.init();
        });
    </script>
@endsection

