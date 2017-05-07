<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/26 0026
 * Time: 上午 11:37
 */

namespace Admin\Controller;


use Admin\Model\RepairModel;
use Think\Page;

class RepairController extends AdminController{
//    public function index(){
//        $list =  $this->lists('Repair');
////        var_dump($list);exit;
//        $this->assign('list', $list);
//        $this->meta_title = '报修管理';
//        $this->display('');
//    }
    public function index()
    {
        $repair = D('Repair');
        $count = $repair->count();
        $page = new Page($count,3);
        if($count>$page->listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $pageHtml = $page->show();

        $list = $repair->order('create_time')->limit($page->firstRow,$page->listRows)->select();
        int_to_string($list,['status'=>[1=>'进行中',0=>'已结束']]);
        $this->assign('list',$list);
        $this->assign('pageHtml',$pageHtml);
        $this->display('index');
    }




/**
 * 添加报修
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
public function add(){
    if(IS_POST){
        $Repair = D('Repair');
        $data = $Repair->create();
        if($data){
            $Str= "abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $str=str_shuffle($Str);
            $Repair->num=substr($str,0,15);
            $id = $Repair->add();
            if($id){
                $this->success('新增成功', U('index'));
                //记录行为
                action_log('update_repair', 'repair', $id, UID);
            } else {
                $this->error('新增失败');
            }
        } else {
            $this->error($Repair->getError());
        }
    } else {
        $this->assign('info',null);
        $this->meta_title = '新增报修';
        $this->display('edit');
    }
}

/**
 * 编辑报修
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
public function edit($id=0){
    if(IS_POST){
        $Repair = D('Repair');
        $data = $Repair->create();
        if($data){
            if($Repair->save()){
                //记录行为
                action_log('update_repair', 'repair', $data['id'], UID);
                $this->success('编辑成功', U('index'));
            } else {
                $this->error('编辑失败');
            }

        } else {
            $this->error($Repair->getError());
        }
    } else {
        /* 获取数据 */
        $info = M('Repair')->find($id);

        if(false === $info){
            $this->error('获取配置信息错误');
        }
        $this->assign('info', $info);
        $this->display('edit');
//        $id = I('get.id');
//        $list = M('Repair')->find($id);
//
//        $this->assign('info',$list);
//        $this->display('edit');
    }
}

/**
 * 删除报修
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
public function del(){
    $id = array_unique((array)I('id',0));

    if ( empty($id) ) {
        $this->error('请选择要操作的数据!');
    }

    $map = array('id' => array('in', $id) );
    if(M('Repair')->where($map)->delete()){
        //记录行为
        action_log('update_repair', 'repair', $id, UID);
        $this->success('删除成功');
    } else {
        $this->error('删除失败！');
    }
}

/**
 * 报修排序
 * @author huajie <banhuajie@163.com>
 */
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
        $list = M('Repair')->where($map)->field('id,title')->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->meta_title = '报修排序';
        $this->display();
    }elseif (IS_POST){
        $ids = I('post.ids');
        $ids = explode(',', $ids);
        foreach ($ids as $key=>$value){
            $res = M('Repair')->where(array('id'=>$value))->setField('sort', $key+1);
        }
        if($res !== false){
            $this->success('排序成功！');
        }else{
            $this->eorror('排序失败！');
        }
    }else{
        $this->error('非法请求！');
    }
}
    //详情
    public function detail($id){
        $detail = M('Repair')->find($id);
        $this->assign('detail',$detail);
        $this->display('detail');

    }
}