<?php

namespace App\Http\Controllers;

use App\Course;
use Carbon\Carbon;
use HtmlParser\ParserDom;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CoursesController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    var $html_dom;
    var $viewState;
    var $courses_url = null;
    var $week = 0;

    public function __construct()
    {
        $this->html_dom = new ParserDom();
        $this->setTime();
        View()->share('week', getenv('week'));
    }

    /**
     *  设置当前教学周为环境变量；
     *  调用方法：
     *  getenv('week');
     */
    public function setTime()
    {
        $FirstDayOfThisSemester = config('app.FirstDayOfThisSemester');
        $time = explode('-', $FirstDayOfThisSemester);
        $week = Carbon::today()->diffInWeeks(Carbon::createFromDate($time[0], $time[1], $time[2]));
        $week++;
        putenv('week=' . $week);
        $this->week = $week;
    }

    public function index(Request $request)
    {
        $data = $request->session()->get('_token');
        $data = '郭沛伦';
        return $data;
    }

    /**
     * @param $html
     * @return string
     */
    public function getViewState($con1)
    {
        $viewState = '';
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
        $viewState = $view;
        return $viewState;
    }

    /**
     * 保存验证码
     * @param Request $request
     */
    public function getCaptcha(Request $request)
    {
        $data = $request->session()->get('_token');
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
//        var_dump($cookie_file);
        $captcha_url = 'http://202.116.160.170/CheckCode.aspx';
        $captcha = '/public/captcha/' . $data . ".jpg";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);  //保存cookie
        curl_setopt($curl, CURLOPT_URL, $captcha_url);  //设置url
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//不输出返回结果
        $img = curl_exec($curl);  //执行curl
        curl_close($curl);
