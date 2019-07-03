<?php

namespace app\admin\controller;

use app\admin\model\UserModel;
use think\Controller;
use think\Request;
use think\cache\driver\Redis;
use QL\QueryList;
use think\Db;
use think\Facade\Cache;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;


class IndexController extends Controller
{

    /**
     * Captcha Form
     * @param Request $r
     * @return \think\response\View
     */
    public function captcha(Request $r)
    {
        if ($r->post()) {
            $params = $r->post();
            $verify = $params['captcha'];
            $comm = new Comm();
            if (!$comm->checkVerify($verify)) {
                $this->error('失败');
            }else{
                $this->success('成功');
            }
        }
        return view();
    }


    /**
     * Redis
     */
    public function redis()
    {
        $redis = new redis();
        $redis->set('testRedis', [1, 2, 3]);
        var_dump($redis->get('testRedis')); //结果：bool(true)
    }


    /**
     * QueryList爬虫
     * @param string $url
     */
    public function querylist($url='https://www.baidu.com')
    {
        $html = file_get_contents($url);
        //采集规则
        $rules = [
            //采集img标签的src属性，也就是采集页面中的图片链接
            'name1' => ['img','src'],
            //采集class为content的div的纯文本内容，
            //并移除内容中的a标签内容，移除id为footer标签的内容，保留img标签
            'name2' => ['div.content','text','-a -#footer img'],
            //采集第二个div的html内容，并在内容中追加了一些自定义内容
            'name3' => ['div:eq(1)','html','',function($content){
                $content += 'some str...';
                return $content;
            }]
        ];
        // 过程:设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
//        $data =  QueryList::get('https://www.baidu.com');
        $data = QueryList::html($html)->rules($rules)->query()->getData();
        //打印结果
        print_r($data->all());
    }

    /**
     * cache
     */
    public function cache($id){
//        $res = Db::name('ad_type')
//            ->cache('test',60)
//            ->select();
//        dump(cache::get('test'));

        cache::set('uu','xxx');
//        $model = new UserModel();
//        $res = $model->cache('user',60)->select();
//        dump($res);
        dump(cache('uu'));
    }

    /**
     *  JST 生成token
     */
    public function jwt()
    {
        $builder = new Builder();
        $signer = new Sha256();
        $secret = "suspn@)!*";

        //设置header和payload，以下的字段都可以自定义
        $builder->setIssuer("lzx") //发布者
            ->setAudience("lt") //接收者
            ->setId("abc", true) //对当前token设置的标识
            ->setIssuedAt(time()) //token创建时间
            ->setExpiration(time() + 60) //过期时间
            ->setNotBefore(time() + 1) //当前时间在这个时间前，token不能使用
            ->set('uid', 30061); //自定义数据

        //设置签名
        $builder->sign($signer, $secret);

        //获取加密后的token，转为字符串
        $token = (string)$builder->getToken();
        echo $token;
    }

    /**
     *  JWT 验证token
     */
    public function checkJwt() {
        $signer  = new Sha256();
        $secret = "suspn@)!*";
        //获取token
        $token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
        if (!$token) {
            exit('Invalid token');
        }

        try {
            //解析token
            $parse = (new Parser())->parse($token);
            //验证token合法性
            if (!$parse->verify($signer, $secret)) {
                exit('Token fuck');
            }
            //验证是否已经过期
            if ($parse->isExpired()) {
                exit('Already expired');
            }
            //获取数据
            var_dump($parse->getClaims());
        } catch (Exception $e) {
            //var_dump($e->getMessage());
            exit('Fuck Bitch');
        }
    }


    /**
     * description: 单例模式实例
     */
    public function singlePattern()
    {
        $singleObj1 = PatternController::getObj();
        $singleObj2 = PatternController::getObj();
        if($singleObj1 === $singleObj2){
            echo 1;
        }else{
            echo 2;
        }
    }


}
