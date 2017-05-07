<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2017/4/27
 * Time: 10:31
 */

namespace Home\Controller;


use Admin\Model\RepairModel;

class RepairController extends HomeController
{
    public function add(){
        //判断是否登录 登录了就可以报修
        if(is_login()){
            if(IS_POST){
                $Repair = new RepairModel();
                //验证加载数据
                $data = $Repair->create();
                if($data){
                    $id = $Repair->add();
                    if($id){
                        $this->success('新增成功', U('Index/index'));
//                        $this->success('新增成功');
                        //记录行为
                        action_log('update_repair', 'repair', $id, UID);
                    } else {
                        $this->error('新增失败');
                    }
                } else {
                    $this->error($Repair->getError());
                }
            } else {
                $this->display('online');
            }
        } else {//没有登录 跳转到登录页面
            $this->redirect('User/login');
        }
    }
}