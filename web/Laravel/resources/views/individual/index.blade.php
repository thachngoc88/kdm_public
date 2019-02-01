@extends('layout')

@section('pageTitle', '個人別リスト')
@section('bodyClass', 'admin individual')

@section('breadcrumb')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('home.index', ['action'=>'admin']) }}">ダッシュボード</a></li>
        <li class="active">個人別リスト</li>
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
                    <div class="panel-heading-btn"></div>
                    <h4 class="panel-title">科目</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="subject-finder">

                        <div class="form-group">
                            <label class="col-md-4 control-label">学年 / 教科</label>
                            <div class="col-md-8">
                                <select class="default-select2 form-control filter" name="curri_id" id="curri_select" value="{{$curriculumId}}">
                                    @foreach($grades as $grade)
                                        @foreach($grade->curriculums as $curriculum)
                                        <option value="{{$curriculum->id}}" @if($curriculumId == $curriculum->id) selected="selected" @endif>{{$grade->number}}年 / {{$curriculum->subject->name}}</option>
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

        @include('classfiltering.selectors')

    </div>
    <!-- end row -->

<!-- begin col-12 -->
<div class="col-md-12">
        {!!$dataTable->table([], true)!!}
</div>


    @endsection


    @push('scripts')
    <script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
    @endpush


    @section('extraScript')
        <script>
            var csrfToken = "{{ csrf_token() }}";
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
                    url: "{{route('individual.footer')}}",
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
                dt = $('#dataTableBuilder')
                    .on('init', function (e) {
                        $('.dataTables_scrollFoot .dataTable').append("<tfoot style=''></tfoot>");
                    }).on('draw.dt', function (e) {
                        console.log('on draw.dt');
                        updateHeader();
                        updateFooter();
                    })
                    .dataTable();

                var onChangeClassFiltering = function(){
                    //updateFooter();
                    dt.api().draw();
                };
                @include('classfiltering.script')

                $('#curri_select').change(function () {
                    var curriculumId = $(this).val();
                    console.log('curriculumId:::' + curriculumId);
                    window.location.href = '{{route('individual')}}/' + curriculumId + '';
                });
                var path = location.pathname;
                var arrpath = path.split("/");
                var idCurr = arrpath[2];
                $('#curri_select').val(idCurr);
        });

        var workbookHash = {!! $workbookHash !!};
    </script>
@endsection