<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27 0027
 * Time: 上午 10:39
 */

namespace Home\Controller;

//前台登录后页面
use Think\Controller;

class CenterController extends HomeController{
    public function index(){
        $this->display('index');
    }
}