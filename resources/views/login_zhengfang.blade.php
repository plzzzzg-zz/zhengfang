<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>更新课表</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('/grade/login') }}">
                        <input type="text" hidden name="func" value="{{$func}}">
                        {{ csrf_field() }}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                        <div class="form-group{{ $errors->has('student_id') ? ' has-error' : '' }}">
                            <label for="student_id" class="col-md-4 control-label">学号</label>

                            <div class="col-md-6">
                                <input id="student_id" type="student_id" class="form-control" name="student_id" value="{{ old('student_id') }}" required autofocus>

                                @if ($errors->has('student_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('student_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="code" class="col-md-4 control-label">验证码</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input style="height: 41px;" id="code" type="code" class="form-control" name="code" required>
                                    <span class="input-group-addon"><img src="{{$captcha_path}}"></span>
                                </div>
                                @if ($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                        {{--<div class="col-md-6 col-md-offset-4">--}}
                        {{--<div class="checkbox">--}}
                        {{--<label>--}}
                        {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me--}}
                        {{--</label>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                {{--Forgot Your Password?--}}
                                {{--</a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>
{{--<form action="{{url('/courses/login')}}" method="post">--}}
{{--{{ csrf_field() }}--}}
{{--<img src="{{$captcha_path}}">--}}
{{--<input type="text" name="code">--}}
{{--<input type="submit" name="submit" value="go">--}}
{{--</form>--}}
