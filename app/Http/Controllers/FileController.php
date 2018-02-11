<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    var $access_token;

    public function __construct()
    {
        View()->share('access_token', $this->access_token);
    }

    function iframe(){
        return view('iframe');
    }
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function auth()
    {
        ini_set("display_errors", "On");
        error_reporting(0);

        if (!function_exists('curl_init')) {
            throw new Exception('YiBan needs the CURL PHP extension.');
        }
        if (!function_exists('json_decode')) {
            throw new Exception('YiBan needs the JSON PHP extension.');
        }
        if (!function_exists('mcrypt_decrypt')) {
            throw new Exception('YiBan needs the mcrypt PHP extension.');
        }
        $APPID = "c4d7d4b56df6392d";   //在open.yiban.cn管理中心的AppID
        $APPSECRET = "61f04f3295a1d625c3c0771b66e827be"; //在open.yiban.cn管理中心的AppSecret
        $CALLBACK = "http://f.yiban.cn/iapp166735";  //在open.yiban.cn管理中心的oauth2.0回调地址

        if (isset($_GET["code"])) {   //用户授权后跳转回来会带上code参数，此处code非access_token，需调用接口转化。
            $getTokenApiUrl = "https://oauth.yiban.cn/token/info?code=" . $_GET['code'] . "&client_id={$APPID}&client_secret={$APPSECRET}&redirect_uri={$CALLBACK}";
            $res = $this->sendRequest($getTokenApiUrl);
            if (!$res) {
                throw new Exception('Get Token Error');
            }
            $userTokenInfo = json_decode($res);
            $access_token = $userTokenInfo["access_token"];
        } else {
            $postStr = pack("H*", $_GET["verify_request"]);
            if (strlen($APPID) == '16') {
                $postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
            } else {
                $postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
            }
            $postInfo = rtrim($postInfo);
            $postArr = json_decode($postInfo, true);
            if (!$postArr['visit_oauth']) {  //说明该用户未授权需跳转至授权页面
                header("Location: https://openapi.yiban.cn/oauth/authorize?client_id={$APPID}&redirect_uri={$CALLBACK}&display=web");
                die;
            }
            $access_token = $postArr['visit_oauth']['access_token'];
        }
        $this->access_token = $access_token;
        \session(['access_token' => $access_token]);
        View()->share('access_token', $this->access_token);
    }

    /**
     * 获取文件列表
     */
    public function getFiles($file_type = null)
    {
        // $this->auth();
        $access_token = $this->access_token;
        $next_page = 1;
        $files = array();
        // var_dump($files);
        while ($next_page != false) {
            $fileListJsonStr = $this->sendRequest("https://openapi.yiban.cn/data/download?access_token={$access_token}&count=30&page="
                . $next_page . "&file_type=" . $file_type);
            $fileList = json_decode($fileListJsonStr, true); //assoc?array:json Object
            $next_page = $fileList->info->next_page;
            // var_dump($next_page);
            // var_dump(json_encode($fileList));
            $files = (array_merge($files, $fileList));
            // var_dump($fileList);
            // var_dump($files);
        }
//        var_dump($userInfo->info->yb_userid);
//        json_encode($userInfo,JSON_UNESCAPED_UNICODE);
        //$yb_userid = $file->info->yb_userid;
        return $files; //array
    }

    /**
     * 获取当前用户已加入的群。
     */
    public function getGroups(){
        $access_token = $this->access_token = "8b271107884b64e26a0f8b56ff3d3d60e5b17381";
        $page=1;
        $publicGroups = json_decode($this->sendRequest("https://openapi.yiban.cn/group/public_group?page={$page}&access_token={$access_token}"),true)['info'];
        // var_dump($publicGroups);
        $groups_count = $publicGroups['num'];
        $publicGroups = $publicGroups['public_group'];
        foreach ($publicGroups as &$group) {
            $group['group_name'] = "[公共群]".$group['group_name'];
        }
        //todo 页数和总理。while

        $organGroups = json_decode($this->sendRequest("https://openapi.yiban.cn/group/organ_group?page={$page}&access_token={$access_token}"),true)['info']['organ_group'];
        foreach ($organGroups as &$group) {
            $group['group_name'] = "[机构群]".$group['group_name'];
        }
        $groups = array_merge($organGroups,$publicGroups);
        return $groups;
    }

    public function FileList()
    {
        $this->auth();
        $allFiles=$this->getFiles();
        // var_dump($this->access_token);
        $pictures = array('info'=>array('file'=>array()));
        $videos = array('info'=>array('file'=> array()));
        $zips = array('info'=>array('file'=> array()));
        $docs = array('info'=>array('file'=>array()));
        $all = array('info'=>array('file'=>array()));
        foreach ($allFiles['info']['file'] as $file) {
            $file['file_size']=$this->formatSizeUnits($file['file_size']);
            array_push($all['info']['file'],$file);
            switch (explode('.',$file['file_name'])[1]) {
                case "jpg":
                case 'png':
                case 'ico':
                    array_push($pictures['info']['file'],$file);
                    break;
                case "zip":
                case 'rar':
                    array_push($zips['info']['file'],$file);
                    break;
                case "docx":
                case 'doc':
                case 'ppt':
                case 'pdf':
                case 'pptx':
                    array_push($docs['info']['file'],$file);
                    break;
                case "flv":
                case 'mp4':
                    array_push($pictures['info']['file'],$file);
                    break;
                default:
                    // var_dump(explode('.',$file['file_name'])[1]);
                    break;
            }
        }
        $files['pictures']=$pictures;
        $files['videos']=$videos;
        $files['zips']=$zips;
        $files['docs']=$docs;
        $files['all']=$all;
        //
        //$fileListJson = Storage::get('/public/json/fileList.json');
        //$files = json_decode($fileListJson, true);
        // return $files;
        $groups = $this->getGroups();
        // var_dump($groups);
        return view('fileListtest',compact('files','groups'));
    }

    function sendRequest($uri)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Yi OAuth2 v0.1');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $response = curl_exec($ch);
        if ($response == false) {
            echo curl_error($ch);
        }
        return $response;
    }

    function blank()
    {
        return "";
    }
}
