<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 空模块，主要用于显示404页面，请不要删除
 */
class EmptyController extends HomeController{
    //没有任何方法，直接执行HomeController的_empty方法
    //请不要删除该控制器

    public function index(){
        $category_name= strtolower(CONTROLLER_NAME);

        $category_id = M('Category')->getFieldByName($category_name,'id');
//        var_dump($category_id);exit;

        //查询当前页的数据
        $document = D("Document");
        $document_article = D("Document_article");

        $article = $document->where(['category_id'=>$category_id])->page(I('p',1),C('article_ROWS'))->select();
        $res = $document_article->where(['id'=>$article[0]['id']])->select();
        $content=$res[0]['content'];
        //选择视图
//        var_dump($article[0]['id']);exit;
//        var_dump($content);exit;

        foreach($article as &$v){
            $v['create_time'] = date('Y-m-d H:i',$v['create_time']);
            $v['path'] = get_cover($v['cover_id'],"path");
//            $v['url'] = U('index',"id=".$v['id']);
            $v['url'] = U('detail',['id'=>$v['id']]);
            $v['content']=$content;
        }
//        $this->assign('page',$pageHtml);

        if(IS_AJAX){//判断是否是ajax请求
            if(empty($article)){
                $this->error('没有数据');
            }else{
                $this->success($article);
            }
        }
//        var_dump($category_name);exit;
        $this->assign('article',$article);
        $this->assign('content',$content);
//        <switch name="User.level">
//        <case value="1">value1</case>
//        <case value="2">value2</case>
//        <default />default
//    </switch>
        if($category_name == 'service'){
//            echo 222;exit;
            $this->display('Service/index');
        }
        if($category_name == 'notice'){
//            echo 222;exit;
            $this->display('Notice/index');
        }
        if($category_name == 'business'){
//            echo 111;exit;
            $this->display('Business/index');
        }
        if($category_name == 'activity'){
//            echo 111;exit;
            $this->display('Activity/index');
        }
        if($category_name == 'house'){
//            echo 111;exit;
            $this->display('House/zushou');
        }
    }

    /**
     *
     */
    public function detail($id=0){
        //判断缓存是否存在
//        $key = MODULE_NAME.CONTROLLER_NAME.ACTION_NAME.$id;
//        S(array(
//                'type'=>'redis',
//                'host'=>'127.0.0.1',
//                'port'=>'6379',
//                'prefix'=>'onethink',
//                'expire'=>60)
//        );
//        $detail = S($key);
//        if(!$detail){
            $document = M('Document');
            $article = $document->alias('d')
                ->field("m.nickname,a.content,d.*")
                ->join("__MEMBER__ as m on m.uid=d.uid")
                ->join("left join onethink_document_article as a on a.id=d.id")
                ->where("d.id=".$id)
                ->find();
//        var_dump($article['nickname']);exit;
//        var_dump($article);exit;

            //保存到缓存中
//            S($key,$detail,60*60);
//        }

        $this->assign('article',$article);
        $this->display('Notice/detail');
    }
}
