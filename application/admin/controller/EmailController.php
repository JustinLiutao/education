<?php
/**
 * Created by PhpStorm.
 * User: tao12.liu
 * Date: 2019/7/18
 * Time: 11:09
 */

namespace app\admin\Controller;

use think\Controller;

Class EmailController extends Controller
{
    public function send()
    {
        $to = 'tao12.liu@tcl.com';
        $title = 'Title';
        $content = <<<BODY
<body>
<div style="background-color: #fc1;width:500px;">
    <div style="width:200px;margin:0 auto;background-color: #f8cbcb">
        <p>这里是测试</p>
    </div>
</div>
</body>
BODY;

        if(sendEmail($to, $title, $content)) {
            echo '发送成功';
        }else{
            echo '发送失败';
        }

    }




}