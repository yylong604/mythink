<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/26 0026
 * Time: 上午 11:37
 */

namespace Admin\Controller;


class RepairController extends AdminController{
    public function index(){
        $pid = i('get.pid', 0);
        /* 获取报修列表 */
        $map  = array('status' => array('gt', -1), 'pid'=>$pid);

        $list = M('Repair')->where($map)->order('sort asc,id asc')->select();
//        var_dump($list);exit;
        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->meta_title = '报修管理';
        $this->display('');
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
            $Repair->create_time =time();
            $Repair->update_time =time();
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
        $pid = i('get.pid', 0);
        //获取父导航
        if(!empty($pid)){
            $parent = M('Repair')->where(array('id'=>$pid))->field('title')->find();
            $this->assign('parent', $parent);
        }

        $this->assign('pid', $pid);
        $this->assign('info',null);
        $this->meta_title = '新增报修';
        $this->display('edit');
    }
}

/**
 * 编辑报修
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
public function edit($id = 0){
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
        $info = array();
        /* 获取数据 */
        $info = M('Repair')->find($id);

        if(false === $info){
            $this->error('获取配置信息错误');
        }

        $pid = i('get.pid', 0);
        //获取父导航
        if(!empty($pid)){
            $parent = M('Repair')->where(array('id'=>$pid))->field('title')->find();
            $this->assign('parent', $parent);
        }

        $this->assign('pid', $pid);
        $this->assign('info', $info);
        $this->meta_title = '编辑报修';
        $this->display();
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
}