//        $fp = fopen($captcha,"w");  //文件名
//        fwrite($fp,$img);  //写入文件
//        fclose($fp);
        Storage::put($captcha, $img);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        $this->getCaptcha($request);
        $data = $request->session()->get('_token');
        $captcha = 'captcha/' . $data . ".jpg";
        $captcha_path = asset(Storage::url($captcha));
       // var_dump($captcha_path);
        return view('login', compact('captcha_path'));
    }

    /**
     * @param $url
     * @param $cookie
     * @param $post
     * @return mixed|string
     */
    public function post($url, $cookie, $post)
    {
        $ch = curl_init();
        $header = array("Content-Type: application/x-www-form-urlencoded; charset=gb2312");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gb2312');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, 'http://202.116.160.170/default2.aspx');  //重要，302跳转需要referer，可以在Request Headers找到
//        if (session()->has('student_id')){
//            curl_setopt($ch, CURLOPT_REFERER, 'http://202.116.160.170/xs_main.aspx?xh='.\session('student_id').'&xm='.\session('xm').'&gnmkdm='.\session('gnmkdm'));
//        }
        if (!is_null($this->courses_url)) {
            curl_setopt($ch, CURLOPT_REFERER, $this->courses_url);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  //post提交数据
        $result = curl_exec($ch);
        curl_close($ch);
        $result = mb_convert_encoding($result, "UTF-8", "gb2312");
        //适应ParserDom
        $result = str_replace('gb2312', 'utf-8', $result);
        return $result;
    }

    public function login_post(Request $request)
    {
//        header("Content-type:text/html;charset=utf-8");
        $data = $request->session()->get('_token');
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
        $input = $request->all();
        $url = 'http://202.116.160.170/default2.aspx';
        $captcha = $input['code'];
        $xh = $input['student_id'];
        $pw = $input['password'];
        $con1 = $this->post($url, $cookie_file, '');
        $this->viewState = $view = $this->getViewState($con1);
        $post = array(
            '__VIEWSTATE' => $view[1][0],
            'txtUserName' => $xh,
            'TextBox2' => $pw,
            'txtSecretCode' => $captcha,
            'RadioButtonList1' => '%D1%A7%C9%FA',  //“学生”的gbk编码
            'Button1' => '',
            'lbLanguage' => '',
            'hidPdrs' => '',
            'hidsc' => ''
        );
        $string = $this->post($url, $cookie_file, http_build_query($post));
//        编码转换:gb2312->utf-8
//        var_dump($string);
//        $encode = mb_detect_encoding($string, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
//        echo $encode;
        if (strpos($string, '欢迎您')) {
//            echo '登陆成功';
//            $courses_url = 'http://202.116.160.170/xskbcx.aspx?xh=201525010107&xm=%B9%F9%C5%E6%C2%D7';
//            $courses_url = 'http://202.116.160.170/xskbcx.aspx?xh=201525010107&xm=%B9%F9%C5%E6%C2%D7';
//            $data = $this->post($courses_url,$cookie_file,'');
//            return $data;
//            Cookie::queue(Cookie::make('student_id',$xh));
//            Cookie::make('student_id',$xh);
//            $_COOKIE['student_id'] = $xh;
//            $request->cookie('student_id',$xh);
//            dd(Cookie::get('student_id'));
            $this->html_dom->load($string);
            $info = $this->html_dom->find('a[target=zhuti]', 8)->getAttr('href');
            $info = 'http://202.116.160.170/' . $info;
            $info = explode('=', $info);
            $info[2] = mb_convert_encoding(substr($info[2], 0, -7), "gb2312", "UTF-8");
            $info[2] = urlencode($info[2]) . '&gnmkdm';
            $info = implode('=', $info);
            $this->courses_url = $info;
//            var_dump($this->courses_url);
//            preg_match_all("/<li\sclass=\"top\">(.*?)<\/li>/", $con1, $info); //获取__VIEWSTATE字段并存到$view数组中

//            $name =  mb_convert_encoding($info[2],"gb2312","UTF-8");
//            $name =urlencode($name);
//            echo "name:";
//            var_dump($name);
//            \session(['gnmkdm'=>$gnmkdm[0][0],'name'=>$name]);

//             $this->getCourses($string, $request);

//            dd($request->cookie());
            $this->updateCourses($this->getCourses($string, $request));
            //登陆成功重定向
//            return $this->show($request, $xh);
            return redirect(url('courses/'.$xh));
        } else {
            if (strpos($string, '密码')) {
                return redirect()->back()->withInput()->withErrors(['password' => '密码错误！']);
            } elseif (strpos($string, '验证码不正确')) {
                return redirect()->back()->withInput()->withErrors(['code' => '验证码不正确']);
            } elseif (strpos($string, '用户名不存在')) {
                return redirect()->back()->withInput()->withErrors(['student_id' => '学号不存在']);
            }
        }
    }


    public function getCourses(String $html, Request $request)
    {
//        var_dump($html);
        $this->html_dom->load($html);
        $data = $request->session()->get('_token');
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
        $name = substr($this->html_dom->find('#xhxm', 0)->getPlainText(), 0, -6);
        $student_id = substr($this->html_dom->find('#Form1', 0)->getAttr('action'), 16);
        \session(['student_id' => $student_id]);
//        var_dump($name);
//        var_dump($student_id);
//        编码
        $name = mb_convert_encoding($name, "gb2312", "UTF-8");
        $name = urlencode($name);
        $courses_url = 'http://202.116.160.170/xskbcx.aspx?xh=' . $student_id . '&xm=' . $name;
        $post_data = array(
            '__VIEWSTATE' => $this->viewState,
            '__EVENTTARGET' => 'xqd',
            'xnd' => '2016-2017',
            'xqd' => '1',
        );
//        var_dump()
        $post_data = http_build_query($post_data);
        //todo
        /**
         * 查询其他学期的课表失败。系统繁忙。
         */
        $html = $this->post($courses_url, $cookie_file, '');
//        var_dump($courses_url);
//        var_dump($html);
//        $html =str_replace("<br>",'I',$html);

        $this->html_dom->load($html);

        $courses = array();
        $courses_array = $this->html_dom->find('#Table1 tr');
        foreach ($courses_array as $tr) {
            $first_td = $tr->find('td', 0)->getPlainText();
            if ($first_td == '时间') {
                continue;
            }
            $td_array = $tr->find('td[align=Center]');

            foreach ($td_array as $td) {
                if (strlen(trim($td->getPlainText())) != 2) {
                    /*
                     *
                     * 课表的两种情况分析
                     * 1：乒乓球4<br>周二第3,4节{第1-16周}<br>姚业戴<br>东区1栋乒乓室
                     * 2：计算机组成原理<br>周二第1,2节{第2-6周|双周}<br>万军洲(万军洲)<br>4205<br><br>计算机组成原理<br>周二第1,2节{第10-16周|双周}<br>万军洲(万军洲)<br>4205
                     * 存在两个时间段的情况由<br><br>分隔
                     *
                     * 两个时间段存储问题。
                     */
//                    var_dump($td->innerHtml());
                    $content = explode('<br><br>', $td->innerHtml());
                    foreach ($content as $c) {
                        if (substr($c, 0, 4) == '<br>') {
//                            var_dump($c);
//                            echo "<br>";
//                            踩过坑，子串起始位置
                            $c = substr($c, 4);
//                            var_dump($c);
//                            echo "<br>";
//                            echo "<br>";
                        }
                        $contents = explode('<br>', $c);
                        if (sizeof($contents) < 3) {
                            continue;
                        }
//                        var_dump($contents);
                        $course['name'] = $contents[0];
                        $course['student_id'] = \session('student_id');
                        $time = $contents[1];
                        $course['teacher'] = $contents[2];
//                        if (isset($contents[3])){
//                            $course['place'] = $contents[3];
//                        }else{
//                            continue;
//                        }
                        $course['place'] = isset($contents[3]) ? $contents[3] : '';

//                    $reg_weekday = '[\x4e00-\x516d]';
//                    $course['weekday'] = preg_match($reg_weekday,$time)[0];
                        preg_match_all("|周(.*)第(.*),(.*)节{第(.*)-(.*)周}|isU", $time, $time_array);
//                        var_dump($time_array);
//                        echo "<br>";
                        $weekday = $time_array[1][0];
                        switch ($weekday) {
                            case '一':
                                $weekday = 1;
                                break;
                            case '二':
                                $weekday = 2;
                                break;
                            case '三':
                                $weekday = 3;
                                break;
                            case '四':
                                $weekday = 4;
                                break;
                            case '五':
                                $weekday = 5;
                                break;
                            case '六':
                                $weekday = 6;
                                break;
                        }
                        $course['weekday'] = $weekday;
                        $course['class_begin'] = (int)$time_array[2][0];
                        $course['class_end'] = (int)$time_array[3][0];
                        $course['week_begin'] = (int)$time_array[4][0];
                        $end = $time_array[5][0];
                        if (strlen($end) > 2) {
                            preg_match_all('/\d+/', $end, $rs);
//                        var_dump($rs);
                            $course['week_end'] = (int)$rs[0][0];
                            if (strpos($end, '双')) {
                                $course['week_odd'] = 2;
                            } else {
                                $course['week_odd'] = 1;
                            }
                        } else {
                            $course['week_end'] = (int)$end;
                            $course['week_odd'] = 0;
                        }
//                        var_dump($course);
                        array_push($courses, $course);
                        /**
                         * unicode:
                         * 周：\u5468
                         * 第：\u7b2c
                         * 一：\u4e00
                         * 六：\u516d
                         */
                    }
                }
            }
        }
//        var_dump($courses);
        $courses = json_encode($courses, JSON_UNESCAPED_UNICODE);
//        var_dump($courses);
//        var_dump(json_last_error_msg());
        return $courses;
    }

    public function updateCourses(String $new_courses)
    {
        $new_courses = json_decode($new_courses, JSON_UNESCAPED_UNICODE);
        $student_id = \session('student_id');
        $delete = Course::where('student_id', $student_id)->delete();
        foreach ($new_courses as $new) {
            Course::create($new);
        }
    }

    public function courses(Request $request, $student_id = null)
    {
//        参数顺序要跟路由对应
        if (is_null($student_id)) {
            $student_id = $request->session()->get('student_id');
        }
//        if (is_null($week)){
//            $week = $this->week;
//        }
        $courses = Course::all();
//        $courses = DB::table('courses')->where([['student_id','=',$student_id],['week_begin','<=',getenv('week')],['week_end','>=',getenv('week')]])->get();
//        if ($week == 0) {
        $courses = $courses
            ->where('student_id', $student_id);
//        } else {
//            $courses = $courses
//                ->where('student_id', $student_id)
//                ->where('week_begin', '<=', $week)
//                ->where('week_end', '>=', $week);
//            if ($week == 0) {
//            } else if ($week % 2 == 0) {
////            双周
//                $courses = $courses->where('week_odd', '!=', 1);
//            } else if ($week % 2 == 1) {
////            单周
//                $courses = $courses->where('week_odd', '!=', 2);
//            }
//        }
        $result = array();
        foreach ($courses as $course) {
            $tmp = array();
            $n = $course->weekday * 100 + $course->class_begin;
            if (array_key_exists($n, $result)) {
                $result[$n]['week_begin'] = min($course->week_begin, $result[$n]['week_begin']);
                $result[$n]['week_end'] = max($course->week_end, $result[$n]['week_end']);
                continue;
            }
            $tmp['duration'] = $course->class_end - $course->class_begin + 1;
            $tmp['place'] = $course->place;
            $tmp['name'] = $course->name;
            $tmp['teacher'] = $course->teacher;
            $tmp['week_begin'] = $course->week_begin;
            $tmp['week_end'] = $course->week_end;
            $tmp['week_odd'] = $course->week_odd;
            $tmp['hasClass'] = True;
            $result[$n] = $tmp;
        }
        //补全课表
        for ($i = 1; $i <= 7; $i++) {
            $k = $i * 100;
            for ($j = 1; $j <= 11; $j += 2) {
                $jj = $k + $j;
                if (!array_key_exists($jj, $result)) {
                    $empty['hasClass'] = false;
                    $result[$jj] = $empty;
                }
            }
        }
        return json_encode($result);
    }

    function show(Request $request, $xh = null)
    {
        if (is_null($xh)) {
            if ($request->session()->has('student_id')) {
//                $xh = Cookie::get('student_id');
                $xh = \session('student_id');
               $xh='201525010107';
            } else {
                // return $this->login($request);
                $xh='201525010107';
            }
        }
//        return redirect(url('/courses',compact('xh')));
        return \view('courses',compact('xh'));

    }

    public function dataTest()
    {
        $this->auth();
        $access_token = $this->access_token;
        // $fileListJsonStr = $this->sendRequest("https://openapi.yiban.cn/data/download?access_token={$access_token}");
        // $fileList = json_decode($fileListJsonStr); //it's a json object
        $next_page=1;
        $files=array();
        // var_dump($files);
        while($next_page!=false){
            $fileListJsonStr = $this->sendRequest("https://openapi.yiban.cn/data/download?access_token={$access_token}&count=30&page=".$next_page);
            $fileList = json_decode($fileListJsonStr,true);
            $next_page=$fileList->info->next_page;
            // var_dump($next_page);
             // var_dump(json_encode($fileList));
            $files=(array_merge($files,$fileList));
            // var_dump($fileList);
            // var_dump($files);
        }

//        var_dump($userInfo->info->yb_userid);
//        json_encode($userInfo,JSON_UNESCAPED_UNICODE);
        //$yb_userid = $file->info->yb_userid;
        // var_dump( $files);
         return $files;
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
        $this->access_token =  $access_token;
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
        if ($response == false){
            echo curl_error($ch);
        }
        return $response;
    }
}
