<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<form class="form-inline" id="uploadForm"  enctype="multipart/form-data" method="post" action="https://openapi.yiban.cn/data/upload">
        <label class="custom-file">
            <input type="text" hidden value="{{$access_token}}" name="access_token">
            <input type="text" hidden value="courses_64.png" name="file_name" id="file_name">
            <input type="text" hidden name="share_type">
            <input type="text" hidden name="share_content">
            <input type="file" id="file2" class="custom-file-input" name="file_tmp">
            <span class="custom-file-control fileName"></span>
        </label>
    </form>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
	$("#uploadForm").on('submit',function(){
		window.parent.console.log("submitted.");
	})
</script>
</html>