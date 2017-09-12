<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
     public function _initialize(){
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
        cookie('anonymous')?cookie('anonymous'):cookie('anonymous',md5(session_id()),60*60*24*3);
        $anonymous=cookie('anonymous');
        if(is_login()){
            $this->assign('user',session('user_auth'));
            //dump(session('user_auth'));
            $cartcount=M('Cart')->where('uid='.session('user_auth.uid'))->count();
            $this->assign('cartcount',$cartcount?$cartcount:0);
        }else{
            $cartcount=M('Cart')->where('anonymous="'.$anonymous.'"')->count();
            $this->assign('cartcount',$cartcount?$cartcount:0);
        }
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
         $cartcount=M('Cart')->where('uid='.session('user_auth.uid'))->count();
         $this->assign('cartcount',$cartcount?$cartcount:0);

    }
    public function getpeisong(){
    	$areaid=I('request.areaid');
        $where['areaid']=$areaid;
        if (is_login()) {
            $user=M('Users')->find(session('user_auth.uid'));
            if ($user['utype']>0) {
                $utype=get_usertypeid($user['utype']);
            }else{
                $utype=getusertypeid($user['score']);
            }
            $where['utype']=$utype;
        }else{
            $where['utype']=1;
        }
    	$peisong=M('Delivery')->where($where)->select();
    	if ($peisong) {

    		$info=array(
    			'data'=>$peisong,
    			'status'=>1,
    		);
    		$this->ajaxReturn($info);
    	}else{
			$info=array(
    			'info'=>L('country_noshipping'),
    			'status'=>0,
    		);
    		$this->ajaxReturn($info);
    	}
    	
    }
    public function getpeisonginfo(){
    	$id=I('request.id');
    	$peisong=M('Delivery')->find($id);
    	if ($peisong) {
    		$info=array(
    			'data'=>$peisong,
    			'status'=>1,
    		);
    		$this->ajaxReturn($info);
    	}else{
			$info=array(
    			'info'=>L('country_noshipping'),
    			'status'=>0,
    		);
    		$this->ajaxReturn($info);
    	}
    }
    public function getyunfei(){
        $w=(double)I('post.weight');
        $money=(int)I('post.money');
        $where['utype']=I('post.utype');
        $where['areaid']=I('post.areaid');
        $d=M('Delivery')->where($where)->select();
        foreach ($d as $key => $value) {
        	if ($w>0&&$w<=$value['first_weight']) {
    			$zongmoney=$value['first_fee']+$money*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    			
    		}
    		if ($w>$value['first_weight']&&$w<=$value['third_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['second_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['second_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$d['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;

    		}
    		if ($w>$value['third_weight']&&$w<=$value['fourth_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['third_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['third_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    		}
    		if ($w>$value['fourth_weight']&&$w<=$value['fifth_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fourth_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fourth_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    		}
    		if ($w>$value['fifth_weight']&&$w<=$value['sixth_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fifth_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fifth_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    		}
    		if ($w>$value['sixth_weight']&&$w<=$value['seventh_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['sixth_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['sixth_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    		}
    		if ($w>$value['seventh_weight']) {
    			$zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['seventh_fee']+$value['baoguan_fee']+$value['ranyou_fee']+$money*$value['fwfv'];
    			$d[$key]['zongmoney']=$zongmoney;
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['seventh_fee'];
                $d[$key]['yunmoney']=$yunmoney;
                $fwmoney=$money*$value['fwfv'];
                $d[$key]['fwmoney']=$fwmoney;
                $d[$key]['money']=$money;
    		}
        }
            dump($d);
            $this->assign('dinfo',$d);
            $this->display('Cate:gusuan');
    }

    public function indexgetyunfei(){
        $chang=(int)I('post.chang');
        $kuan=(int)I('post.kuan');
        $gao=(int)I('post.gao');
        $weight=(double)I('post.weight');
        $w=(double)I('post.weight');
        $id=I('post.did');
        $d=M('Delivery')->find($id);
        $voweight=($chang*$kuan*$gao)/6000;
        if ($weight>$voweight) {
         $w=$weight;
        }else{
         $w=$voweight;
        };
        if ($w>0&&$w<=$d['first_weight']) {
            $freight=$d['first_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['first_weight']&&$w<=$d['third_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['second_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['third_weight']&&$w<=$d['fourth_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['third_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['fourth_weight']&&$w<=$d['fifth_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['fourth_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['fifth_weight']&&$w<=$d['sixth_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['fifth_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['sixth_weight']&&$w<=$d['seventh_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['sixth_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
        if ($w>$d['seventh_weight']) {
            $freight=$d['first_fee']+ceil(($w-$d['first_weight'])/$d['xu_weight'])*$d['seventh_fee']+$d['baoguan_fee']+$d['ranyou_fee'];
            $this->ajaxReturn($freight);
        };
    }

}