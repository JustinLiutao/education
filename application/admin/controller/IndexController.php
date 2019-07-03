<?php

namespace app\admin\controller;

use app\admin\model\UserModel;
use think\Controller;
use think\Request;
use think\cache\driver\Redis;
use QL\QueryList;
use think\Db;
use think\Facade\Cache;

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

    public function jwt() {
        echo 1;
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
