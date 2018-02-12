<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{--<script src="{{asset('js/app.js')}}"></script>--}}
    {{--<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>--}}
    {{--<script src="https://cdn.bootcss.com/vue/2.5.13/vue.min.js"></script>--}}
    <title>成绩查询</title>
</head>
<body>
<div class="container-fluid" id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>{!! $data['title'] !!}</h5>
                    平均绩点：{{$data['avg_grade_point']}}
                </div>
                <div class="card-body">
                    <div class="card-columns">
                        {{--<div class="card" v-for="course in courses">--}}
                            {{--<div class="card-header">@{{course.name}}</div>--}}
                            {{--<div class="card-body">--}}
                                {{--<!-- <h5 class="card-title">@{{course.point}}</h5>--}}
                                            {{--<p class="card-text">@{{course.credit}}</p> -->--}}
                                {{--<table class="table">--}}
                                    {{--<tr>--}}
                                        {{--<td colspan="2" class="text-center">成绩：<span class="font-weight-bold">@{{course.grade}}</span>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>绩点：@{{course.grade_point.toFixed(2)}}</td>--}}
                                        {{--<td>学分：@{{course.credit.toFixed(2)}}</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>平时成绩：@{{course.ordinary_perf}}</td>--}}
                                        {{--<td>期末成绩：@{{course.final_perf}}</td>--}}
                                    {{--</tr>--}}
                                {{--</table>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        @foreach( $data['courses'] as $course)
                        <div class="card" v-for="course in courses">
                            <div class="card-header">{{$course['name']}}</div>
                            <div class="card-body">
                                <!-- <h5 class="card-title">@{{course.point}}</h5>
                                            <p class="card-text">@{{course.credit}}</p> -->
                                <table class="table">
                                    <tr>
                                        <td colspan="2" class="text-center">成绩：<span class="font-weight-bold">{{$course['grade']}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>绩点：{{$course['grade_point']}}</td>
                                        <td>学分：{{$course['credit']}}</td>
                                    </tr>
                                    <tr>
                                        <td>平时成绩：{{$course['ordinary_perf']}}</td>
                                        <td>期末成绩：{{$course['final_perf']}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>