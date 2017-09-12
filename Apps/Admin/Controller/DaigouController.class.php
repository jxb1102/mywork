<?php
namespace Admin\Controller;
use Think\Controller;
class DaigouController extends AdminController {
    public function index(){
        $this->redirect('lists');
    }
    public function lists(){
    	$REQUEST    =   (array)I('request.');
        if ($REQUEST['status']) {
            $where['status']=$REQUEST['status'];
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                $map['dgsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['ydsn']=array('like','%'.$keyword.'%');
                $map['consignee']=array('like','%'.$keyword.'%');
                $map['tel']=array('like','%'.$keyword.'%');
                $map['areaname']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
    	$data=M('Daigou');
    	if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $data->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, $REQUEST);
        // if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        // }

        $p =$page->show();
    	$list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
    	foreach ($list as $key => $value) {
    		$list[$key]['user']=get_user($value['uid']);
            $list[$key]['dname']=M('Delivery')->where('id='.$value['deliveryid'])->getField('dname');
    	}
        $area=M('State')->select();
        $this->assign('area',$area);
        $this->assign('data',$REQUEST);
    	$this->assign('_page', $p? $p: '');
    	$this->assign('list',$list);
    	$this->display();
    }
    public function goods(){
        if (I('get.status')) {
            $where['status']=I('get.status');
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                $map['dgsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['ydsn']=array('like','%'.$keyword.'%');
                $map['consignee']=array('like','%'.$keyword.'%');
                $map['tel']=array('like','%'.$keyword.'%');
                $map['areaname']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        $data=M('DaigouGoods');
        if(I('request.r')){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $data->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, I('request.'));
        // if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        // }

        $p =$page->show();
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status,dgid desc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['dgsn']=M('Daigou')->where('id='.$value['dgid'])->getField('dgsn');
        }
        $this->assign('list',$list);
        $this->assign('data',I('request.'));
        $this->assign('_page', $p? $p: '');
        $this->display();
    }
    public function getdaigou($id){
        $data=M('Daigou')->find($id);
        $this->ajaxReturn($data);
    }
    public function getdelivery($id){
        $areaid=M('Daigou')->where('id='.$id)->getField('areaid');
        $data=M('Delivery');
        $list=$data->where('areaid='.$areaid)->select();
        $this->ajaxReturn($list);
    }
    public function edit(){
        $data=M('Daigou');
        if (!I('post.totalgoodsprice')) {
            $this->error('请填写物品金额');
        }
        if (!I('post.totalgoodsfreight')) {
            $this->error('请填写运费');
        }
        $areaname=M('State')->where('id='.I('post.areaid'))->getField('name');
        $list=array(
            'id'=>I('post.id'),
            'deliveryid'=>I('post.deliveryid'),
            'totalgoodsprice'=>I('post.totalgoodsprice'),
            'totalgoodsfreight'=>I('post.totalgoodsfreight'),
            'totalprice'=>I('post.totalgoodsprice')+I('post.totalgoodsfreight'),
            'remark'=>I('post.remark'),
            'edittime'=>time(),
            'consignee'=>I('post.consignee'),
            'tel'=>I('post.tel'),
            'zipcode'=>I('post.zipcode'),
            'address'=>I('post.address'),
            'areaid'=>I('post.areaid'),
            'areaname'=>$areaname,
        );
        if ($data->create($list)) {
            $id=$data->save();
            if (false!==$id) {
                $this->success('编辑成功',U('Daigou/lists'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function getgoods($id){
        $data=M('DaigouGoods')->find($id);
        $this->ajaxReturn($data);
    }
    public function editgoods(){
        $data=M('DaigouGoods');
        
        $info=$data->find(I('post.id'));
        if ($info['status']>1) {

            $goodsprice=$info['goodsprice'];
            $goodsnum=$info['goodsnum'];
        }else{
            if (!I('post.goodsprice')) {
            $this->error('请填写商品金额');
        }
        if (!I('post.goodsnum')) {
            $this->error('请填写数量');
        }
            $goodsprice=I('post.goodsprice');
            $goodsnum=I('post.goodsnum');
        }
        $list=array(
            'id'=>I('post.id'),
            'goodsprice'=>$goodsprice,
            'goodsnum'=>$goodsnum,
            'goodssize'=>I('post.goodssize'),
            'goodscolor'=>I('post.goodscolor'),
            'goodsremark'=>I('post.goodsremark'),
        );
        if ($data->create($list)) {
            $id=$data->save();
            if (false!==$id) {
                if ($info['status']==1) {
                    $dg=M('Daigou')->find($info['dgid']);
                    $goodslist=M('DaigouGoods')->where('dgid='.$info['dgid'])->select();
                    $jsgoodsprice=0;
                    foreach ($goodslist as $key => $value) {
                        $jsgoodsprice=$jsgoodsprice+sprintf('%.2f',$value['goodsprice']*$value['goodsnum']);
                    }
                    $totalgoodsprice=$jsgoodsprice;
                    $freight=$dg['totalgoodsfreight'];
                    $totalprice=$totalgoodsprice+$freight;
                    M('Daigou')->where('id='.$info['dgid'])->setField('totalgoodsprice',$totalgoodsprice);
                    M('Daigou')->where('id='.$info['dgid'])->setField('totalprice',$totalprice);
                }
                $this->success('编辑成功',U('Daigou/goods'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function querendaigou($id){
        $id=explode(',', $id);
        $id=array_filter($id);
        foreach ($id as  $row) {
            $data=array('status'=>3);
            M('Daigou')->where('id='.$row.' AND status=2')->setField($data);
            M('DaigouGoods')->where('dgid='.$row.' AND status=2')->setField($data);
        }
        $this->success('设置成功',U('Daigou/goods','status=3'));
    }
    public function addkdsn(){
        $id=I('post.id');
        $kdsn=trim(I('post.kdsn'));
        $have=M('Yubao')->where('kdsn="'.$kdsn.'"')->find();
        if ($have) {
            $this->error('快递单号已存在');
        }
        $info=M('DaigouGoods')->find($id);
        if ($info) {
            $data=array('kdsn'=>$kdsn,'status'=>4,'cgtime'=>time());
            $set=M('DaigouGoods')->where('id='.$id)->setField($data);
            $count1=M('DaigouGoods')->where('dgid='.$info['dgid'])->count();
            $count2=M('DaigouGoods')->where('dgid='.$info['dgid'].' AND status=4')->count();
            $dggoodslist=M('DaigouGoods')->where('dgid='.$info['dgid'].' AND status=4')->group('kdsn')->select();
            if ($count1==$count2) {
                $data1=array('status'=>4,'cgtime'=>time());
                M('Daigou')->where('id='.$info['dgid'])->setField($data1);
                $dginfo=M('Daigou')->find($info['dgid']);
                // $shenbao=sprintf('%.2f',($dginfo['totalgoodsprice']/C('MAIN_HUILV'))*C('MAIN_USD_HUILV'));
                $shenbao=$dginfo['totalgoodsprice'];
                $ydinfo=M('Yundan')->where('dgid='.$dginfo['id'])->find();
                if ($ydinfo) {
                    $this->error('订单已存在,请刷新网页');
                }
                $add=array(
                    'uid'=>$dginfo['uid'],
                    'uname'=>$dginfo['uname'],
                    'status'=>1,
                    'dgid'=>$dginfo['id'],
                    'addtime'=>time(),
                    'areaid'=>$dginfo['areaid'],
                    'areaname'=>$dginfo['areaname'],
                    'consignee'=>$dginfo['consignee'],
                    'tel'=>$dginfo['tel'],
                    'zipcode'=>$dginfo['zipcode'],
                    'address'=>$dginfo['address'],
                    'deliveryid'=>$dginfo['deliveryid'],
                    'remark'=>'代购单号：'.$dginfo['dgsn'].$dginfo['remark'],
                    'shenbao'=>$shenbao,
                );
                M('Yundan')->create($add);
                $ydid=M('Yundan')->add();
                M('Yundan')->where('id='.$ydid)->setField('sn',C('DD_PREFIX').sprintf("%06d",$ydid));
                M('Daigou')->where('id='.$dginfo['id'])->setField('ydid',$ydid);
                M('Daigou')->where('id='.$dginfo['id'])->setField('ydsn',C('DD_PREFIX').sprintf("%06d",$ydid));
                foreach ($dggoodslist as $key => $value) {
                    $ybadd=array(
                        'ydid'=>$ydid,
                        'uid'=>$value['uid'],
                        'uname'=>$value['uname'],
                        'kdsn'=>$value['kdsn'],
                        'kdname'=>'代购',
                        'bgname'=>'商家 '.$value['goodsseller'].' 商品',
                        'shuliang'=>1,
                        'addtime'=>time(),
                        'status'=>1,
                        'remark'=>'代购单号：'.$dginfo['dgsn'],
                        'dgid'=>$dginfo['id'],
                        'shenbao'=>$value['goodsprice']
                    );
                    M('Yubao')->create($ybadd);
                    M('Yubao')->add();
                }
                $result=array('data'=>$id,'status'=>1,'info'=>'成功');
                $this->ajaxReturn($result);
            }else{
                $result=array('data'=>$id,'status'=>1,'info'=>'成功');
                $this->ajaxReturn($result);
            }
        }else{
            $this->error('产品错误');
        }
        

    }
    public function editkdsn(){
        $id=I('post.id');
        $kdsn=trim(I('post.kdsn'));
        $info=M('DaigouGoods')->find($id);
        if ($info) {
            if ($info['kdsn']==$kdsn) {
                $this->error('快递单号无变化');
            }
            
            $dginfo=M('Daigou')->find($info['dgid']);
            if ($dginfo['status']>3) {
                $where1['kdsn']=$kdsn;
                $where1['dgid']=array('neq',$info['dgid']);
                $ybinfo1=M('Yubao')->where($where1)->find();
                if ($ybinfo1) {
                    $this->error('该单号已在其他代购单中');
                }
                $where2['kdsn']=$kdsn;
                $where2['dgid']=$info['dgid'];
                $ybinfo2=M('Yubao')->where($where2)->find();
                if ($ybinfo2) {
                    M('DaigouGoods')->where('id='.$id)->setField('kdsn',$kdsn);
                }else{
                    M('DaigouGoods')->where('id='.$id)->setField('kdsn',$kdsn);
                    $ybadd=array(
                        'ydid'=>$dginfo['ydid'],
                        'uid'=>$info['uid'],
                        'uname'=>$info['uname'],
                        'kdsn'=>$kdsn,
                        'kdname'=>'代购',
                        'bgname'=>'商家 '.$info['goodsseller'].' 商品',
                        'shuliang'=>1,
                        'addtime'=>time(),
                        'status'=>1,
                        'remark'=>'代购单号：'.$dginfo['dgsn'],
                        'dgid'=>$dginfo['id'],
                    );
                    M('Yubao')->create($ybadd);
                    M('Yubao')->add();
                }
                $dggoodsinfo=M('DaigouGoods')->where('kdsn="'.$info['kdsn'].'"')->find();
                if (empty($dggoodsinfo)) {
                    M('Yubao')->where('kdsn="'.$info['kdsn'].'"')->delete();
                }
                $result=array('data'=>$id,'status'=>1,'info'=>'成功');
                $this->ajaxReturn($result);
                
            }else{
                $have=M('Yubao')->where('kdsn="'.$kdsn.'"')->find();
                if ($have) {
                    $this->error('快递单号已存在');
                }
                M('DaigouGoods')->where('id='.$id)->setField('kdsn',$kdsn);
                $result=array('data'=>$id,'status'=>1,'info'=>'成功');
                $this->ajaxReturn($result);
            }
        }else{
            $this->error('产品错误');
        }
    }
    public function view(){
        $id=I('get.id');
        $dg=M('Daigou');
        $dginfo=$dg->find($id);
        if (empty($dginfo)) {
            $this->redirect('Daigou/lists');
        }
        $goodslist=M('DaigouGoods')->where('dgid='.$dginfo['id'])->select();
        $dginfo['delivery']=M('Delivery')->find($dginfo['deliveryid']);
        $this->assign('goodslist',$goodslist);
        $this->assign('ydinfo',$dginfo);
        $this->display();
    }
    public function ceshi(){
        $list=M('DaigouGoods')->group('dgid')->select();
        dump($list);
    }

    public function shenhedaigou(){
        $id=$_POST['id'];
        M('Daigou')->where('id='.$id)->setField('shenhe',1);
        $uid=M('Daigou')->where('id='.$id)->getField('uid');
        $userinfo=D('Users')->where('uid='.$uid)->find();
        $sendresult=send_mail($userinfo['email'],'','代购商品审核已通过','尊敬的'.$userinfo['username'].':<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您提交的代购订单已审核通过，请至神州代运网支付商品货款。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina官方网址是：www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。
            <br><br><br><br>Dear '.$userinfo['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Audit successfully,You could visit our website and pay for your goods-purchasing now.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Official ProcurementChina:www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
        if ($sendresult==true) {
                $this->success('审核成功,并且Email发送成功');
            }else{
                $this->success('审核成功,Email发送失败');
            }
    }
}