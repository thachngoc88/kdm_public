@extends('layout')

@section('pageTitle', "{$unit->curriculum->subject->name}{$unit->number}  {$unit->name}")
@section('bodyClass', 'challenge unit')

@section('breadcrumb')

@endsection


@section('sidebar')
@stop

@section('content')

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-10 col-md-offset-1">
            <!-- begin panel -->
            <div class="panel panel-inverse disable-draggable">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                    </div>
                    <h4 class="panel-title">問題・解答のダウンロードと採点入力</h4>
                </div>
                <div class="panel-body">
                    @inject('roler', 'App\Services\Roler')
                    <ul class="media-list media-list-with-divider">
                        <li class="media media-sm">
                            <?php
                            $challenge = $unit->workbooks[0];
                            $isExist   = App\Services\Utils::checkExistBothFileDownloadInWorkbook($challenge, true);
                            ?>
                            <div class="media-body">
                                <h4 class="media-heading">チャレンジ問題 {{$unit->number}}</h4>
                                <div class="row">
                                    <div class="col-sm-4
                                    @if ($roler->isChallengeUser())
                                    col-sm-offset-1
                                    @else
                                    col-sm-offset-2
                                    @endif
                                    action-button text-center">
                                        <a href="{{$isExist ? route('pdfdownload', ['type'=>'q', 'workbookId'=>$challenge->id]):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} target="_blank" class="btn btn-lg btn-info m-t-5 m-b-5">
                                            <i class="fa fa-file-o fa-fw" aria-hidden="true"></i>&nbsp;<strong>問題PDF</strong></a>
                                    </div>
                                    <div class="
                                    @if ($roler->isChallengeUser())
                                    col-sm-2
                                    @else
                                    col-sm-4
                                    @endif
                                    action-button text-center">
                                        <a href="{{$isExist ? route('pdfdownload', ['type'=>'a', 'workbookId'=>$challenge->id]):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} target="_blank" class="btn btn-lg btn-success m-t-5 m-b-5">
                                            <i class="fa fa-file fa-fw" aria-hidden="true"></i>&nbsp;<strong>解答PDF</strong></a>
                                    </div>
                                    @if ($roler->isChallengeUser())
                                    <div class="col-sm-4 action-button text-center">
                                        <a href="{{$isExist ? route('recordinput', $challenge->id):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} class="btn btn-lg btn-warning m-t-5 m-b-5">
                                            <i class="fa fa-edit fa-fw" aria-hidden="true"></i>&nbsp;<strong>採点入力</strong></a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="media media-sm">
                            <ul class="media-list">
                            @foreach($unit->workbooks as $workbook)
                            @if($workbook != $challenge)
                            <?php
                            $isExist=App\Services\Utils::checkExistBothFileDownloadInWorkbook($workbook, true);
                            ?>
                                <li class="media-sm p-b-25">
                                    <div class="media-body">
                                        <h4 class="media-heading">補充問題 {{$unit->number}}-{{$workbook->number}}<span class="p-l-15">{{$workbook->title}}</span></h4>
                                        <div class="row">
                                            <div class="col-sm-4
                                            @if ($roler->isChallengeUser())
                                            col-sm-offset-1
                                            @else
                                            col-sm-offset-2
                                            @endif
                                            action-button text-center">
                                                <a href="{{$isExist ? route('pdfdownload', ['type'=>'q', 'workbookId'=>$workbook->id]):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} target="_blank" class="btn btn-lg btn-info m-t-5 m-b-5">
                                                    <i class="fa fa-file-o fa-fw" aria-hidden="true"></i>&nbsp;<strong>問題PDF</strong></a>
                                            </div>
                                            <div class="
                                             @if ($roler->isChallengeUser())
                                            col-sm-2
                                            @else
                                            col-sm-4
                                            @endif
                                            action-button text-center">
                                                <a href="{{$isExist ? route('pdfdownload', ['type'=>'a', 'workbookId'=>$workbook->id]):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} target="_blank" class="btn btn-lg btn-success m-t-5 m-b-5">
                                                    <i class="fa fa-file fa-fw" aria-hidden="true"></i>&nbsp;<strong>解答PDF</strong></a>
                                            </div>
                                            @if ($roler->isChallengeUser())
                                            <div class="col-sm-4 action-button text-center">
                                                <a href="{{$isExist ? route('recordinput', $workbook->id):'javascript:void(0)'}}" {{$isExist ? '':'disabled'}} class="btn btn-lg btn-warning m-t-5 m-b-5">
                                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i>&nbsp;<strong>採点入力</strong></a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
    <div class="link">
        <a href="{{ route("mapsheet", $unit->curriculum->id) }}" id="backBtn">やったねシートへ</a>
    </div>

@endsection