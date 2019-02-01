@extends('layout')

@section('pageTitle', '集計リスト')
@section('bodyClass', 'admin aggregation')

@section('breadcrumb')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('home.index', ['action'=>'admin']) }}">ダッシュボード</a></li>
        <li class="active">集計リスト</li>
    </ol>
@endsection

@section('content')

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-md-6 ui-sortable disable-draggable">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="subject-finder-panel">
                <div class="panel-heading">
                    <div class="panel-heading-btn">

                    </div>
                    <h4 class="panel-title">科目</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="subject-finder">

                        <div class="form-group">
                            <label class="col-md-4 control-label">学年 / 教科</label>
                            <div class="col-md-8">
                                <select class="default-select2 form-control filter" name="curri_id" id="curri_select">
                                    @foreach($grades as $grade)
                                        @foreach($grade->curriculums as $curriculum)
                                            <option value="{{$curriculum->id}}" @if($curriculumId == $curriculum->id) selected @endif>{{$grade->number}}年 / {{$curriculum->subject->name}}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->

        <!-- begin col-6 -->
        <div class="col-md-6">
            <!-- begin panel -->
            <div class="panel panel-inverse disable-draggable" data-sortable-id="filtering-panel">
                <div class="panel-heading">
                    <div class="panel-heading-btn"></div>
                    <h4 class="panel-title">フィルタリング</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="filtering">
                        @inject('roler', 'App\Services\Roler')


                        <div class="form-group">
                            <label class="col-md-4 control-label">市町村</label>
                            <div class="col-md-8">
                                <select class="default-select2 form-control filter" name="city_id" id="city-select">
                                    @if ($roler->isPrefectureUser()) <option value="all" @if(!$cityId) selected @endif>全ての市町村</option> @endif
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" @if($cityId == $city->id) selected @endif>{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">学校</label>
                            <div class="col-md-8">
                                <select class="default-select2 form-control filter" name="school_id" id="school-select" @if(!$schools) disabled @endif>
                                    @if ($roler->isPrefectureUser() || $roler->isCityUser()) <option value="all" @if(!$schoolId) selected @endif>全ての学校</option> @endif
                                    @if($schools)
                                        @foreach($schools as $school)
                                            <option value="{{$school->id}}" @if($schoolId == $school->id) selected @endif>{{$school->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label class="col-md-4 control-label">クラス</label>
                            <div class="col-md-8">
                                <select class="default-select2 form-control filter" name="class_id" id="class-select" @if(!$classes) disabled @endif>
                                    <option value="all" @if(!$classId) selected @endif>全てのクラス</option>
                                    {{--@if($classes)--}}
                                        {{--@foreach($classes as $class)--}}
                                            {{--<option value="{{$class->id}}" @if($classId == $class->id) selected @endif>{{$class->grade->number . "-" . $class->name}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->

    </div>
    <!-- end row -->


    <!-- begin col-12 -->
    <div class="col-md-12">
        {!! $dataTable->table([],true) !!}
    </div>
    <!-- end col-12 -->

@endsection


@push('scripts')
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
        var updateHeader = function(){
            $('.dataTables_scrollHead th:not(:has(a))').each(function() {
                var text = $(this).text();
                if(text in workbookHash){
                    var $t = $(this);
                    $t.html('<a tabindex="0" data-toggle="popover" data-trigger="focus" title="' + workbookHash[text].title + '" data-content="' + workbookHash[text].content + '">' + text + '</a>');
                    $t.find('a').popover({
                        container: 'body',
                        placement: 'top'
                    });
                }
            });
            $('.dataTables_scrollBody').on('scroll', function() {
                $(".dataTables_scrollHead").find("a[aria-describedby^='popover']").blur();
            });
        };

        var updateFooter = function(){
            var city_id = $('#city-select').val();
            var school_id = $('#school-select').val();
            var class_id = $('#class-select').val();
            var curri_id = $('#curri_select').val();

            console.log("curri_id:" + curri_id);
            $.ajax({
                type: "POST",
                url: "{{route('aggregation.footer')}}",
                data: {city_id:city_id , school_id:school_id,class_id:class_id,curri_id:curri_id},
                dataType: 'json',
                headers: {
                    'X-CSRF-Token':'{{ csrf_token() }}'
                },
                success: function(rst) {
                    $('.dataTables_scrollFoot .dataTable tfoot').html(rst.data);
                    //Fixed first column at footer
                    $('.DTFC_LeftFootWrapper .dataTable tfoot').empty();
                    $('.dataTables_scrollFoot .dataTable tfoot').find('tr').each(function () {
                        var td_copy = $(this).find('td:first-child').clone();
                        var tr_height = $(this).height();
                        var tr_new = $("<tr></tr>").height(tr_height+"px").append(td_copy);
                        $('.DTFC_LeftFootWrapper .dataTable tfoot').append(tr_new);


                    });
                    $(window).resize();
                },
                error: function (data) {
                    console.log('ERROR:' + data);
                }
            });
        };

        $(document).ready(function() {

            var onChangeClassFiltering = function(e){
//                updateFooter();
//                dt.api().draw();
                var curriculumId = $('#curri_select').val();
                var mode = '';
                var $t = $(e.target);
                var name = $t.attr('name').replace(/_id$/, '');
                var val = $t.val();
                var suffix = '';
                if(val == 'all'){
                    mode = name;
                    switch(name){
                        case 'city':
                            suffix = '';
                            break;
                        case 'school':
                            suffix = $('#city-select').val();
                            break;
                        case 'class':
                            suffix = $('#city-select').val() + '/' + $('#school-select').val();
                            break;
                    }
                }else{
                    switch(name){
                        case 'city':
                            mode = 'school';
                            suffix = $('#city-select').val();
                            break;
                        case 'school':
                            mode = 'class';
                            suffix = $('#city-select').val() + '/' + $('#school-select').val();
                            break;
                        case 'class':
//                            mode = 'class';
//                            suffix = $('#city-select').val() + '/' + $('#school-select').val();
                            break;
                    }
                }
                window.location.href = '{{route('aggregation')}}/' + curriculumId + '/' + mode + '/' + suffix;
            };
            var updateSelectors = function(){
//                $('#school-select').prop('disabled', $('#city-select').val() == 'all');
//                $('#class-select').prop('disabled', $('#school-select').val() == 'all');
            };

            $('#city-select').change(function(e){
                onChangeClassFiltering(e);
                updateSelectors();
            });

            $('#school-select').change(function(e){
                onChangeClassFiltering(e);
                updateSelectors();
            });

            $('#class-select').change(function(e){
                onChangeClassFiltering(e);
                updateSelectors();
            });

            $('#curri_select').change(function () {
                var curriculumId = $(this).val();
                window.location.href = '{{route('aggregation')}}/' + curriculumId + '';
            });
            var path = location.pathname;
            var arrpath = path.split("/");
            var idCurr = arrpath[2];
            $('#curri_select').val(idCurr);


            $('#filtering').get(0).reset();
            updateSelectors();

            dt = $('#dataTableBuilder')
                .on('init', function (e) {
                })
                .on('draw.dt', function (e) {
                    if($('.dataTables_scrollFoot tfoot').size() == 0){
                        $('.dataTables_scrollFoot .dataTable').append("<tfoot style=''></tfoot>");
                    }
                    updateHeader();
                    updateFooter();
                })
                .dataTable();
        });

        var workbookHash = {!! $workbookHash !!};

    </script>

@endsection