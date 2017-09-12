<?php
namespace Admin\Controller;
use Think\Controller;
class SettingController extends AdminController {
    public function index(){
        if (IS_POST) {
            $config=M('Config');
            foreach (I('post.') as $k => $v) {
                $config->where('name="'.$k.'"')->setField('value',$v);
            }
            $this->success('更新成功');
        }else{
            $this->display();
        }
        
    }
    public function cangku(){
        if (IS_POST) {
            $config=M('Config');
            foreach (I('post.') as $k => $v) {
                $config->where('name="'.$k.'"')->setField('value',$v);
            }
            $this->success('更新成功');
        }else{
            $this->display();
        }
    }
    public function sidenav(){
    	$side=M('Sidenav');
    	if (IS_POST) {
            if (!I('post.title')) {
                $this->error('请填写名称');
            }
            if (!I('post.uri')) {
                $this->error('请填写链接地址');
            }
            if (!I('post.sort')) {
                $this->error('请填写排序');
            }
    		$data=array(
    			'title'=>I('post.title'),
    			'uri'=>I('post.uri'),
    			'pid'=>I('post.pid'),
    			'sort'=>I('post.sort'),
    			'status'=>I('post.status'),
    		);
    		if ($side->create($data)) {
    			$id=$side->add();
    			if ($id>0) {
    				$this->success('添加成功',U('Setting/sidenav'));
    			}else{
    				$this->error('添加失败');
    			}
    		}else{
    			$this->error('数据创建失败');
    		}
    	}else{
    	$id=I('get.id');
    	if ($id) {
    		$info=$side->find($id);
    		$this->assign('info',$info);
    	}
    	$where['pid']=0;
    	$sidelist=$side->where($where)->order('sort')->select();
    	foreach ($sidelist as $key => $value) {
    		$sidelist[$key]['children']=$side->where('pid='.$value['id'])->order('sort')->select();
            foreach ($sidelist[$key]['children'] as $k => $v) {
                $sidelist[$key]['children'][$k]['son']=$side->where('pid='.$v['id'])->order('sort')->select();
            }
    	}
    	$this->assign('sidelist',$sidelist);
    	$this->display();
    }
}
	public function sidenavedit(){

		$side=M('Sidenav');
		$data=array(
				'id'=>I('post.id'),
    			'title'=>I('post.title'),
    			'uri'=>I('post.uri'),
    			'pid'=>I('post.pid'),
    			'sort'=>I('post.sort'),
    			'status'=>I('post.status'),
    		);
    		if ($side->create($data)) {
    			$id=$side->save();
    			if ($id!==false) {
    				$this->success('编辑成功',U('Setting/sidenav'));
    			}else{
    				$this->error('编辑失败');
    			}
    		}else{
    			$this->error('数据创建失败');
    		}
	}
    public function sidenavdel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('信息错误');
        }
        if(false!==M('Sidenav')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/sidenav'));
        }else{
            $this->error('删除错误');
        }
    }
    public function state(){
        $data=M('State');
        $id=I('get.id');
        if ($id) {
                $info=$data->find($id);
                $this->assign('info',$info);
            }
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $zhou=M('Zhou');
        $zhoulei=$zhou->select();
        $this->assign('zhoulei',$zhoulei);
        $total= $data->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');

        $list=$data->limit($page->firstRow.','.$page->listRows)->select();
        //dump($list);
        foreach ($list as $key => $value) {
            $zid=$value['zid'];
            $zh=$zhou->where('id='.$zid)->find();
            $list[$key]['zname']=$zh['name'];
        }
        //dump($list);
        $this->assign('list',$list);
        $this->display();
    }
    public function stateadd(){
        $data=M('State');
        $add=array(
            'name'=>I('post.name'),
            'status'=>I('post.status'),
            'zid'=>I('post.zid')
        );
        if ($data->create($add)) {
            $id=$data->add();
            if ($id>0) {
                $this->success('国家添加成功',U('Setting/state'));
            }else{
                $this->error('国家添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function stateedit(){
        $data=M('State');
        $save=array(
            'id'=>I('post.id'),
            'name'=>I('post.name'),
            'status'=>I('post.status'),
            'zid'=>I('post.zid')
        );
        if ($data->create($save)) {
            if (false!==$data->save()) {
                $this->success('国家编辑成功',U('Setting/state'));
            }else{
                $this->error('国家编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function statedel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        if(false!==M('State')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/state'));
        }else{
            $this->error('删除失败');
        }
    }
    public function peisong(){
        $data=M('Delivery');
        $id=I('get.id');
        if ($id) {
                $info=$data->find($id);
                //dump($info['tiji']);
                $this->assign('info',$info);
        }
        if (I('request.keyword')) {
            $where['dname']=array('like','%'.I('request.keyword').'%');
            $where['utypename']=array('like','%'.I('request.keyword').'%');
            $where['areaname']=array('like','%'.I('request.keyword').'%');
            $where['_logic']="or";
        }
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $data->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');

        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        //$list=$data->select();
        foreach ($list as $key => $value) {
            $list[$key]['utype']=M('Usertype')->where('id='.$value['utype'])->find();
        }
        $state=M('State')->order('name')->select();
        $this->assign('state',$state);
        $usertype=M('Usertype')->where('status=1')->select();
        $geshi=M('DeliveryGeshi')->where('status=1')->order('id desc')->select();
        $this->assign('geshi',$geshi);
        $this->assign('utype',$usertype);
        $this->assign('list',$list);
        $this->display();
    }
    public function peisongadd(){
        $data=M('Delivery');
        if (I('post.areaid')) {
            $state=M('State')->find(I('post.areaid'));
        }
        $add=array(
            'dname'=>I('post.dname'),
            'uri'=>I('post.uri'),
            'daima'=>I('post.daima'),
            'areaid'=>I('post.areaid'),
            'areaname'=>$state['name'],
            'status'=>I('post.status'),
            'geshiid'=>I('post.geshiid'),
            
        );
        if ($addd=$data->create()) {
            $addd['areaname']=$state['name'];
            $addd['geshiid']=I('post.geshiid');
			$addd['tese']=$_POST['tese'];
            $id=$data->add($addd);
            if ($id>0) {
                $this->success('配送方式添加成功',U('Setting/peisong'));
            }else{
                $this->error('配送方式添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function peisongedit(){
        $data=M('Delivery');
        if (I('post.areaid')) {
            $state=M('State')->find(I('post.areaid'));
        }
        $save=array(
            'id'=>I('post.id'),
            'dname'=>I('post.dname'),
            'uri'=>I('post.uri'),
            'daima'=>I('post.daima'),
            'areaid'=>I('post.areaid'),
            'areaname'=>$state['name'],
            'status'=>I('post.status'),
            'utype'=>I('post.utype'),
            'utypename'=>$utype['name'],
        );
        if ($savee=$data->create()) {
            $savee['areaname']=$state['name'];
            $savee['geshiid']=I('post.geshiid');
			$savee['tese']=$_POST['tese'];
            if (false!==$data->save($savee)) {
                $this->success('配送方式编辑成功',U('Setting/peisong'));
            }else{
                $this->error('配送方式编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function peisongdel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        if(false!==M('Delivery')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/peisong'));
        }else{
            $this->error('删除失败');
        }
    }
    public function geshi(){
        $data=M('DeliveryGeshi');
        if (I('request.keyword')) {
            $where['title']=array('like','%'.I('request.keyword').'%');
        }
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $data->where($where)->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($list as $key => $value) {
            $fslist=M('Delivery')->where('geshiid='.$value['id'])->select();
            $delist=array();
            foreach ($fslist as $k => $v) {
                $delist[$k]=$v['areaname'].'-'.$v['dname'];
            }
            $delist=implode(' , ', $delist);
            $list[$key]['delist']=$delist;
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function addgeshi(){
        if (IS_POST) {
            if (!I('post.title')) {
                $this->error('标题必须填写');
            }
            $add=array(
                'title'=>I('post.title'),
                'status'=>I('status'),
                'content'=>$_POST['content'],
                'addtime'=>time(),
            );
            if (M('DeliveryGeshi')->create($add)) {
                if (M('DeliveryGeshi')->add()) {
                    $this->success('添加成功',U('geshi'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{
           $this->display(); 
        }
        
    }
    public function editgeshi(){
        if (IS_POST) {
            if (!I('post.title')) {
                $this->error('标题必须填写');
            }
            $add=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'status'=>I('status'),
                'content'=>$_POST['content'],
                'addtime'=>time(),
            );
            if (M('DeliveryGeshi')->create($add)) {
                if (M('DeliveryGeshi')->save()) {
                    $this->success('编辑成功',U('geshi'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{
        $id=I('get.id');
        if ($id) {
            $info=M('DeliveryGeshi')->find($id);
            $this->assign('info',$info);
        }else{
            $this->error('没有该运单格式');
        };
           $this->display('addgeshi'); 
        }
    }
    public function delgeshi(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('信息错误');
        }
        if(false!==M('DeliveryGeshi')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/geshi'));
        }else{
            $this->error('删除错误');
        }
    }
        public function lang(){
        $data=M('Lang');
        $id=I('get.id');
        if ($id) {
                $info=$data->find($id);
                $this->assign('info',$info);
            }
        $list=$data->order('name')->select();
        $this->assign('list',$list);
        $this->display();
    }
    public function langadd(){
        $data=M('Lang');
        $add=array(
            'name'=>I('post.name'),
            'type'=>I('post.type'),
            'status'=>I('post.status'),
        );
        if ($data->create($add)) {
            $id=$data->add();
            if ($id>0) {
                $this->success('语言添加成功',U('Setting/lang'));
            }else{
                $this->error('语言添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function langedit(){
        $data=M('Lang');
        $save=array(
            'id'=>I('post.id'),
            'name'=>I('post.name'),
            'type'=>I('post.type'),
            'status'=>I('post.status'),
        );
        if ($data->create($save)) {
            if (false!==$data->save()) {
                $this->success('语言编辑成功',U('Setting/lang'));
            }else{
                $this->error('语言编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function langdel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        if(false!==M('Lang')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/lang'));
        }else{
            $this->error('删除失败');
        }
    }

    public function diyu(){
        $diyu=M('Zhou');
        $id=I('get.id');
        if ($id) {
                $info=$diyu->find($id);
                $this->assign('info',$info);
            }
        $diyulist=$diyu->select();
        $this->assign('list',$diyulist);
        $this->display();
    }

    public function diyuadd(){
        $data=M('Zhou');
        $add=array(
            'name'=>I('post.name'),
            'status'=>I('post.status'),
        );
        if ($data->create($add)) {
            $id=$data->add();
            if ($id>0) {
                $this->success('地域添加成功',U('Setting/diyu'));
            }else{
                $this->error('地域添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function diyuedit(){
       $data=M('Zhou');
        $save=array(
            'id'=>I('post.id'),
            'name'=>I('post.name'),
            'status'=>I('post.status'),
        );
        if ($data->create($save)) {
            if (false!==$data->save()) {
                $this->success('国家编辑成功',U('Setting/diyu'));
            }else{
                $this->error('国家编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        } 
    }

    public function diyudel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        if(false!==M('Zhou')->where('id='.$id)->delete()){
            $this->success('删除成功',U('Setting/diyu'));
        }else{
            $this->error('删除失败');
        }
    }
}