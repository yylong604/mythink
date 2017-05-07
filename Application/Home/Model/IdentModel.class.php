<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/28
 * Time: 14:26
 */

namespace Home\Model;


use Think\Model;

class IdentModel extends Model
{
    protected $_validate = [
        ['name','require','姓名不能为空'],
        ['room_num','require','房号不能为空'],
        ['tel','/^1[1|3|5|7|8|9]\d{9}$/','电话格式不正确!','regex',3],
        ['ident_id','/^[1-9]([0-9]{16}|[0-9]{13})[xX0-9]$/','身份证格式不正确','regex',3],
    ];

    protected $_auto = [
        ['create_time',NOW_TIME]
    ];

    public function checkident()
    {
       return $this->where(['uid'=>session('uid')])->select();
    }
}