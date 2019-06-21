<?php
/**
 * Created by PhpStorm.
 * User: tao12.liu
 * Date: 2019/6/20
 * Time: 14:59
 */

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class TalkModel extends Model
{
    protected $pk = 'id';
    protected $table = 'tp_talk';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    // 开启自动生成时间，定义时间戳字段名(默认 create_time,update_time)
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

}