<?php
/**
 * Created by PhpStorm.
 * User: tao12.liu
 * Date: 2019/7/1
 * Time: 9:40
 */

namespace app\admin\controller;

class PatternController
{
    /**
     * 单例模式
     * 1. 私有化构造函数
     * 2. 私有化克隆
     * 3. 设置一个静态方法来生成对象，同事定义一个静态属性保存对象
     */
    /*
        private static  $ini;
        private function __construct(){}
        private function __clone(){}

        public static function getObj() {
            if( self::$ini instanceof self )
            {
                return self::$ini;
            }
            self::$ini = new self;
            return self::$ini;
        }
    */

    /**
     * 工厂模式
     * 1. 用静态方法生成对象
     */
    /*
        public static function create($type,array $size=[])
        {
            switch ($type)
            {
                case 'rectangle':
                    return new Rectangle($size[0],$size[1]);
                    break;
                case 'triangle':
                    return new Triangle($size[0],$size[1]);
                    break;
            }
        }
    */

    /**
     * 观察者模式
     * 1. 主体要有注册方法
     * 2. 主体要有通知方法
     * 3. 观察者要有执行方法
     */
    public $observer = [];

    public function register($obj)
    {
        $this->observer[] = $obj;
    }

    public function notify()
    {
        if (!empty($this->observer)) {
            foreach ($this->observer as $k => $v) {
                $v->action();
            }
        }
    }
}

/**
 * 工厂使用类
 */
/*
class Rectangle
{
    private $width;  //宽度
    private $height; //高度
    public function __construct($witch,$height)
    {
        $this->width = $witch;
        $this->height = $height;
    }
    //计算长方形面积: 宽 * 高
    public function area()
    {
        return $this->width * $this->height;
    }
}

class Triangle
{
    private $bottom;  //底边
    private $height;  //边长
    public function __construct($bottom,$height)
    {
        $this->bottom = $bottom;
        $this->height = $height;
    }
    //计算三角形面积:  (底 * 高) / 2
    public function area()
    {
        return ($this->bottom * $this->height)/2;
    }
}
*/

/**
 *  观察者使用类
 */
Class Dog
{
    public function action()
    {
        echo "旺旺<br>";
    }
}

Class Cat
{
    public function action()
    {
        echo "喵喵<br>";
    }
}