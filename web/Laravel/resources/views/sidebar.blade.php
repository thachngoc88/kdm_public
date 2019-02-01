
    <!-- begin #sidebar -->
    <div id="sidebar" class="sidebar">
        <!-- begin sidebar scrollbar -->
        <div data-scrollbar="true" data-height="100%">
            <!-- begin sidebar user -->
            <ul class="nav">
                <li class="nav-profile">
                    <div class="info">
                        <i class="fa fa-user m-r-15"></i>&nbsp;
                        @inject('roler', 'App\Services\Roler')
                        {{$roler->getRoleName()}}
                    </div>
                </li>
            </ul>
            <!-- end sidebar user -->
            <!-- begin sidebar nav -->
            <ul class="nav">
                @inject('roler', 'App\Services\Roler')
                <li>
                    <a href="{{ route('home') }}"><i class="fa fa-laptop"></i> <span>ダッシュボード</span></a>
                </li>
                <li>
                    <a href="{{ route('aggregation') }}"><i class="fa fa-file-excel-o"></i> <span>集計リスト</span></a>
                </li>
                <li>
                    <a href="{{ route('individual') }}"><i class="fa fa-id-badge"></i> <span>個人別リスト</span></a>
                </li>
                @if($roler->isPrefectureUser())
                <li>
                    <a href="{{ route('setting') }}"><i class="fa fa-cog"></i> <span>県教委担当者</span></a>
                </li>
                @endif
                <li>
                    <a href="{{ route('home.index', ['action'=>'challenge']) }}"><i class="fa fa-newspaper-o"></i> <span>問題・解答（科目選択）</span></a>
                </li>
                <!-- begin sidebar minify button -->
                <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
                <!-- end sidebar minify button -->
            </ul>
            <!-- end sidebar nav -->
        </div>
        <!-- end sidebar scrollbar -->
    </div>
    <div class="sidebar-bg"></div>
    <!-- end #sidebar -->
