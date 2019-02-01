@extends('auth.layout')

@section('pageTitle', '子ども一人ひとりの学びづくり ログイン')

@section('content')

    <!-- begin login -->
    <div class="login login-with-news-feed">

        <!-- begin news-feed -->
        <div class="news-feed">
            <div class="news-image">
                <img src="/assets/img/login-bg/image.png" data-id="login-cover-image" alt="" />
            </div>
        </div>
        <!-- end news-feed -->
        <!-- begin right-content -->
        <div class="right-content">
            <!-- begin login-header -->
            <div class="login-header">
                <div class="brand">
                    <img src="/assets/img/title.png" alt="子ども一人ひとりの学びづくり支援システム" />
                    <small>神奈川県 教育委員会</small>
                </div>
                <div class="icon">
                    <i class="fa fa-sign-in"></i>
                </div>
            </div>
            <!-- end login-header -->
            <!-- begin login-content -->
            <div class="login-content">
                <form class="margin-bottom-0" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group m-b-15{{ $errors->has('login_id') ? ' has-error' : '' }}">
                        <label class="hidden" for="login-login-id">IDを入力</label>
                        <input id="login-login-id" type="text" class="form-control input-lg" placeholder="ID" name="login_id" value="{{ old('login_id') }}" title="IDを入力" required />
                        @if ($errors->has('login_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('login_id') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group m-b-15{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="hidden" for="login-password">パスワードを入力</label>
                        <input id="login-password" type="password" class="form-control input-lg" placeholder="パスワード" name="password" value="{{ old('password') }}" title="パスワードを入力" required />
                        @if ($errors->has('password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    {{--<div class="checkbox m-b-30">--}}
                        {{--<label>--}}
                            {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> このデバイスに覚えさせる--}}
                        {{--</label>--}}
                    {{--</div>--}}
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">ログイン</button>
                    </div>
                </form>
                {{--@if (session('extoken') && session('extoken') == '1' && request()->cookie('isLogout') != '1')--}}
                {{--<div style="margin-top: 50px; font-size: 15px;">--}}
                    {{--You have not been on the page for more than {{session('lifetime_ss')}} minutes, please login again!--}}
                {{--</div>--}}
                {{--@endif--}}
                <?php
                if(request()->cookie('isLogout') == '1')
                Cookie::queue(Cookie::forget('isLogout'));
                 ?>
            </div>
            <!-- end login-content -->
        </div>
    <!-- end right-container -->

    </div>

<!-- end login -->
    <!-- end login -->





@endsection
