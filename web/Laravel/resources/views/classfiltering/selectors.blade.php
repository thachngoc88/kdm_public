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

                <div class="form-group">
                    <label class="col-md-4 control-label">クラス</label>
                    <div class="col-md-8">
                        <select class="default-select2 form-control filter" name="class_id" id="class-select" disabled>
                            <option value="all" selected>全てのクラス</option>
                            {{--@foreach($prefectures as $pre)--}}
                                {{--@foreach($pre->cities as $city)--}}
                                    {{--@foreach($city->schools as $school)--}}
                                        {{--@foreach($school->classes as $class)--}}
                                            {{--<option value="{{$class->id}}">{{$class->name}}-{{$class->grade->number}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endforeach--}}
                                {{--@endforeach--}}
                            {{--@endforeach--}}
                        </select>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- end panel -->
</div>
<!-- end col-6 -->