<!DOCTYPE html>
<html>
<head>
    <title>易班云盘</title>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous"> -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon shortcut" href="{{asset('cloud.png')}}" type="image/x-icon">
    <style>
        .custom-file-control::before{
            content: "上传";
        }
        .custom-file-control::after{
             content: "Choose a file ...";
         }
         .custom-file-control-uploading::after{
             content: "uploading ..." !important;;
         }
        a{
            text-decoration: none !important;
        }
        @media (max-width: 575px) {
            .hidden-lg-up{
                display: none;
            }
        }
        table{
             table-layout:fixed;word-wrap:break-word;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand hidden-lg-up" >易班云盘</a>
    <!-- <div class="progress">
  <div id="progressBar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div> -->
    <form class="form-inline" id="uploadForm"  enctype="multipart/form-data" method="post" action="https://openapi.yiban.cn/data/upload">
        <div class="row">
            <div class="col">
        <label class="sr-only">分享类型</label>
        <select name="share_type" id="share_type" class="form-control" onchange="changeShareType()">
            <option value="1">个人文件</option>
            <option value="2">所有好友</option>
            <option value="3">指定公共群/机构群</option>
            <!-- <option value="4">指定用户</option> -->
            <option value="5">公开</option>
        </select>
        </div>
        <div class="col d-none" id="group">
        <label class="sr-only">群组</label>
        <select name="share_content"  class="form-control ">
            @foreach($groups as $group)
            <option value="{{$group['group_id']}}">{{$group['group_name']}}</option>
            @endforeach
        </select>
        </div>
        <!-- <label class="custom-file">
            <input type="text" hidden value="" name="access_token" id="access_token">
            <input type="text" hidden value="" name="file_name" id="file_name">
            <input type="file" id="file2" class="custom-file-input" name="file_tmp">
            <span class="custom-file-control fileName" id="span"></span>
        </label> -->
            </div>
            <div class="row">
        <div class="col">
        <div class="custom-file">
            <input type="text" hidden value="" name="access_token" id="access_token">
            <input type="text" hidden value="" name="file_name" id="file_name">
          <input type="file" id="file2" class="custom-file-input" name="file_tmp">
          <label class="custom-file-label" id="label-for-file" for="file2" style="justify-content: left">Choose file</label>
            </div>
            </div>
        </div>
    </form>
</nav>
<br>
<div class="container-fluid">
    <div id="accordion" role="tablist">
        {{--1 all--}}
        <div class="card">
            <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <div class="card-header" role="tab" id="headingOne">
                <h5 class="mb-0">
                        ALL
                </h5>
            </div>
            </a>
            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne"
                 data-parent="#accordion">
                <div class="card-body">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>大小</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files['all']['info']['file'] as $file)
                            <tr>
                                <td><a href="{{$file['download_url']}}"><p>{{$file['file_name']}}</p></a></td>
                                <td>{{$file['file_size']}}</td>
                                {{--<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlencode($file['download_url'])}}"
                                        frameborder="0"></iframe>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--2 图片--}}
        <div class="card">
            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                       aria-controls="collapseTwo">
            <div class="card-header" role="tab" id="headingTwo">
                <h5 class="mb-0">
                        Pic
                </h5>
            </div>
            </a>
            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo"
                 data-parent="#accordion">
                <div class="card-body">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>大小</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files['pictures']['info']['file'] as $file)
                            <tr>
                                <td><a href="{{$file['download_url']}}"><p>{{$file['file_name']}}</p></a></td>
                                <td>{{$file['file_size']}}</td>
                                {{--<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlencode($file['download_url'])}}"
                                        frameborder="0"></iframe>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--3 文档--}}
        <div class="card">
            <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false"
                       aria-controls="collapseThree">
            <div class="card-header" role="tab" id="headingThree">
                <h5 class="mb-0">
                    Docs
                </h5>
            </div>
            </a>
            <div class="collapse" id="collapseThree" role="tabpanel" aria-labelledby="headingThree"
                 data-parent="#accordion">
                <div class="card-body">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>大小</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files['docs']['info']['file'] as $file)
                            <tr>
                                <td><a href="{{$file['download_url']}}"><p>{{$file['file_name']}}</p></a></td>
                                <td>{{$file['file_size']}}</td>
                                {{--<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlencode($file['download_url'])}}"
                                        frameborder="0"></iframe>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--4 视频--}}
        <div class="card">
            <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false"
                       aria-controls="collapseFour">
            <div class="card-header" role="tab" id="headingFour">
                <h5 class="mb-0">
                        Videos
                </h5>
            </div>
            </a>
            <div class="collapse" id="collapseFour" role="tabpanel" aria-labelledby="headingFour"
                 data-parent="#accordion">
                <div class="card-body">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>大小</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files['video']['info']['file'] as $file)
                            <tr>
                                <td><a href="{{$file['download_url']}}"><p>{{$file['file_name']}}</p></a></td>
                                <td>{{$file['file_size']}}</td>
                                {{--<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlencode($file['download_url'])}}"
                                        frameborder="0"></iframe>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{--5 压缩--}}
        <div class="card">
            <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false"
                       aria-controls="collapseFive">
            <div class="card-header" role="tab" id="headingFive">
                <h5 class="mb-0">
                        ZIP
                </h5>
            </div>
            </a>
            <div class="collapse" id="collapseFive" role="tabpanel" aria-labelledby="headingFive"
                 data-parent="#accordion">
                <div class="card-body">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>大小</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($file['zips']['info']['file'] as $file)
                            <tr>
                                <td><a href="{{$file['download_url']}}"><p>{{$file['file_name']}}</p></a></td>
                                <td>{{$file['file_size']}}</td>
                                {{--<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlencode($file['download_url'])}}"
                                        frameborder="0"></iframe>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <iframe  id="iframe" src="{{route('/data/iframe')}"></iframe> -->

