<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index(Request $r)
    {
        if($r->post()){
            $params = $r->post();
            $verify = $params['captcha'];
            $comm = new Comm();
            if(!$comm->checkVerify($verify)){
                $this->error('验证码错误','admin/index/index');
            }

        }
        return view();
    }
}
