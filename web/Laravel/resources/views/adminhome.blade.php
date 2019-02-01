@extends('home')

@section('pageTitle', '教委担当者 ダッシュボード')
@section('bodyClass', 'admin')
@section('content')
    <!-- begin row -->
    <div class="row menu-box">
        <!-- begin col-3 -->
        <div class="col-md-3 col-md-offset-3 col-sm-6">
            <a href="{{ route('aggregation') }}">
                <div class="widget widget-stats bg-green">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel-o fa-fw"></i></div>
                    <div class="stats-title">集計リスト</div>
                    <div class="stats-desc">　</div>
                </div>
            </a>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('individual') }}">
                <div class="widget widget-stats bg-blue">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-id-badge fa-fw"></i></div>
                    <div class="stats-title">個人別リスト</div>
                    <div class="stats-desc">　</div>
                </div>
            </a>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->

    <!-- begin row -->
    <div class="row menu-box">
    @inject('roler', 'App\Services\Roler')

    @if ($roler->isPrefectureUser())
        <!-- begin col-3 -->
            <div class="col-md-3 col-md-offset-3 col-sm-6">
                <a href="{{ route('setting') }}">
                    <div class="widget widget-stats bg-purple">
                        <div class="stats-icon stats-icon-lg"><i class="fa fa-cog fa-fw"></i></div>
                        <div class="stats-title">県教委担当者</div>
                        <div class="stats-desc">励ましメッセージ、ユーザの管理</div>
                    </div>
                </a>
            </div>
            <!-- end col-3 -->
    @endif

    <!-- begin col-3 -->
        <div class="col-md-3 col-sm-6 @if (!$roler->isPrefectureUser()) col-md-offset-3 @endif">
            <a href="{{ route('home.index', ['action'=>'challenge']) }}">
                <div class="widget widget-stats @if (!$roler->isPrefectureUser()) bg-purple @else bg-black @endif">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-newspaper-o fa-fw"></i></div>
                    <div class="stats-title">問題・解答（科目選択）</div>
                    <div class="stats-desc">マップシート、PDFダウンロード</div>
                </div>
            </a>
        </div>
        <!-- end col-3 -->


    @if (!$roler->isPrefectureUser())
    <!-- begin col-3 -->
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-aqua">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-cog fa-fw"></i></div>
                <div class="stats-title">マニュアルダウンロード</div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'school'])}}"><i class="fa fa-download fa-fw"></i> H30学校用マニュアル</a></div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'challengeuser'])}}"><i class="fa fa-download fa-fw"></i> H30児童用マニュアル</a></div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'relation'])}}"><i class="fa fa-download fa-fw"></i> H30チャレンジ補充問題関連付け</a></div>
            </div>
        </div>
        <!-- end col-3 -->
    @endif

    </div>
    <!-- end row -->

    @if ($roler->isPrefectureUser())

    <!-- begin row -->
    <div class="row menu-box">
        <!-- begin col-3 -->
        <div class="col-md-3 col-md-offset-3 col-sm-6">
            <div class="widget widget-stats bg-aqua">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-book fa-fw"></i></div>
                <div class="stats-title">マニュアルダウンロード</div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'school'])}}"><i class="fa fa-download fa-fw"></i> H30学校用マニュアル</a></div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'challengeuser'])}}"><i class="fa fa-download fa-fw"></i> H30児童用マニュアル</a></div>
                <div class="stats-desc"><a href="{{route('manualdownload', ['type'=>'relation'])}}"><i class="fa fa-download fa-fw"></i> H30チャレンジ補充問題関連付け</a></div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->

    @endif

@endsection
