<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/26
 * Time: 11:52
 */

namespace Admin\Controller;


use Admin\Model\FixModel;
use Think\Page;

//报修管理
class FixController extends AdminController
{
    //分页显示报修页面
    public function index()
    {
        $fix = D('Fix');
        $count = $fix->count();
        $page = new Page($count,3);
        if($count>$page->listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $pageHtml = $page->show();

        $list = $fix->where('status=0')->order('create_time')->limit($page->firstRow,$page->listRows)->select();
//var_dump($page->firstRow);exit;
        int_to_string($list,['status'=>[1=>'进行中',0=>'已结束']]);
        $this->assign('list',$list);
        $this->assign('pageHtml',$pageHtml);
        $this->display('index');
    }

//    public function add()
//    {
//        if(IS_POST){
//            $fix = D('Fix');//实例化模型
//            $data = $fix->create();
//            if($data){
////                $fix->create_time = time();
//                $fix->order = substr(str_shuffle('12346579879KLVNIOANGSOKLN'),0,11);
//                $id = $fix->add();
//                if($id){//返回数据的id
//                    $this->success('新增成功', U('index'));
//                    //记录行为
//                    action_log('update_channel', 'channel', $id, UID);
//                } else {
//                    $this->error('新增失败');
//                }
//            } else {
//                $this->error($fix->getError());
//            }
//        } else {
//            $pid = I('get.pid', 0);
//            //获取父导航
//            if(!empty($pid)){
//                $parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
//                $this->assign('parent', $parent);
//            }
//
//            $this->assign('pid', $pid);
//            $this->assign('info',null);
//            $this->meta_title = '新增导航';
//            $this->display('edit');
//        }
//    }
    public function add(){
        if(IS_POST){
            $Repair = D('Repair');
            $data = $Repair->create();
            if($data){
                $id = $Repair->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_channel', 'channel', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Repair->getError());
            }
        } else {
            $this->display('edit');
        }
    }

    public function edit($id=0)
    {
        if(IS_POST){
            $fix = D('Fix');//实例化模型
            $data = $fix->create();
            if($data){
                if($fix->save()){
                    //记录行为
                    action_log('update_channel', 'channel', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($fix->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Fix')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Channel')->where(array('id'=>$pid))->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display('edit');
        }
    }

    public function del()
    {
        $id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Fix')->where($map)->delete()){
            //记录行为
            action_log('update_fix', 'fix', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Fix')->where($map)->field('id,create_time')->order('create_time asc,id asc')->select();

            $this->assign('list', $list);
            $this->create_time = '添加时间排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Fix')->where(array('id'=>$value))->setField('id', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}