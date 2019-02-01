@extends('layout')

@section('pageTitle', "採点入力")
@section('bodyClass', 'challenge record-input')

@section('breadcrumb')

@endsection


@section('sidebar')
@stop

@section('content')

<!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-6 col-md-offset-3">
        <!-- begin panel -->
        <div class="panel panel-inverse disable-draggable">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    </div>
                <h4 class="panel-title">
                    @if($workbook->number == 0)
                        チャレンジ問題 {{$workbook->unit->number}}
                    @else
                        補充問題 {{$workbook->unit->number}}-{{$workbook->number}}
                    @endif
                </h4>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">

                    <div class="form-group table-row header">
                        <div class="row">
                            <div class="col-xs-3 hidden-sm hidden-md hidden-lg" style="">問題</div>
                            <div class="col-xs-3 hidden-xs text-right" style="">問題</div>
                            <div class="col-xs-8 col-xs-offset-1">採点</div>
                        </div>
                    </div>

                    @foreach($workbook->questions as $key => $question)
                    <div class="eachrow">
                    <div class="row form-group table-row">
                        <label class="col-sm-3 control-label">{{$key + 1}}</label>
                        <input class="quesion" type="hidden" value="{{ $question->id }}">
                        <div class="col-sm-8 col-sm-offset-1">
                            <div class="radio radio-css radio-inline radio-inverse m-r-40">

                                <input type="radio" name="radioInlineCss1{{ $question->id }}" id="radio_inline_css_1_1{{ $question->id }}" value="none" <?php if((isset($question->records[0]) && $question->records[0]->record==null) || !isset($question->records[0])) echo 'checked' ?>  />


                                <label for="radio_inline_css_1_1{{ $question->id }}">
                                    未解答
                                </label>
                            </div>
                            <div class="radio radio-css radio-inline radio-success">

                                <input type="radio" name="radioInlineCss1{{ $question->id }}" id="radio_inline_css_1_2{{ $question->id }}" value="correct" <?php if(isset($question->records[0]) && $question->records[0]->record==1) echo 'checked' ?>   />

                                <label for="radio_inline_css_1_2{{ $question->id }}">
                                    正解
                                </label>
                            </div>
                            <div class="radio radio-css radio-inline radio-danger">

                                <input type="radio" name="radioInlineCss1{{ $question->id }}" id="radio_inline_css_1_3{{ $question->id }}" value="incorrect" <?php if(isset($question->records[0]) && $question->records[0]->record==2) echo 'checked' ?> />

                                <label for="radio_inline_css_1_3{{ $question->id }}">
                                    不正解
                                </label>
                            </div>
                        </div>
                    </div>
                    </div>
                @endforeach
                    <div class="form-group m-t-30">
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-2 text-center">
                                <button  class="btn btn-lg btn-info m-t-5" id="recordupdater"><span class="p-l-15 p-r-15">更新</span></button>
                            </div>
                            <div class="col-sm-4 text-center">
                                <button  class="btn btn-lg btn-danger m-t-5" id="canceler">キャンセル</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-12 -->
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

            $('#canceler').click(function () {
                window.location.href = '{{ route("unit", $workbook->unit->id) }}';
                return false;
            });

            $('#recordupdater').click(function () {
                $t = $(this);
                $t.prop('disabled', true).text('更新中');
                var records = new Array();
                $('div.eachrow').each(function (index, value) {
                    var question= $(this).find('input.quesion').val();
                    obj = {};
                    var origin = 'radioInlineCss1';
                    var nameIn = origin + question;
                    //console.log(nameIn);
                    obj['question'] = question;
                    obj['answer'] = $(this).find('input[name='+nameIn+']:checked').val();
                    records.push(obj);
                });
                all = {workid:'{{$workbook->id}}',userid:'{{$challengeUser->user_id}}',cu:'{{$challengeUser->id}}',records:records}
                $.ajax({
                    method: 'post',
                    url: '{{route("recordinput.save")}}',
                    data: all,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token':'{{ csrf_token() }}',
                    },
                    success: function(data){
                        if(!data.error){
                            location.href = "{{ route("unit", $workbook->unit->id) }}";
//                            showGritterNotification('更新完了','採点データを更新しました');
                        } else {
                            if(data.status_code == 422){
                                showGritterNotification('更新失敗','エラーにより採点データを更新できませんでした');
                                $t.prop('disabled', false).text('更新');
                            }
                        }
                    },
                    error: function(data){
//                        console.log(data);
                        showGritterNotification('更新失敗','サーバエラーにより採点データを更新できませんでした');
                        console.warn("採点データの更新に失敗しました");
                        $t.prop('disabled', false).text('更新');
                    }

                });
                return false;
            });

        });
    </script>
@endsection

