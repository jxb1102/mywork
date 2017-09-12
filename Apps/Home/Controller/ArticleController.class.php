<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    public function view($id){
    	$id=I('request.id');
    	
    	$articleinfo=M('Article')->find($id);
    	$articleinfo['catetitle']=M('Category')->where('id='.$articleinfo['cid'])->getField('title');

        $lwhere['lang']=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
        $lwhere['status']=1;
    	$catelist=M('Category')->where($lwhere)->order('sort desc')->select();
    	$this->assign('catelist',$catelist);
    	$this->assign('articleinfo',$articleinfo);
        $this->assign('title',$articleinfo['title'].' - ');
        $this->display();
    }
    public function bangarticle($id){
        $id=I('request.id');
        $articleinfo=M('Article')->find($id);
        $articleinfo['catetitle']=M('Category')->where('id='.$articleinfo['cid'])->getField('title');

        $lwhere['lang']=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
        $lwhere['status']=0;
        $catelist=M('Category')->where($lwhere)->order('sort desc')->select();
        $this->assign('catelist',$catelist);
        $this->assign('articleinfo',$articleinfo);
        $this->assign('title',$articleinfo['title'].' - ');
        $this->display();
    }
}