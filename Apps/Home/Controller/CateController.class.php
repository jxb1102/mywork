<?php
namespace Home\Controller;
use Think\Controller;
class CateController extends CommonController {
    public function lists(){
    	$id=I('request.id');
        $lwhere['lang']=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
        $lwhere['status']=1;
    	$catelist=M('Category')->where($lwhere)->order('sort desc')->select();
    	$cateinfo=M('Category')->find($id);
    	$this->assign('catelist',$catelist);
    	$this->assign('cateinfo',$cateinfo);
    	$art=M('Article');
    	$where['status']=1;
    	$where['cid']=$id;
        $where['lang']=$lwhere['lang'];
    	if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $art->where($where)->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $articlelist=$art->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('articlelist',$articlelist);
        $this->assign('title',$cateinfo['title'].' - ');
        $this->display();
    }
    public function gjzy(){
        $data=M('Category');
        $where['sort']=array('LT',10);
        $where['status']=1;
        $went=$data->field('id')->where($where)->find();
        $wherec['cid']=array('eq',$went['id']);
        $wherec['status']=1;
        $art=M('Article');
        $ant=$art->where($wherec)->order('sort desc')->limit(6)->select();
        $this->assign('ant',$ant);
        $this->display();
    }

    public function gwzn(){
        // $link=M('Link');
        // $where['lstate']=1;
        // $list=$link->where($where)->order('lid asc')->limit(5)->select();
        // $this->assign('list',$list);
        $data=M('Goodcate');
        $wherec['state']=1;
        $info=$data->field('id,title')->where($wherec)->order('sort desc,id asc')->select();
        foreach ($info as $key => $value) {
            $we['fid']=$value['id'];
            $we['state']=1;
            $good=M('Goodlist');
            //$mat=$good->where($we)->limit(4)->order('sort desc')->select();
            //$info[$key]['goods']=$mat;
            $mat=$good->where($we)->order('sort desc,start desc')->limit(12)->select();
            //dump($mat);
            $count=count($mat);
            if($count>0&&$count<=4){
                $mat1=array_slice($mat, 0,4);
                $info[$key]['goods'][]=$mat1;
            }if($count>4&&$count<=8) {
                $mat1=array_slice($mat,0,4);
                $mat2=array_slice($mat,4,4);
                $info[$key]['goods'][]=$mat1;
                $info[$key]['goods'][]=$mat2;
            }if ($count>8&&$count<=12) {
                $mat1=array_slice($mat,0,4);
                $mat2=array_slice($mat,4,4);
                $mat3=array_slice($mat, 8,4);
                $info[$key]['goods'][]=$mat1;
                $info[$key]['goods'][]=$mat2;
                $info[$key]['goods'][]=$mat3;
            }
             
        }
        //dump($info); 
        $this->assign('info',$info);
        $diancate=M('Diancate');
        $dw['status']=1;
        $dianlei=$diancate->where($dw)->order('id asc')->select();
        $this->assign('dianlei',$dianlei);
        $dianlist=M('Dianlist');
        $id=I('get.id');
        $listRows=12;
        $wh['status']=1;
        //dump($wh);
        if($id!=''){
            $wh['fid']=$id;
            $total= $dianlist->where($wh)->count();
            $page= new \Think\Page($total, $listRows, I('request.'));
            if($total>$listRows){
               $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            }
            $p =$page->show();
            $this->assign('_page', $p? $p: '');
            $diandd=$dianlist->where($wh)->limit($page->firstRow.','.$page->listRows)->order('sort desc,id desc')->select();
            $this->assign('diandd',$diandd);
        }else{
            $total= $dianlist->where($wh)->count();
            $page= new \Think\Page($total, $listRows, I('request.'));
            if($total>$listRows){
               $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            }
            $p =$page->show();
            $this->assign('_page', $p? $p: '');
            $diandd=$dianlist->where($wh)->limit($page->firstRow.','.$page->listRows)->order('sort desc')->select();
            //dump($diandd);
            $this->assign('diandd',$diandd);
        }
        $this->display();
    }

