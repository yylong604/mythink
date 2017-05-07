<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class RepairModel extends Model {
    protected $patchValidate=true;
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', 'require', '电话不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('username', 'require', '报修人不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('content', 'require', '报修内容不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('address', 'require', '报修地址不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
        ['create_time','time',3,'function'],// 对update_time字段在更新的时候写入当前时间戳


    );
//    protected $_validate = [
//        ['name','require','姓名不能为空！'],//字段,规则,错误提示,
//        ['tel','/^1[3|4|5|8][0-9]\d{8}$/','电话格式不正确！',1,'regex',3],//0:存在就验证 1:必须验证 2:不为空时验证  规则 时间
//        ['address','require','地址不能为空！'],//时间:1 新增 2 编辑  3 都要验证
//        ['question','require','问题不能为空！'],
//    ];
//
//    protected $_auto = [
//        ['status','0'],// 新增的时候把status字段设置为1
//        ['create_time','time',3,'function'],// 对update_time字段在更新的时候写入当前时间戳
//    ];

}
