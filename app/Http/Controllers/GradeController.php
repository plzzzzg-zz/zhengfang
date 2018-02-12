<?php

namespace App\Http\Controllers;

use HtmlParser\ParserDom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GradeController extends Controller
{
    var $html_dom;
    var $student_id;
    var $student_name;
    var $view_state;

    public function __construct()
    {
        $this->html_dom = new ParserDom();
    }

    /**
     * @param $id
     * 展示成绩
     */
    function showGrades(Request $request){
//        $data = $this->getGrades($request);
        return view('grade',compact('data'));
    }


    /**
     * 获取正方成绩数据
     */
    function getGrades(Request $request){
        $data = $request->session()->get('_token');
        $name = $this->student_name;
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
        $name = mb_convert_encoding($name, "gb2312", "UTF-8");
        $name = urlencode($name);
        $grade_url = 'http://202.116.160.170/xscjcx.aspx?xh='.$this->student_id."&xm=".$name;
        $xn = "%D1%A7%C4%EA%B3%C9%BC%A8";
//        $xn ='学年成绩';
        $result = $this->post($grade_url,$cookie_file,'');
        $this->html_dom->load($result);
        $viewState = $this->html_dom->find("input[name=__VIEWSTATE]",0)->getAttr("value");
        $post_data = array(
            'ddlXN' => '2017-2018',
            '__VIEWSTATE' => $viewState,
            '__EVENTTARGET'=>'',
            '__EVENTARGUMENT'=>'',
            'hidLanguage'=>'',
            'ddlXQ' => '1',
            'ddl_kcxz' => '',
            'btn_xn'=> $xn
        );
        $post_data = http_build_query($post_data);
        //取得成绩页面代码
        $html = $this->post($grade_url,$cookie_file,$post_data);
        //获取成绩
        $data = $this->analyzeGrades($html);
        return $data;
    }

    /**
     * @param $html
     * @return array|string
     * 分析成绩页面获取各科成绩
     */
    function analyzeGrades($html){
        $this->html_dom->load($html);
        $trs = $this->html_dom->find('table[class=datelist] tbody',0)->find('tr');
        $data = array();
        $courses = array();
        foreach ($trs as $tr){
            $tds = $tr->find("td");
            if ($tds[3]->getPlainText() == '课程名称') continue;
            $course = array();
            $course['name'] = $tds[3]->getPlainText();
            $course['credit'] = $tds[6]->getPlainText();
            $course['grade_point'] = $tds[7]->getPlainText();
            $course['ordinary_perf'] = $tds[8]->getPlainText();
            $course['final_perf'] = $tds[10]->getPlainText();
            $course['grade'] = $tds[12]->getPlainText();
            array_push($courses,$course);
        }
        $data['courses'] = json_encode($courses,JSON_UNESCAPED_UNICODE);
        $data['title'] = $this->html_dom->find('#lbl_bt',0)->getPlainText();
        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 登陆页面
     */
    public function login(Request $request)
    {
//        var_dump($_SERVER["HTTP_HOST"]);
        $this->getCaptcha($request);
        $data = $request->session()->get('_token');
        $captcha = 'captcha/' . $data . ".jpg";
//        $captcha_path = Storage::url($captcha);
        $captcha_path = asset(Storage::url($captcha));
//        var_dump($captcha_path);
        $func = "grade";
        return view('login_zhengfang', compact('captcha_path','func'));
    }

    /**
     * 保存验证码
     * @param Request $request
     */
    public function getCaptcha(Request $request)
    {
        $data = $request->session()->get('_token');
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
        $captcha_url = 'http://202.116.160.170/CheckCode.aspx';
        $captcha = '/public/captcha/' . $data . ".jpg";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);  //保存cookie
        curl_setopt($curl, CURLOPT_URL, $captcha_url);  //设置url
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//不输出返回结果
        $img = curl_exec($curl);  //执行curl
        curl_close($curl);
        Storage::put($captcha, $img);
    }


    /**
     * @param $html
     * @return string
     */
    public function getViewState($con1)
    {
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
        $viewState = $view;
        return $viewState;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 登录请求
     */
    public function login_post(Request $request)
    {
//        header("Content-type:text/html;charset=utf-8");
        $data = $request->session()->get('_token');
        $cookie_file = storage_path() . '\\app\\public\\cookies\\' . $data . '.cookie';
        $input = $request->all();
        $func = $input['func'];
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
        if (strpos($string, '欢迎您')) {
//            $this->html_dom->load($string);
//            $info = $this->html_dom->find('a[target=zhuti]', 8)->getAttr('href');
//            $info = 'http://202.116.160.170/' . $info;
//            $info = explode('=', $info);
//            $info[2] = mb_convert_encoding(substr($info[2], 0, -7), "gb2312", "UTF-8");
//            $info[2] = urlencode($info[2]) . '&gnmkdm';
//            $info = implode('=', $info);
//            $this->courses_url = $info;
            $this->html_dom->load($string);
            //getName
            $this->student_name = $this->html_dom->find("#xhxm",0)->getPlainText();
            //getId
            $this->student_id=$xh;
            if ($func == 'grade'){
                return $this->showGrades($request);
            }
            //登陆成功重定向
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

    /**
     * @param $uri
     * @return mixed
     * 发送请求
     */
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
//        if (!is_null($this->courses_url)) {
//            curl_setopt($ch, CURLOPT_REFERER, $this->courses_url);
//        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  //post提交数据
        $result = curl_exec($ch);
        curl_close($ch);
        $result = mb_convert_encoding($result, "UTF-8", "gb2312");
        //适应ParserDom
        $result = str_replace('gb2312', 'utf-8', $result);
        return $result;
    }
}
