@extends('home')

@push('css')
<link href="/css/common.css" rel="stylesheet" />
<link href="/css/sawarabigothic.css" rel="stylesheet" />
@endpush

@section('pageTitle', '子ども一人ひとりの学びづくり支援システム')
@section('bodyClass', 'challenge challengehome')

@section('sidebar')
@stop

@section('content')

    <!-- begin row -->
    <div  class="row">
        <div id = "index">
            <main>
                <h1><img src="/assets/img/system.png" alt="子ども一人ひとりの学びづくり支援システム"></h1>
                <div id="indexBtn">

                    <a id="jpBtn" href="{{ route('mapsheet',['curriculumId' => 1]) }}"><img src="/assets/img/jp.png" alt="国語"></a>
                    <a id="mathBtn" href="{{ route('mapsheet',['curriculumId' => 2]) }}"><img src="/assets/img/math.png" alt="算数"></a>
                    <!-- end col-3 -->

                </div>
            </main>

        </div>
    </div>
    <!-- end row -->

@endsection
