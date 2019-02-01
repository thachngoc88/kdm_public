@extends('layout')

@push('css')
<link href="/css/common.css" rel="stylesheet" />
<link href="/css/mapsheet.css" rel="stylesheet" />
<link href="/css/sawarabigothic.css" rel="stylesheet" />
@endpush

@section('sidebar')
@stop

@section('pageTitle', "やったねシート")
@if($curriculum->id == 1)
    @section('bodyClass', "challenge mapsheet jp")
@else
    @section('bodyClass', "challenge mapsheet math")
@endif

@section('content')

    <main>
        <div id="mapsheet-header">
            <div id="headerLeft">
                <img id="title" src="/assets/img/mapsheet/logo.png" alt="やったねシート">
                @if($curriculum->id == 1)
                    <img src="/assets/img/mapsheet/logo_jp.png" alt="国語">
                @else
                    <img src="/assets/img/mapsheet/logo_math.png" alt="算数">
                @endif
                <img src="/assets/img/mapsheet/system.png" alt="子ども一人ひとりの学びづくり支援システム">
            </div>
            <div id="headerRight">
                <div id="message">
                     <span>
                        @if(isset($message))
                             {{$message->text}}
                         @else
                             こんにちは！
                         @endif
                     </span>
                </div>
                <div id="character">
                    <img src="/assets/img/mapsheet/cat_study.png" alt="">
                </div>
                <div id="headerBtn">
                    @if($curriculum->id == 1)
                        <a href="{{route('mapsheet',['curriculumId' => 2]) }}" id="subject">
                            <span id="subjectIcon"><img src="/assets/img/mapsheet/icon_math.png" alt=""></span>
                            <span id="subjectText">算数へ</span>
                        </a>
                    @else
                        <a href="{{route('mapsheet',['curriculumId' => 1]) }}" id="subject">
                            <span id="subjectIcon"><img src="/assets/img/mapsheet/icon_jp.png" alt=""></span>
                            <span id="subjectText">国語へ</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>


        @foreach($curriculum->units as $unit)
            <a href="{{ route("unit", ['id'=>$unit->id])}}" data-unit-id="{{$unit->id}}" class="section
                    @if($stamps[$unit->id] === 0)
                    default
                    @elseif($stamps[$unit->id] === 1)
                    failed
                    @elseif($stamps[$unit->id] === 2 || $stamps[$unit->id] === 3)
                    passed
                    @endif">
                <h2>
                <span class="no">
                    {{$unit->number}}
                </span>
                <span class="title">{!!preg_replace('@^(.+?)(〜)@', '$1<br>$2', $unit->name)!!}</span>
            </h2>
            <ul>
                @foreach($unit->workbooks as $workbook)
                    <?php
                        $isExist=App\Services\Utils::checkExistBothFileDownloadInWorkbook($workbook, true);
                    ?>
                @unless ($loop->first)
                    @if($isExist)
                        <li class="{{$unit->number}}
                        @if($marks[$workbook->id] === 0)
                            default
                        @elseif($marks[$workbook->id] === 1)
                            failed
                        @elseif($marks[$workbook->id] === 2 || $marks[$workbook->id] === 3)
                            passed
                        @endif
                            disables
                        ">{{$unit->number}}-{{$workbook->number}} {{$workbook->title}}</li>
                    @else
                        <li class="{{$unit->number}} disabled">{{$workbook->title}}</li>
                    @endif
                @endunless
                @endforeach
            </ul>
        </a>
       <!-- <section data-unit-id="{{$unit->id}}" class="
                    @if($stamps[$unit->id] === 0)
            default
@elseif($stamps[$unit->id] === 1)
            failed
@elseif($stamps[$unit->id] === 2 || $stamps[$unit->id] === 3)
            passed
@endif
                ">
        <h2>

            <span  <span @if($curriculum->id == 2)
            class="no">
@elseif($curriculum->id == 1)
            class="noMath">
@endif
            {{$unit->number}}
                </span>
                <span class="title">{!!preg_replace('@^(.+?)(〜)@', '$1<br>$2', $unit->name)!!}</span>
            </h2>
            <ul>
                @foreach($unit->workbooks as $workbook)
                @unless ($loop->first)
                <li class="{{$unit->number}}
                    @if($marks[$workbook->id] === 0)
                    default
                    @elseif($marks[$workbook->id] === 1)
                    failed
                    @elseif($marks[$workbook->id] === 2 || $marks[$workbook->id] === 3)
                    passed
                    @endif
                        ">{{$workbook->title}}</li>
                    @endunless
            @endforeach
                </ul>
            </section>-->
        @endforeach
        <section class="link">
            <a href="http://www.pref.kanagawa.jp/cnt/f417579/p472981.html" target="_blank" id="backBtn">県の課題解決教材のトップページへ</a>
        </section>
    </main>

@endsection