    public function active(){
        $data=M('Active');
        $listRows=6;
        $total= $data->where($where)->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $info=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc')->select();
        $this->assign('list',$info);
        $this->display();
    }
    public function bangzhu(){
        $ide=I('request.id');
        if($ide==''){
           $cw['status']=0;
           $cw['lang']=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
           $cateid=M('Category')->where($cw)->field('id')->order('sort desc')->find();
           $id=$cateid['id'];
        }else{
            $id=$ide;
        }
        $lwhere['lang']=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
        $lwhere['status']=0;
        $catelist=M('Category')->where($lwhere)->order('sort desc')->select();
        $cateinfo=M('Category')->find($id);
        $this->assign('catelist',$catelist);
        $this->assign('cateinfo',$cateinfo);
        $art=M('Article');
        $where['status']=1;
        $where['cid']=$id;
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $art->where($where)->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $articlelist=$art->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('articlelist',$articlelist);
        $this->assign('title',$cateinfo['title'].' - ');
        $this->display();
    }

    public function huansuan(){
        $this->display();
    }
	public function gusuan(){
        $usertype=D('Usertype');
        $userlist=$usertype->select();
        $this->assign('userlist',$userlist);
        $state=M('State');
        $statelist=$state->select();
        $this->assign('statelist',$statelist);
        $w=(double)I('post.weight');
        $money=(int)I('post.money');
        //$where['utype']=I('post.utype');
        $where['areaid']=I('post.areaid');
        $d=M('Delivery')->where($where)->select();
        foreach ($d as $key => $value) {
            $d[$key]['money']=$money;
            if ($w>0&&$w<=$value['first_weight']) {
                $yunmoney=$value['first_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+$money+$fwmoney;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney; 
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['first_weight']&&$w<=$value['third_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['second_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['second_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['third_weight']&&$w<=$value['fourth_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['third_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['third_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['fourth_weight']&&$w<=$value['fifth_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fourth_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fourth_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['fifth_weight']&&$w<=$value['sixth_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fifth_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['fifth_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['sixth_weight']&&$w<=$value['seventh_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['sixth_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['sixth_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
            if ($w>$value['seventh_weight']) {
                $yunmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['seventh_fee'];
                $fwmoney=$yunmoney*$value['fwfv']+$value['baoguan_fee']+$value['ranyou_fee'];
                $zongmoney=$value['first_fee']+ceil(($w-$value['first_weight'])/$value['xu_weight'])*$value['seventh_fee']+$fwmoney+$money;
                $d[$key]['zongmoney']=$zongmoney;
                $d[$key]['yunmoney']=$yunmoney;
                $d[$key]['fwmoney']=$fwmoney;
            };
        }
        // dump($d);
            $this->assign('dinfo',$d);
	    $this->display();	
	}
	public function gjyf(){
        $zhou=M('Zhou');
        $zhoulist=$zhou->where('status=1')->order('id asc')->select();
        foreach ($zhoulist as $key => $value) {
            $zid=$value['id'];
            $state=M('State');
            $wh['zid']=$zid;
            $wh['status']=1;
            $statelist=$state->where($wh)->select();
            $zhoulist[$key]['list']=$statelist;
        }
       // dump($zhoulist);
        $this->assign('list',$zhoulist);
        $areaid=$_GET['areaid'];
        if($areaid!=''){
            $st=$state->where('id='.$areaid)->find();
            $this->assign('st',$st);
            $del=M('Delivery');
            $dellist=$del->where('areaid='.$areaid)->select();
            //dump($dellist);
            $this->assign('dellist',$dellist);
        }
        $this_zid=D("State")->where("id=".$_GET['areaid'])->getField("zid");
         if(empty($this_zid)){
            $this_zid=1;
         }
        $this->assign("this_zid",$this_zid);   
	    $this->display();	
	}
	public function dgyanshi(){
		  $this->display();
		}

    public function zyyanshi(){
        $this->display();
    }


}