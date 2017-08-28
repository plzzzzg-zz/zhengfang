<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {{--<script type="text/javaScript"  src="{{ asset('js/vue.min.js') }}"></script>--}}
    <script src="https://cdn.bootcss.com/vue/2.4.2/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.16.2/axios.min.js"></script>
{{--<script type="text/javaScript"  src="{{ asset('js/axios.js') }}"></script>--}}
{{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/simple-sidebar.css')}}">
    <style>
        td {
            vertical-align: middle !important;
            height: 70px;
            text-align: center;
            font-size: 10px;
            border-top: 0 !important;
        }

        .hasClass {
            background-color: #475669;
            color: white;
            border: 1px solid #475669;
        }

        thead tbody {
            display: block;
        }

        .container {
            overflow-y: auto;
            height: 100%;
            padding: 0;
        }

        td:first-child, th:first-child {
            width: 33px !important;
            background-color: #324057;
            color: white;
        }

        table {
            border: 0;
            /*table-layout: fixed;*/
        }

        td, th {
            text-align: center !important;
            width: 48px;
            padding: 8px 4px !important;
            word-break: break-all;
            word-wrap: break-word;
            border: 1px dashed #324057;
        }

        th {
            background-color: #324057;
            color: white;
        }

        tr:nth-child(2) {
            border-left: 0;
        }

        tr:last-child {
            border-right: 0 !important;
        }

        ul {
            list-style: none;
        }

        .table > thead > tr > th {
            border-bottom: 2px solid #324057;
        }

        .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
            border: 1px #324057;
        }
    </style>
<body>

<div id="wrapper" class="">

    <!-- Sidebar -->
    <div id="sidebar-wrapper" style="right: 0;color: white;">
        <ul class="sidebar-nav">
            <li class="sidebar-brand" @click="showWeeks">
                当前：@{{ week }}
                <span class="glyphicon glyphicon-triangle-bottom"></span>
            </li>
            <li class="divider"></li>
            <li id="weeks" hidden="hidden">
                <ul>
                    <li @click="changeWeek(1)">第1周</li>
                    <li @click="changeWeek(2)">第2周</li>
                    <li @click="changeWeek(3)">第3周</li>
                    <li @click="changeWeek(4)">第4周</li>
                    <li @click="changeWeek(5)">第5周</li>
                    <li @click="changeWeek(6)">第6周</li>
                    <li @click="changeWeek(7)">第7周</li>
                    <li @click="changeWeek(8)">第8周</li>
                    <li @click="changeWeek(9)">第9周</li>
                    <li @click="changeWeek(10)">第10周</li>
                    <li @click="changeWeek(11)">第11周</li>
                    <li @click="changeWeek(12)">第12周</li>
                    <li @click="changeWeek(13)">第13周</li>
                    <li @click="changeWeek(14)">第14周</li>
                    <li @click="changeWeek(15)">第15周</li>
                    <li @click="changeWeek(16)">第16周</li>
                    <li @click="changeWeek(17)">第17周</li>
                    <li @click="changeWeek(18)">第18周</li>
                    <li @click="changeWeek(19)">第19周</li>
                    <li @click="changeWeek(20)">第20周</li>
                    <li @click="changeWeek(21)">第21周</li>
                </ul>
            </li>
            <li>
                <a href="{{url('/courses/login')}}">更新课表</a>
            </li>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 0">
        <div class="container">
            {{--{{$courses}}--}}
            <table class="table table-responsive"
                   style="width:100%;position:fixed;top: 0; background-color:white;border-color: #324057;border-right: 1px solid #324057;">
                <thead class="thead-inverse text-center">
                <tr>
                    <th>
                        {{--<a href="#menu-toggle" class="" id="menu-toggle" style=""><span class="glyphicon glyphicon-menu-hamburgerh" ></span></a></th>--}}
                        <button @click="t" id="menu-toggle" type="button" class="btn btn-xs btn-default"
                                aria-label="Left Align">
                            <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                        </button>
                    {{--<button id="menu-toggle"  type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">--}}
                    {{--<span class="sr-only">Toggle navigation</span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--<span class="icon-bar"></span>--}}
                    {{--</button></th>--}}
                    <th>MON</th>
                    <th>TUES</th>
                    <th>WED</th>
                    <th>THUR</th>
                    <th>FRI</th>
                    <th>SAT</th>
                    <th>SUN</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="container" @click="hideSideBar" id="c">
            <table class="table table-responsive" style="margin-top: 37px;margin-bottom: 0!important; border: 0;">
                {{--@{{ thisWeek }}--}}
                {{--@{{ courses }}--}}
                <tbody class="text-center clearfix" style="overflow-y: auto">
                {{--row1--}}
                <tr>
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">1
                    </td>
                    <td :rowspan="courses['101']['duration']" v-if="courses['101']['hasClass']"
                        v-bind:class="{hasClass:courses['101']['hasClass']}">
                        @{{ courses['101']['name'] }}<br>
                        @{{ courses['101']['place'] }}<br>
                        @{{ courses['101']['week_begin'] }}-@{{ courses['101']['week_end'] }}周
                        <template v-if="courses['101']['week_odd']==1">|单</template>
                        <template v-if="courses['101']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['201']['duration']" v-if="courses['201']['hasClass']"
                        v-bind:class="{hasClass:courses['201']['hasClass']}">
                        @{{ courses['201']['name'] }}<br>
                        @{{ courses['201']['place'] }}<br>
                        @{{ courses['201']['week_begin'] }}-@{{ courses['201']['week_end'] }}周
                        <template v-if="courses['201']['week_odd']==1">|单</template>
                        <template v-if="courses['201']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['301']['duration']" v-if="courses['301']['hasClass']"
                        v-bind:class="{hasClass:courses['301']['hasClass']}">
                        @{{ courses['301']['name'] }}<br>
                        @{{ courses['301']['place'] }}<br>
                        @{{ courses['301']['week_begin'] }}-@{{ courses['301']['week_end'] }}周
                        <template v-if="courses['301']['week_odd']==1">|单</template>
                        <template v-if="courses['301']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['401']['duration']" v-if="courses['401']['hasClass']"
                        v-bind:class="{hasClass:courses['401']['hasClass']}">
                        @{{ courses['401']['name'] }}<br>
                        @{{ courses['401']['place'] }}<br>
                        @{{ courses['401']['week_begin'] }}-@{{ courses['401']['week_end'] }}周
                        <template v-if="courses['401']['week_odd']==1">|单</template>
                        <template v-if="courses['401']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['501']['duration']" v-if="courses['501']['hasClass']"
                        v-bind:class="{hasClass:courses['501']['hasClass']}">
                        @{{ courses['501']['name'] }}<br>
                        @{{ courses['501']['place'] }}<br>
                        @{{ courses['501']['week_begin'] }}-@{{ courses['501']['week_end'] }}周
                        <template v-if="courses['501']['week_odd']==1">|单</template>
                        <template v-if="courses['501']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['601']['duration']" v-if="courses['601']['hasClass']"
                        v-bind:class="{hasClass:courses['601']['hasClass']}">
                        @{{ courses['601']['name'] }}<br>
                        @{{ courses['601']['place'] }}<br>
                        @{{ courses['601']['week_begin'] }}-@{{ courses['601']['week_end'] }}周
                        <template v-if="courses['601']['week_odd']==1">|单</template>
                        <template v-if="courses['601']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['701']['duration']" v-if="courses['701']['hasClass']" style="border-right: 0;"
                        v-bind:class="{hasClass:courses['701']['hasClass']}">
                        @{{ courses['701']['name'] }}<br>
                        @{{ courses['701']['place'] }}<br>
                        @{{ courses['701']['week_begin'] }}-@{{ courses['701']['week_end'] }}周
                        <template v-if="courses['701']['week_odd']==1">|单</template>
                        <template v-if="courses['701']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr>
                    <td scope="row">2</td>
                    <td v-if="!courses['101']['hasClass']"></td>
                    <td v-if="!courses['201']['hasClass']"></td>
                    <td v-if="!courses['301']['hasClass']"></td>
                    <td v-if="!courses['401']['hasClass']"></td>
                    <td v-if="!courses['501']['hasClass']"></td>
                    <td v-if="!courses['601']['hasClass']"></td>
                    <td v-if="!courses['701']['hasClass']" style="border-right: 0;"></td>
                </tr>
                {{--row3--}}
                <tr>
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">3
                    </td>
                    <td :rowspan="courses['103']['duration']" v-if="courses['103']['hasClass']"
                        v-bind:class="{hasClass:courses['103']['hasClass']}">
                        @{{ courses['103']['name'] }}<br>
                        @{{ courses['103']['place'] }}<br>
                        @{{ courses['103']['week_begin'] }}-@{{ courses['103']['week_end'] }}周
                        <template v-if="courses['103']['week_odd']==1">|单</template>
                        <template v-if="courses['103']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['203']['duration']" v-if="courses['203']['hasClass']"
                        v-bind:class="{hasClass:courses['203']['hasClass']}">
                        @{{ courses['203']['name'] }}<br>
                        @{{ courses['203']['place'] }}<br>
                        @{{ courses['203']['week_begin'] }}-@{{ courses['203']['week_end'] }}周
                        <template v-if="courses['203']['week_odd']==1">|单</template>
                        <template v-if="courses['203']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['303']['duration']" v-if="courses['303']['hasClass']"
                        v-bind:class="{hasClass:courses['303']['hasClass']}">
                        @{{ courses['303']['name'] }}<br>
                        @{{ courses['303']['place'] }}<br>
                        @{{ courses['303']['week_begin'] }}-@{{ courses['303']['week_end'] }}周
                        <template v-if="courses['303']['week_odd']==1">|单</template>
                        <template v-if="courses['303']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['403']['duration']" v-if="courses['403']['hasClass']"
                        v-bind:class="{hasClass:courses['403']['hasClass']}">
                        @{{ courses['403']['name'] }}<br>
                        @{{ courses['403']['place'] }}<br>
                        @{{ courses['403']['week_begin'] }}-@{{ courses['403']['week_end'] }}周
                        <template v-if="courses['403']['week_odd']==1">|单</template>
                        <template v-if="courses['403']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['503']['duration']" v-if="courses['503']['hasClass']"
                        v-bind:class="{hasClass:courses['503']['hasClass']}">
                        @{{ courses['503']['name'] }}<br>
                        @{{ courses['503']['place'] }}<br>
                        @{{ courses['503']['week_begin'] }}-@{{ courses['503']['week_end'] }}周
                        <template v-if="courses['503']['week_odd']==1">|单</template>
                        <template v-if="courses['503']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['603']['duration']" v-if="courses['603']['hasClass']"
                        v-bind:class="{hasClass:courses['603']['hasClass']}">
                        @{{ courses['603']['name'] }}<br>
                        @{{ courses['603']['place'] }}<br>
                        @{{ courses['603']['week_begin'] }}-@{{ courses['603']['week_end'] }}周
                        <template v-if="courses['603']['week_odd']==1">|单</template>
                        <template v-if="courses['603']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['703']['duration']" style="border-right: 0;" v-if="courses['703']['hasClass']"
                        v-bind:class="{hasClass:courses['703']['hasClass']}">
                        @{{ courses['703']['name'] }}<br>
                        @{{ courses['703']['place'] }}<br>
                        @{{ courses['703']['week_begin'] }}-@{{ courses['703']['week_end'] }}周
                        <template v-if="courses['703']['week_odd']==1">|单</template>
                        <template v-if="courses['703']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr>
                    <td scope="row">4</td>
                    <td v-if="!courses['103']['hasClass']"></td>
                    <td v-if="!courses['203']['hasClass']"></td>
                    <td v-if="!courses['303']['hasClass']"></td>
                    <td v-if="!courses['403']['hasClass']"></td>
                    <td v-if="!courses['503']['hasClass']"></td>
                    <td v-if="!courses['603']['hasClass']"></td>
                    <td v-if="!courses['703']['hasClass']" style="border-right: 0;"></td>
                </tr>
                {{--row5--}}
                <tr style="border-top-color: #133d55;">
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">5
                    </td>
                    <td :rowspan="courses['105']['duration']" v-if="courses['105']['hasClass']"
                        v-bind:class="{hasClass:courses['105']['hasClass']}">
                        @{{ courses['105']['name'] }}<br>
                        @{{ courses['105']['place'] }}<br>
                        @{{ courses['105']['week_begin'] }}-@{{ courses['105']['week_end'] }}周
                        <template v-if="courses['105']['week_odd']==1">|单</template>
                        <template v-if="courses['105']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['205']['duration']" v-if="courses['105']['hasClass']"
                        v-bind:class="{hasClass:courses['205']['hasClass']}">
                        @{{ courses['205']['name'] }}<br>
                        @{{ courses['205']['place'] }}<br>
                        @{{ courses['205']['week_begin'] }}-@{{ courses['205']['week_end'] }}周
                        <template v-if="courses['205']['week_odd']==1">|单</template>
                        <template v-if="courses['205']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['305']['duration']" v-if="courses['305']['hasClass']"
                        v-bind:class="{hasClass:courses['305']['hasClass']}">
                        @{{ courses['305']['name'] }}<br>
                        @{{ courses['305']['place'] }}<br>
                        @{{ courses['305']['week_begin'] }}-@{{ courses['305']['week_end'] }}周
                        <template v-if="courses['305']['week_odd']==1">|单</template>
                        <template v-if="courses['305']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['405']['duration']" v-if="courses['405']['hasClass']"
                        v-bind:class="{hasClass:courses['405']['hasClass']}">
                        @{{ courses['405']['name'] }}<br>
                        @{{ courses['405']['place'] }}<br>
                        @{{ courses['405']['week_begin'] }}-@{{ courses['405']['week_end'] }}周
                        <template v-if="courses['405']['week_odd']==1">|单</template>
                        <template v-if="courses['405']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['505']['duration']" v-if="courses['505']['hasClass']"
                        v-bind:class="{hasClass:courses['505']['hasClass']}">
                        @{{ courses['505']['name'] }}<br>
                        @{{ courses['505']['place'] }}<br>
                        @{{ courses['505']['week_begin'] }}-@{{ courses['505']['week_end'] }}周
                        <template v-if="courses['505']['week_odd']==1">|单</template>
                        <template v-if="courses['505']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['605']['duration']" v-if="courses['605']['hasClass']"
                        v-bind:class="{hasClass:courses['605']['hasClass']}">
                        @{{ courses['605']['name'] }}<br>
                        @{{ courses['605']['place'] }}<br>
                        @{{ courses['605']['week_begin'] }}-@{{ courses['605']['week_end'] }}周
                        <template v-if="courses['605']['week_odd']==1">|单</template>
                        <template v-if="courses['605']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['705']['duration']" v-if="courses['705']['hasClass']" style="border-right: 0;"
                        v-bind:class="{hasClass:courses['705']['hasClass']}">
                        @{{ courses['705']['name'] }}<br>
                        @{{ courses['705']['place'] }}<br>
                        @{{ courses['705']['week_begin'] }}-@{{ courses['705']['week_end'] }}周
                        <template v-if="courses['705']['week_odd']==1">|单</template>
                        <template v-if="courses['705']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr style="border-bottom-color: #133d55;">
                    <td scope="row">6</td>
                    <td v-if="!courses['105']['hasClass']"></td>
                    <td v-if="!courses['205']['hasClass']"></td>
                    <td v-if="!courses['305']['hasClass']"></td>
                    <td v-if="!courses['405']['hasClass']"></td>
                    <td v-if="!courses['505']['hasClass']"></td>
                    <td v-if="!courses['605']['hasClass']"></td>
                    <td v-if="!courses['705']['hasClass']" style="border-right: 0;"></td>
                </tr>
                {{--row7--}}
                <tr>
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">7
                    </td>
                    <td :rowspan="courses['107']['duration']" v-if="courses['107']['hasClass']"
                        v-bind:class="{hasClass:courses['107']['hasClass']}">
                        @{{ courses['107']['name'] }}<br>
                        @{{ courses['107']['place'] }}<br>
                        @{{ courses['107']['week_begin'] }}-@{{ courses['107']['week_end'] }}周
                        <template v-if="courses['107']['week_odd']==1">|单</template>
                        <template v-if="courses['107']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['207']['duration']" v-if="courses['207']['hasClass']"
                        v-bind:class="{hasClass:courses['207']['hasClass']}">
                        @{{ courses['207']['name'] }}<br>
                        @{{ courses['207']['place'] }}<br>
                        @{{ courses['207']['week_begin'] }}-@{{ courses['207']['week_end'] }}周
                        <template v-if="courses['207']['week_odd']==1">|单</template>
                        <template v-if="courses['207']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['307']['duration']" v-if="courses['307']['hasClass']"
                        v-bind:class="{hasClass:courses['307']['hasClass']}">
                        @{{ courses['307']['name'] }}<br>
                        @{{ courses['307']['place'] }}<br>
                        @{{ courses['307']['week_begin'] }}-@{{ courses['307']['week_end'] }}周
                        <template v-if="courses['307']['week_odd']==1">|单</template>
                        <template v-if="courses['307']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['407']['duration']" v-if="courses['407']['hasClass']"
                        v-bind:class="{hasClass:courses['407']['hasClass']}">
                        @{{ courses['407']['name'] }}<br>
                        @{{ courses['407']['place'] }}<br>
                        @{{ courses['407']['week_begin'] }}-@{{ courses['407']['week_end'] }}周
                        <template v-if="courses['407']['week_odd']==1">|单</template>
                        <template v-if="courses['407']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['507']['duration']" v-if="courses['507']['hasClass']"
                        v-bind:class="{hasClass:courses['507']['hasClass']}">
                        @{{ courses['507']['name'] }}<br>
                        @{{ courses['507']['place'] }}<br>
                        @{{ courses['507']['week_begin'] }}-@{{ courses['507']['week_end'] }}周
                        <template v-if="courses['507']['week_odd']==1">|单</template>
                        <template v-if="courses['507']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['607']['duration']" v-if="courses['607']['hasClass']"
                        v-bind:class="{hasClass:courses['607']['hasClass']}">
                        @{{ courses['607']['name'] }}<br>
                        @{{ courses['607']['place'] }}<br>
                        @{{ courses['607']['week_begin'] }}-@{{ courses['607']['week_end'] }}周
                        <template v-if="courses['607']['week_odd']==1">|单</template>
                        <template v-if="courses['607']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['707']['duration']" style="border-right: 0;" v-if="courses['707']['hasClass']"
                        v-bind:class="{hasClass:courses['707']['hasClass']}">
                        @{{ courses['707']['name'] }}<br>
                        @{{ courses['707']['place'] }}<br>
                        @{{ courses['707']['week_begin'] }}-@{{ courses['707']['week_end'] }}周
                        <template v-if="courses['707']['week_odd']==1">|单</template>
                        <template v-if="courses['707']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr>
                    <td scope="row">8</td>
                    <td v-if="!courses['107']['hasClass']"></td>
                    <td v-if="!courses['207']['hasClass']"></td>
                    <td v-if="!courses['307']['hasClass']"></td>
                    <td v-if="!courses['407']['hasClass']"></td>
                    <td v-if="!courses['507']['hasClass']"></td>
                    <td v-if="!courses['607']['hasClass']"></td>
                    <td v-if="!courses['707']['hasClass']" style="border-right: 0;"></td>
                </tr>
                {{--row9--}}
                <tr>
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">9
                    </td>
                    <td :rowspan="courses['109']['duration']" v-if="courses['109']['hasClass']"
                        v-bind:class="{hasClass:courses['109']['hasClass']}">
                        @{{ courses['109']['name'] }}<br>
                        @{{ courses['109']['place'] }}<br>
                        @{{ courses['109']['week_begin'] }}-@{{ courses['109']['week_end'] }}周
                        <template v-if="courses['109']['week_odd']==1">|单</template>
                        <template v-if="courses['109']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['209']['duration']" v-if="courses['209']['hasClass']"
                        v-bind:class="{hasClass:courses['209']['hasClass']}">
                        @{{ courses['209']['name'] }}<br>
                        @{{ courses['209']['place'] }}<br>
                        @{{ courses['209']['week_begin'] }}-@{{ courses['209']['week_end'] }}周
                        <template v-if="courses['209']['week_odd']==1">|单</template>
                        <template v-if="courses['209']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['309']['duration']" v-if="courses['309']['hasClass']"
                        v-bind:class="{hasClass:courses['309']['hasClass']}">
                        @{{ courses['309']['name'] }}<br>
                        @{{ courses['309']['place'] }}<br>
                        @{{ courses['309']['week_begin'] }}-@{{ courses['309']['week_end'] }}周
                        <template v-if="courses['309']['week_odd']==1">|单</template>
                        <template v-if="courses['309']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['409']['duration']" v-if="courses['409']['hasClass']"
                        v-bind:class="{hasClass:courses['409']['hasClass']}">
                        @{{ courses['409']['name'] }}<br>
                        @{{ courses['409']['place'] }}<br>
                        @{{ courses['409']['week_begin'] }}-@{{ courses['409']['week_end'] }}周
                        <template v-if="courses['409']['week_odd']==1">|单</template>
                        <template v-if="courses['409']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['509']['duration']" v-if="courses['509']['hasClass']"
                        v-bind:class="{hasClass:courses['509']['hasClass']}">
                        @{{ courses['509']['name'] }}<br>
                        @{{ courses['509']['place'] }}<br>
                        @{{ courses['509']['week_begin'] }}-@{{ courses['509']['week_end'] }}周
                        <template v-if="courses['509']['week_odd']==1">|单</template>
                        <template v-if="courses['509']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['609']['duration']" v-if="courses['609']['hasClass']"
                        v-bind:class="{hasClass:courses['609']['hasClass']}">
                        @{{ courses['609']['name'] }}<br>
                        @{{ courses['609']['place'] }}<br>
                        @{{ courses['609']['week_begin'] }}-@{{ courses['609']['week_end'] }}周
                        <template v-if="courses['609']['week_odd']==1">|单</template>
                        <template v-if="courses['609']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['709']['duration']" v-if="courses['709']['hasClass']" style="border-right: 0;"
                        v-bind:class="{hasClass:courses['709']['hasClass']}">
                        @{{ courses['709']['name'] }}<br>
                        @{{ courses['709']['place'] }}<br>
                        @{{ courses['709']['week_begin'] }}-@{{ courses['709']['week_end'] }}周
                        <template v-if="courses['709']['week_odd']==1">|单</template>
                        <template v-if="courses['709']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr>
                    <td scope="row">10</td>
                    <td v-if="!courses['109']['hasClass']"></td>
                    <td v-if="!courses['209']['hasClass']"></td>
                    <td v-if="!courses['309']['hasClass']"></td>
                    <td v-if="!courses['409']['hasClass']"></td>
                    <td v-if="!courses['509']['hasClass']"></td>
                    <td v-if="!courses['609']['hasClass']"></td>
                    <td v-if="!courses['709']['hasClass']" style="border-right: 0;"></td>
                </tr>
                {{--row11--}}
                <tr>
                    <td class="text-center" scope="row" style="width: 33px
;border-right:0;">11
                    </td>
                    <td :rowspan="courses['111']['duration']" v-if="courses['111']['hasClass']"
                        v-bind:class="{hasClass:courses['111']['hasClass']}">
                        @{{ courses['111']['name'] }}<br>
                        @{{ courses['111']['place'] }}<br>
                        @{{ courses['111']['week_begin'] }}-@{{ courses['111']['week_end'] }}周
                        <template v-if="courses['111']['week_odd']==1">|单</template>
                        <template v-if="courses['111']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['211']['duration']" v-if="courses['211']['hasClass']"
                        v-bind:class="{hasClass:courses['211']['hasClass']}">
                        @{{ courses['211']['name'] }}<br>
                        @{{ courses['211']['place'] }}<br>
                        @{{ courses['211']['week_begin'] }}-@{{ courses['211']['week_end'] }}周
                        <template v-if="courses['211']['week_odd']==1">|单</template>
                        <template v-if="courses['211']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['311']['duration']" v-if="courses['311']['hasClass']"
                        v-bind:class="{hasClass:courses['311']['hasClass']}">
                        @{{ courses['311']['name'] }}<br>
                        @{{ courses['311']['place'] }}<br>
                        @{{ courses['311']['week_begin'] }}-@{{ courses['311']['week_end'] }}周
                        <template v-if="courses['311']['week_odd']==1">|单</template>
                        <template v-if="courses['311']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['411']['duration']" v-if="courses['411']['hasClass']"
                        v-bind:class="{hasClass:courses['411']['hasClass']}">
                        @{{ courses['411']['name'] }}<br>
                        @{{ courses['411']['place'] }}<br>
                        @{{ courses['411']['week_begin'] }}-@{{ courses['411']['week_end'] }}周
                        <template v-if="courses['411']['week_odd']==1">|单</template>
                        <template v-if="courses['411']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['511']['duration']" v-if="courses['511']['hasClass']"
                        v-bind:class="{hasClass:courses['511']['hasClass']}">
                        @{{ courses['511']['name'] }}<br>
                        @{{ courses['511']['place'] }}<br>
                        @{{ courses['511']['week_begin'] }}-@{{ courses['511']['week_end'] }}周
                        <template v-if="courses['511']['week_odd']==1">|单</template>
                        <template v-if="courses['511']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['611']['duration']" v-if="courses['611']['hasClass']"
                        v-bind:class="{hasClass:courses['611']['hasClass']}">
                        @{{ courses['611']['name'] }}<br>
                        @{{ courses['611']['place'] }}<br>
                        @{{ courses['611']['week_begin'] }}-@{{ courses['611']['week_end'] }}周
                        <template v-if="courses['611']['week_odd']==1">|单</template>
                        <template v-if="courses['611']['week_odd']==2">|双</template>
                    </td>
                    <td v-else></td>
                    <td :rowspan="courses['711']['duration']" v-if="courses['711']['hasClass']"
                        style="border-right: 0;" v-bind:class="{hasClass:courses['711']['hasClass']}">
                        @{{ courses['711']['name'] }}<br>
                        @{{ courses['711']['place'] }}<br>
                        @{{ courses['711']['week_begin'] }}-@{{ courses['711']['week_end'] }}周
                        <template v-if="courses['711']['week_odd']==1">|单</template>
                        <template v-if="courses['711']['week_odd']==2">|双</template>
                    </td>
                    <td v-else style="border-right: 0;"></td>
                </tr>
                <tr>
                    <td scope="row">12</td>
                    <td v-if="!courses['111']['hasClass']"></td>
                    <td v-if="!courses['211']['hasClass']"></td>
                    <td v-if="!courses['311']['hasClass']"></td>
                    <td v-if="!courses['411']['hasClass']"></td>
                    <td v-if="!courses['511']['hasClass']"></td>
                    <td v-if="!courses['611']['hasClass']"></td>
                    <td v-if="!courses['711']['hasClass']"></td>
                </tr>
                <tr>
                    <td scope="row">13</td>

                    <template v-if="courses['111']['hasClass']"></template>
                    <template v-else-if="courses['111']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                    <td v-if="!courses['211']['hasClass']||courses['211']['duration']!=3"></td>
                    <template v-else></template>
                    <template v-if="courses['311']['hasClass']"></template>
                    <template v-else-if="courses['311']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                    <template v-if="courses['411']['hasClass']"></template>
                    <template v-else-if="courses['411']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                    <template v-if="courses['511']['hasClass']"></template>
                    <template v-else-if="courses['511']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                    <template v-if="courses['611']['hasClass']"></template>
                    <template v-else-if="courses['611']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                    <template v-if="courses['711']['hasClass']"></template>
                    <template v-else-if="courses['711']['duration'] === 3">&nbsp;</template>
                    <td v-else></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery first, then Bootstrap JS. -->
<script src="https://cdn.bootcss.com/jquery/3.2.0/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"
></script>
</body>
<script>

    $(document).ready(function () {
    });
    let week = {!! $week !!};
    let student_id = {!! $xh !!};
    let url = '/api/courses/' + student_id;
    let vm = new Vue({
        el: '#wrapper',
        data: {
            courses: [],
            week: week,
        },
        beforeCreate: function () {
            let self = this;
            axios.get(url)
                .then((response) => {
                    /**
                     * In option functions like data and created,
                     * vue binds this to the view-model instance for us,
                     * so we can use this.followed, but in the function inside then,
                     * this is not bound.
                     *
                     * 在 then的内部不能使用Vue的实例化的this, 因为在内部 this 没有被绑定。
                     * 解决方案：
                     * 1. let self = this;
                     * 2. Use ECS6 arrow function,箭头方法可以与父方法共享变量
                     */
                    this.courses = response.data;
                    for (let i = 100; i <= 700; i += 100) {
                        for (let j = 1; j <= 11; j += 2) {
                            let c = i + j;
                            if (typeof(self.courses[c]['name']) === "string") {
                                if (self.week >= self.courses[c]['week_begin'] &&
                                    self.courses[c]['week_end'] >= self.week) {
                                    if (self.courses[c]['week_odd'] === 0) {
                                        self.courses[c]['hasClass'] = true;
                                    } else self.courses[c]['hasClass'] = self.courses[c]['week_odd'] % 2 === self.week % 2;
                                } else {
                                    self.courses[c]['hasClass'] = false;
                                }
                            }
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        created: function () {
            let self = this;
        },
        methods: {
            changeCourses: function () {
                let self = this;
                for (let i = 100; i <= 700; i += 100) {
                    for (let j = 1; j <= 11; j += 2) {
                        let c = i + j;
                        if (typeof(self.courses[c]['name']) === "string") {
                            if (self.week >= self.courses[c]['week_begin'] &&
                                self.courses[c]['week_end'] >= self.week) {
                                if (self.courses[c]['week_odd'] === 0) {
                                    self.courses[c]['hasClass'] = true;
                                } else self.courses[c]['hasClass'] = self.courses[c]['week_odd'] % 2 === self.week % 2;
                            } else {
                                self.courses[c]['hasClass'] = false;
                            }
                        }
                    }
                }
            },
            changeWeek(week) {
                vm.week = week;
            },
            t(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            },
            showWeeks(e) {
                e.preventDefault();
                $("#weeks").slideToggle();
            },
            hideSideBar(e) {
                e.preventDefault();
                if ($("#wrapper").hasClass('toggled')) {
                    $("#wrapper").removeClass("toggled");
                }
            }
        }
        ,
        watch: {
            week: function () {
                this.changeCourses();
            }
        }
    });
</script>
</html>