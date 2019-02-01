
    <!-- begin #header -->
    <div id="header" class="header navbar navbar-default">
        <!-- begin container-fluid -->
        <div class="container-fluid">
            <!-- begin mobile sidebar expand / collapse button -->
            <div class="navbar-header">
                {{--<a href="javascript:;" class="navbar-brand"><span class="navbar-logo"></span>子ども一人ひとりの学びづくり</a>--}}
                <a href="{{ route('home') }}" class="navbar-brand"><img src="/assets/img/title.png" alt="子ども一人ひとりの学びづくり" /></a>
                {{--<button type="button" class="navbar-toggle" data-click="sidebar-toggled">--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                {{--</button>--}}
            </div>
            <!-- end mobile sidebar expand / collapse button -->

            <!-- begin header navigation right -->
            <ul class="nav navbar-nav navbar-right">
                @inject('roler', 'App\Services\Roler')
                <li class="dropdown navbar-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        <span class=""><i class="fa fa-user m-r-5"></i>  {{ $roler->getRoleName() . ' : ' . Auth::user()->login_id }}
                            @if ($roler->isCityUser())
                                {{ ' （' . $roler->getRole()->city->name . '）' }}
                            @elseif ($roler->isSchoolUser())
                                {{ ' （' . $roler->getRole()->school->name . '）' }}
                            @elseif ($roler->isChallengeUser())
                                {{ ' （' . $roler->getRole()->klass->school->name . ' ' . $roler->getRole()->klass->grade->number . '-' . $roler->getRole()->klass->name . '）' }}
                            @endif
                            </span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInLeft">
                        <li class="arrow"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                ログアウト
                            </a>
                            <form id="logoutForm"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- end header navigation right -->
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end #header -->
