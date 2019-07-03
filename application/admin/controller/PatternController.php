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
}