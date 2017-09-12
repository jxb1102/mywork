<?php
namespace Home\Controller;
use Think\Controller;
class UcommonController extends Controller {
     public function _initialize(){
        
        if(is_login()){// 还没登录 跳转到登录页面
        	$langlist=M('Lang')->where('status=1')->select();
        
        $langcoo=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
        if (strpos($langcoo,'zh')!==false) {
            $this->assign('langname','简体中文');
        }
        if (strpos($langcoo,'en')!==false) {
            $this->assign('langname','English');
        }
        if (strpos($langcoo,'ru')!==false) {
            $this->assign('langname','Русский');
        }
        $this->assign('langlist',$langlist);
        $navlist=M('Category')->field('id,title,lang,sort')->where('status=1 AND pid=0 AND lang="'.$langcoo.'"')->order('sort desc')->select();
        $this->assign('navlist',$navlist);
        $cate=M('Category');
        $whe['status']=0;
        $whe['lang']=$langcoo;
        $groy=$cate->field('id,title')->where($whe)->limit(5)->order('sort desc')->select();
         foreach ($groy as $key => $value) {
             $art=M('Article');
             $wh['cid']=$value['id'];
             $mat=$art->field('id,title')->where($wh)->limit(4)->order('sort desc')->select();
             $groy[$key]['article']=$mat;
         }
         $this->assign('cateaa',$groy);
        	$user=M('Users')->find(session('user_auth.uid'));
            $this->assign('user',$user);
        $cartcount=M('Cart')->where('uid='.session('user_auth.uid'))->count();
        $this->assign('cartcount',$cartcount?$cartcount:0);
        }else{
            $url=base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $this->redirect('Public/login?url='.$url);
        }
        $ydb=M('YundanBaoguo');
         $yw['uid']=session('user_auth.uid');
         $yw['status']=array('neq',6);
         $uc=$ydb->where($yw)->select();
         //dump($uc);
         $uct=count($uc);
         $this->assign('uct',$uct);
         $daigou=M('Daigou');
         $dw['uid']=session('user_auth.uid');
         $dw['status']=array('neq',6);
         $dc=$daigou->where($dw)->select();
         $udc=count($dc);
         $this->assign('udc',$udc);
         $uz=$uct+$udc;
         $this->assign('uz',$uz);
         $nwhere['type']='notice';
         $nwhere['uid']=0;
        $notice=M('Msg')->where($nwhere)->select();
        $ncount=count($notice);
        //dump($ncount);
        $this->assign('ncount',$ncount);
    }
}