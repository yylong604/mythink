<?php
/**
 * 微信授权
 */

namespace Home\Controller;


use EasyWeChat\Foundation\Application;
use Think\Controller;

// 这行代码是引入 `composer` 的入口文件，这样我们的类才能正常加载。
require './vendor/autoload.php';

class EsaywechatController extends Controller
{
    //
//    public function index()
//    {
//        $app = new Application(C('ESAY_WECHAT'));
//        $server = $app->server;
//        $response = $server->serve();
//        $response->send();
//        var_dump(session('openid'));
//    }
    //回调页面
    public function callback()
    {
        // 一些配置
        $app = new Application(C('ESAY_WECHAT'));
        $oauth = $app->oauth;
        $user = $oauth->user();
        session('openid',$user->id);
        session('img',$user->getAvatar());
    }

    public function test()
    {
        var_dump(session('img'));
    }

    public function getmenus()
    {
        $app = new Application(C('ESAY_WECHAT'));
        $menu = $app->menu;
        $menus = $menu->all();
        var_dump($menus);
    }


    public function setmenus()
    {
        // 一些配置
        $app = new Application(C('ESAY_WECHAT'));
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "view",
                "name" => "首页",
                "url"  => "http://119.23.45.29/onethink/index.php?s=/Home/Index/index.html",
            ],
            [
                "type" => "view",
                "name" => "我的",
                "url"  => "http://119.23.45.29/onethink/index.php?s=/Home/Wechat/mine.html",
            ]
        ];
        $r = $menu->add($buttons);
        var_dump($r);
    }



    //用户绑定
    public function bang()
    {
        //1.获取openid
        //1.1 openid不存在
        if(!session('openid')){
            //实例化wechat组件
            $app = new Application(C('ESAY_WECHAT'));
            //回调页面获取
            $response = $app->oauth->redirect();
            //返回
            $response->send();
        }
        //1.2 存在取出
        $openid = session('openid');
        //2.查看是否绑定
        $member = D('member');
        //2.1 根据openid到member表查找
        $data = $member->where(['openid'=>$openid])->find();
        //2.2 数据存在 说明已经绑定,直接登录
        if($data){
            session('uid',$data['uid']);
            $member->login($data['uid']);//登录
            redirect(U('Home/Index/index'),2,'登录成功!');//跳转到登录页面
        }else{//2.3 没有绑定跳转到登录页面
            redirect(U('User/login'),2,'请绑定!');
        }
    }

}