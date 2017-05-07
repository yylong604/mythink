<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/3
 * Time: 11:07
 */

namespace Home\Controller;


class MineController extends HomeController
{
    public function index(){
        $model = D('Member');
//        $user=$model->
        $this->display('mine');
    }
    //我的保修
    public function repair()
    {
        $model = D('Fix');
        $list = $model->where(['uid'=>1])->select();
        foreach($list as &$v){
            $v['url'] = U('Home/Mine/argue',['id'=>$v['id']]);
        }
        $this->assign('list',$list);
        $this->display('detail');
    }

    //评论
    public function argue()
    {
        $id = I('id');
        $model = D('Fix');
        if(IS_AJAX){
            $data = I('data');
            $ajax_id = I('id');
            $model = D('Fix');
            $d['argue'] = $data;
            $r = $model->where(['id'=>$ajax_id])->save($d);
            if($r){
                $this->success($data);
            }else{
                $this->error('数据不存在');
            }
        }
        $list = $model->where(['id'=>$id])->find();
        $this->assign('list',$list);
        $this->display('argue');
    }

    //我的活动
    public function active()
    {
        //实例化参加活动表
        $model = M('join');
        //连表查询,join加入目标表 on 目标字段=当前表的字段
        $list = $model->alias('j')
            ->field()
            ->join('ot_document as d on d.id=j.ac_id')
            ->where()
            ->select();
        foreach($list as &$v){
            $v['url'] = U('activity',['id'=>$v['id']]);
            $v['path'] = get_cover($v['cover_id'],'path');
        }
        $this->assign('list',$list);
        $this->display('Wechat/active');
    }

    //活动详情
    public function activity($id)
    {
        if(is_login()){
            $document = D('document');
            $document_article = D('document_article');
            $list1 = $document->where(['id'=>$id])->find();
            $list2 = $document_article->where(['id'=>$id])->find();
            $document->where(['id'=>$id])->setInc('view');
            $this->assign('list1',$list1);
            $this->assign('list2',$list2);
            $this->display('activity');
        }else{
            $this->error('请登录',U('User/login'));
            session('url',U());
        }
    }

    //签到积分
    public function score()
    {
        if(IS_AJAX){
            //接收参数
            $score = I('score');//积分
            $uid = I('uid');//用户id
            $model = D('Member');//实例化member
            //根据用户uid查找用户数据
            $data = $model->where(['uid'=>$uid])->find();
            //判断时间是否等于今天
            $old = date("Ymd",$data['update_time']);
            $now = date("Ymd",time());
            //不等于今天,执行签到
            if($old != $now){
                //添加积分
                $r = $model->where(['uid'=>$uid])->setInc('score',$score);
                //添加积分成功
                if($r){
                    $model = M('Member');
                    $d['update_time'] = time();//更新时间
                    $model->where(['uid'=>$uid])->save($d);
                    $this->success($score);
                }else{//失败  提示错误信息
                    $this->error('数据不存在');
                }
            }
            //时间相同提示
            $this->error('今天已经签过了!');
        }
    }
}