<?php
/**
 * Created by PhpStorm.
 * User: tao12.liu
 * Date: 2019/4/1
 * Time: 20:28
 */

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;


class CommController extends Controller
{

    //生成验证码
    public function captcha()
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            //验证码过期时间（s）
            'expire'    =>  60,
            'useCurve'  =>  true,
            'reset'   => true,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function checkVerify($verify){
        $captcha = new Captcha();
        if( !$captcha->check($verify))
        {
            // 验证失败
            return false;
        }else{
            return true;
        }
    }
}