<div id="textarea">
</div>
</body>
<!-- script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
        integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
        integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
        crossorigin="anonymous"></script> -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.map"></script>--}}
{{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- <script type="text/javascript">
    $(document).ready(function () {
//        document.domain="yiban.cn";
    });
    var onProgress = function (e) {
            if (e.lengthComputable) {
                document.getElementById("progressBar").style.width = Math.round(e.loaded * 100 / e.total) + 'px';
            }
        };
    //var access_token={!! $access_token !!};
    $(function() {
        $("input:file").change(function (){
//            var fileName = $(this).val();
//            console.log(fileName);
            var apiUrl="https://openapi.yiban.cn/data/upload";
            $("#file_name").val($(this).val().split('\\').pop());
//            console.log($(this).val().split('\\').pop());
//            console.log($("#file_name").val());
//            $("#uploadForm").submit();

            // $('iframe[id=iframe]').contents().find('#uploadForm').val()


            //--jsonp
           var formData=new FormData();
           formData.append('access_token',"{!! $access_token!!}");
           formData.append('file_name',$(this).val().split('\\').pop());
//            console.log($(this).val().split('\\').pop());
           formData.append('file_tmp',$('#uploadForm')[0]);
//            $.ajax({
//                type:"POST",
//                method:"post",
//                url:"https://openapi.yiban.cn/data/upload",
//                data:formData,
//                dataType:'jsonp',
//                //jsonp不支持post跨域，所以会转换为GET
//                cache:false,
//                crossDomain: true,
//                processData:false,
//                contentType:false,
//                success:function (res) {
//                    console.log(res);
//                }
//            }).fail(function (res) {
//                console.log(res);
//            });
            //--jsonp end

            // var xhttp = new XMLHttpRequest();
            // xhttp.upload.onprogress= onProgress;
            // xhttp.open("POST",apiUrl,true);
            // xhttp.setRequestHeader("Access-Control-Allow-Origin","openapi.yiban.cn");
            // xhttp.onreadystatechange = function() {
            //   if (xhttp.readyState == 4) {
            //     //显示请求结果
            //     console.log(xhttp.getAllResponseHeaders());
            //   }
            // };
            // xhttp.send(formData);


            var submitForm = SubmitByIframe.create({
                url:apiUrl,
                blankUrl: '/blank',
                form: $('#uploadForm'),
                formId: '#uploadForm',
                type: 'POST',
                iframeId: '#hidden_iframe',
                contentType: 'multipart/form-data',
                success: function(res) {
                    window.parent.console.log(res);
                    parent.alert("上传成功");
                },
                error: function() {
                    alert('error');
                    alert("上传失败");
                }
            });
            // submitForm.submit();
//            console.log('file submitted');
//            //refresh
            $('#hidden_iframe').on('load',function(){
                iframeContents = this.contentWindow.document.body.innerHTML;
                $('#status').html(iframeContents);
                console.log(iframeContents);
                console.log('done');
            })
        });
    });

    //todo 异步post
    function done(){
        alert("finished");
    }


    var SubmitByIframe = function(options) {
        this.options = options;
        this.url = options.url;
        this.blankUrl = options.blankUrl || 'blank.html';
        this.$form = $(options.form);
        this.type = options.type || 'POST';
        this.formId = options.formId || options.form;
        this.iframeId = options.iframeId || 'hidden_iframe';
        this.contentType = options.contentType || 'multipart/form-data';
        this.success = options.success;
        this.error = options.error;
    }
    SubmitByIframe.prototype = {
        init: function() {
            var self = this;
            // 创建并插入隐藏的iframe
            var iframeStr = '<iframe id="' + self.iframeId + '" name="' + self.iframeId + '" src="' + self.blankUrl + '" style="display:none"></iframe>';
            $('body').append(iframeStr);

            // form表单属性初始化
            self.$form.attr({
                target: self.iframeId,
                enctype: self.contentType,
                id: self.formId,
                method: self.type
            });
            var hiddenUrlInput = '<input type="hidden" name="url"/>';
            self.$form.append(hiddenUrlInput);
        },

        getUrlValue: function(s){
            if (s.search(/#/)>0){
                s = s.slice(0,s.search(/#/));
            }
            var r = {};
            if (s.search(/\?/)<0){
                return r;
            }
            var p = s.slice(s.search(/\?/)+1).split('&');
            for(var i=0,j=p.length; i<j; i++){
                var tmp = p[i].split('=');
                r[tmp[0]] = tmp[1];
            }
            return r;
        },

        bindSubmit: function() {
            var self = this;
            $(self.iframeId).unbind('load').unbind('errorupdate');
            $('body').on('load', self.iframeId, function() {
                try{
                    var res = self.getUrlValue($(self.iframeId).prop('contentWindow').location.href);
                    if(res) {
                        self.success && self.success(res);
                        $('body').attr("background-color","black");
                        $('$status',window.parent.document).attr('color',"green");
                        window.top.done();
                        console.log("done");
                    } else {
                        self.error && self.error();
                    }
                }catch(err){
                    self.error && self.error();
                }
            })
                .on('errorupdate', self.iframeId, function() {
                    self.error && self.error();
                });
            // alert("upload done");
            // window.location.reload();
        },

        submit: function() {
            this.bindSubmit();
            this.$form.attr('action', this.url).submit();
        },

        render: function() {
            this.init();
        }
    };

    SubmitByIframe.create = function(options) {
        var formSubmit = new SubmitByIframe(options);
        formSubmit.render();
        return formSubmit;
    };
</script> -->
<script type="text/javascript">

    $(document).ready(function () {

        $("input:file").change(function () {
            $("#label-for-file").html("uploading...");
            var iframe = $('<iframe name="postiframe" id="postiframe" style="display: none"></iframe>');
            $("body").append(iframe);

            var form = $('#uploadForm');
            form.attr("action", "https://openapi.yiban.cn/data/upload");
            form.attr("method", "post");

            form.attr("encoding", "multipart/form-data");
            form.attr("enctype", "multipart/form-data");
            // console.log($('#userfile').val()+";"+$("input:file").val().split('\\').pop()+":"+$('#access_token').val()+":"+"{!!$access_token!!}");
            form.attr("target", "postiframe");
            // form.attr("file", $('#userfile').val());
            // form.attr("file_name", $('#file2').val().split('\\').pop());
            // form.attr("access_token", "{!! $access_token!!}}");
            $('#file_name').val($('#file2').val().split('\\').pop());
            $('#access_token').val("{!!$access_token!!}");
            $('#span').addClass('custom-file-control-uploading');
            form.submit();

            $("#postiframe").on('load',function () {
                $('#span').removeClass('custom-file-control-uploading');
                $("#label-for-file").html("Choose File");
                // iframeContents = this.contentWindow.document.body.innerHTML;
                // iframeContents=$('#iframe').contents().find('body').val();
                // $("#textarea").html(iframeContents);
                // console.log(iframeContents);
                alert("上传完成");
                window.location.reload();
            });

            return false;

        });

    });

    function changeShareType(){
        $share_type = $('#share_type').val();
        console.log($share_type);
        if ($share_type == 3) {
            $("#group").removeClass('d-none');
        }else{
            $("#group").addClass('d-none');
        }
    }

</script>

</html>