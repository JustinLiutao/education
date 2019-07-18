<?php

use PHPMailer\PHPMailer\PHPMailer;
use think\facade\Env;

function sendEmail($to, $title, $content)
{
    $mail = new PHPMailer();
    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = Env::get('EMAIL_SMTPDEBUG', '0');
    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
    //邮箱的服务器地址
    $mail->Host = Env::get('EMAIL_HOST', 'smtp.qq.co'); // 阿里云：smtp.mxhichina.com，QQ：smtp.qq.com，网易：stmp.163.com
    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = Env::get('EMAIL_SECURE', 'ssl');
    //设置ssl连接smtp服务器的远程服务器端口号，默认是25，SSL填 465或587
    $mail->Port = Env::get('EMAIL_PORT', '465');
    //设置发送的邮件的编码
    $mail->CharSet = 'UTF-8';
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = Env::get('EMAIL_NAME', '');
    //smtp登录的账号
    $mail->Username = Env::get('EMAIL_USER', '');
    //smtp登录的密码 QQ则使用生成的授权码（上文有提过）
    $mail->Password = Env::get('EMAIL_PASSWORD', '');
    //设置发件人邮箱地址 同登录的账号
    $mail->From = Env::get('EMAIL_USER', '');
    //邮件正文是否为html编码 true或false
    $mail->isHTML(true);

    //设置收件人邮箱地址，第一个参数为收件人邮箱地址，第二参数为给该地址设置的昵称，可以省略，添加多个收件人 则多次调用方法即可
    $mail->addAddress($to);
    //添加该邮件的主题
    $mail->Subject = $title;
    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;
    //为该邮件添加附件。第一个参数为附件存放的目录（相对目录、或绝对目录均可），第二参数为在邮件附件中该附件的名称。简单的使用，不建议使用该函数
    // $mail->addAttachment('Update','Demo.jpg');
    //同样该方法可以多次调用 上传多个附件
    // $mail->addAttachment('../pic/Update','Demo2.png');

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}