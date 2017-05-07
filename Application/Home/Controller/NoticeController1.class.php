<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27 0027
 * Time: 下午 12:12
 */

namespace Home\Controller;


class NoticeController extends HomeController{

    public function index(){
        $i = 3;
        $articles = M('document')->order('create_time desc')->limit(0,$i)->select();
//        var_dump($articles);exit;
        foreach($articles as &$article){
            $img = M('picture')->where(['id'=>($article['cover_id'])])->select();
            $article['path'] = $img[0]['path'];
        }
//                var_dump($articles);exit;

        $this->assign('articles',$articles);
        $this->display('index');
    }

    public function detail(){
        $article = M('document')->find(I('notice_id'));
//        var_dump($article);exit;

        $content = M('document_article')->find($article['id']);
        $user = M('member')->find($article['uid']);
        $article['content'] = $content['content'];
        $article['uname'] = $user['nickname'];
//        var_dump($article['uname']);exit;


        $this->assign('article',$article);
        //var_dump($article);exit;
        $this->display('detail');
    }
}