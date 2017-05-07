<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/26
 * Time: 11:51
 */

namespace Admin\Model;


use Think\Model;

class FixModel extends Model
{
    //自动验证
    /*
     *
     */
    protected $_validate = [
        ['name','require','姓名不能为空！'],//字段,规则,错误提示,
        ['tel','/^1[3|4|5|8][0-9]\d{8}$/','电话格式不正确！',1,'regex',3],//0:存在就验证 1:必须验证 2:不为空时验证  规则 时间
        ['address','require','地址不能为空！'],//时间:1 新增 2 编辑  3 都要验证
        ['content','require','问题不能为空！'],
    ];

    protected $_auto = [
        ['status','0'],// 新增的时候把status字段设置为1
        ['create_time','time',3,'function'],// 对update_time字段在更新的时候写入当前时间戳
    ];
}