<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends UcommonController {
    public function index(){
    	$ddlist=M('Yundan')->where('uid='.session('user_auth.uid').' AND status=3')->select();
    	$this->assign('ddlist',$ddlist);
        $dglist=M('Daigou')->where('uid='.session('user_auth.uid').' AND payed=0')->select();
        //dump($dglist);
        $this->assign('dglist',$dglist);
        $mwhere['uid']=session('user_auth.uid');
        $mwhere['reply']="";
        $msg=M('Msg')->where($mwhere)->select();
        $msgcount=count($msg);
        $this->assign('msgcount',$msgcount);
        $this->display();
    }
    public function yubaoadd(){
        $ybcart=M('YubaoCart')->where('uid='.session('user_auth.uid'))->select();
        foreach ($ybcart as $key => $value) {
            $ybid=M('Yubao')->where('kdsn="'.$value['kdsn'].'"')->find();
            if ($ybid) {
                $ybcart[$key]['daoda']=1;
            }else{
                $ybcart[$key]['daoda']=0;
            }
        }
    	$this->assign('ybcart',$ybcart);
        $address=M('Address')->where('uid='.session('user_auth.uid').' AND def=1')->find();
        $this->assign('address',$address);
        $user=M('Users')->find(session('user_auth.uid'));
        if ($user['utype']>0) {
            $utype=get_usertypeid($user['utype']);
        }else{
            $utype=getusertypeid($user['score']);
        }
        $delivery=M('Delivery')->where('areaid='.$address['areaid'])->select();
        $this->assign('delivery',$delivery);
        $addresslist=M('Address')->where('uid='.session('user_auth.uid'))->order('id desc')->select();
        $this->assign('addresslist',$addresslist);
        $state=M('State')->select();
        $this->assign('state',$state);
        $this->assign('title',L('daiyun_order').' - ');
        $this->display();
    }
    public function yubaoinsert(){
        $addressid=I('post.addressid');
        $deliveryid=I('post.deliveryid');
        $remark=I('post.remark');
        $isxian=I('post.isxian');
        $cartlist=M('YubaoCart')->where('uid='.session('user_auth.uid'))->select();
        if (empty($cartlist)) {
            $this->error('预报包裹为空,请填写预报加入订单');
        }
        if (empty($addressid)) {
            $this->error(L('select_address'));
        }
        if (empty($deliveryid)) {
            $this->error(L('select_shipping_method'));
        }

        foreach ($cartlist as $key => $value) {
            $checkwhere['kdsn']=$value['kdsn'];
            $checkyubao=M('Yubao')->where($checkwhere)->find();
            if ($checkyubao) {
                if ($checkyubao['ydid']>0) {
                    $kdsns[$key]=$checkyubao['kdsn'];
                    //$this->error('快递单号 '.$checkyubao['kdsn'].' 已存在并且已在其他订单中。');
                }
            }

        }
        if ($kdsns) {
            $kdsnscount=count($kdsns);
            $kdsns=implode(' , ', $kdsns);

            $this->error('快递单号 '.$kdsns.' 等 '.$kdsnscount.' 个包裹已存在其他订单');
        }
        $address=M('Address')->find($addressid);
        $add=array(
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'status'=>1,
            'addtime'=>time(),
            'edittime'=>time(),
            'areaid'=>$address['areaid'],
            'areaname'=>$address['areaname'],
            'consignee'=>$address['consignee'],
            'tel'=>$address['tel'],
            'zipcode'=>$address['zipcode'],
            'address'=>$address['address'],
            'deliveryid'=>$deliveryid,
            'remark'=>$remark,
            'isxian'=>$isxian
        );
        if (M('Yundan')->create($add)) {
            $id=M('Yundan')->add();
            if ($id>0) {
                M('Yundan')->where('id='.$id)->setField('sn',C('DD_PREFIX').sprintf("%07d",$id));
                foreach ($cartlist as $key => $value) {
                    $ybadd=array(
                        'ydid'=>$id,
                        'uid'=>session('user_auth.uid'),
                        'uname'=>session('user_auth.username'),
                        'kdsn'=>$value['kdsn'],
                        'kdname'=>$value['kdname'],
                        'bgname'=>$value['bgname'],
                        'shenbao'=>$value['shenbao'],
                        'bgleixing'=>$value['bgleixing'],
                        'chaibao'=>$value['chaibao'],
                        'addtime'=>time(),
                        'edittime'=>time(),
                        'remark'=>$value['remark'],
                    );
                    $allshenbao+=$value['shenbao'];
                    if ($listt=M('Yubao')->create($ybadd)) {
                        $yubaoinfo=M('Yubao')->where('kdsn="'.$value['kdsn'].'"')->find();
                        
                        if ($yubaoinfo) {
                            $listt['id']=$yubaoinfo['id'];
                            $listt['status']=$yubaoinfo['status'];
                            $ybid=M('Yubao')->save($listt);

                        }else{
                            $listt['status']=1;
                           $ybid=M('Yubao')->add($listt); 
                        }
                        if ($ybid!==false) {
                            
                        }else{
                            M('Yundan')->where('id='.$id)->delete();
                            M('Yubao')->where('ydid='.$id)->delete();
                            $this->error('包裹添加失败');
                        }
                    }else{
                        M('Yundan')->where('id='.$id)->delete();
                        M('Yubao')->where('ydid='.$id)->delete();
                        $this->error('数据创建失败');
                    }
                }
                M('Yundan')->where('id='.$id)->setField('shenbao',$allshenbao);
                M('YubaoCart')->where('uid='.session('user_auth.uid'))->delete();
                $this->success('请至订单列表查询包裹最新状态及支付国际运费',U('User/dingdan'));
            }else{
                $this->error('订单添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function ybcartinsert(){
        $kdname=I('post.kdname');
        $kdsn=I('post.kdsn');
        $shenbao=I('post.shenbao');
        $bgname=I('post.bgname');
        $remark=I('post.remark');
        $chaibao=I('post.chaibao');
        $bgleixing=I('post.bgleixing');
        foreach ($kdname as $key => $value) {
            $tianjia[$key]['kdname']=$value;
        }
        foreach ($kdsn as $key => $value) {
            $tianjia[$key]['kdsn']=trim($value);
            $tianjia[$key]['uid']=session('user_auth.uid');
            $tianjia[$key]['uname']=session('user_auth.username');
            $tianjia[$key]['shuliang']=1;
        }
        foreach ($shenbao as $key => $value) {
            $tianjia[$key]['shenbao']=$value;
        }
        foreach ($bgname as $key => $value) {
            $tianjia[$key]['bgname']=$value;
        }
        foreach ($remark as $key => $value) {
            $tianjia[$key]['remark']=$value;
        }
        foreach ($chaibao as $key => $value) {
            $tianjia[$key]['chaibao']=$value;
        }
        foreach ($bgleixing as $key => $value) {
            $tianjia[$key]['bgleixing']=$value;
        }
        foreach ($tianjia as $key => $value) {
            if (empty($value['kdsn'])) {
                $this->error('快递单号不能为空');
            }
            if (empty($value['kdname'])) {
                $this->error('快递公司不能为空');
            }
            if (empty($value['bgname'])) {
                $this->error('包裹名称不能为空');
            }
            if (empty($value['shenbao'])) {
                $this->error('申报价值不能为空');
            }
            $yy=M('YubaoCart')->where('kdsn="'.$value['kdsn'].'"')->find();
            if (!empty($yy)) {
                $this->error('快递单号 '.$value['kdsn'].' 已经存在');
            }
        }
        foreach ($tianjia as $key => $value) {
            $yid=M('YubaoCart')->add($value);
        }
        $this->success('添加成功',U('User/yubaoadd'));
    }
    public function yubaocartedit(){
        $id=I('post.id');
        $field=I('post.field');
        $value=I('post.value');
        $value=trim($value);
        if ($field=="kdsn"||$field=="kdname"||$field=="bgname") {
            if ($value=='') {
                $info=array(
                    'data'=>M('YubaoCart')->where('id='.$id.' AND uid='.session('user_auth.uid'))->find(),
                    'info'=>'不能为空',
                    'field'=>$field,
                );
                $this->error($info);
            }
        }
        M('YubaoCart')->where('id='.$id.' AND uid='.session('user_auth.uid'))->setField($field,$value);
        $this->success('更改成功');
    }
    public function yubaocartdel(){
        $id=I('post.id');
        $del=M('YubaoCart')->where('id='.$id.' AND uid='.session('user_auth.uid'))->delete();
        if (false!==$del) {
            $this->success($id);
        }else{
            $this->error('删除失败');
        }
    }
    public function changeaddress(){
        $id=I('post.id');
        $data=M('Address');
        $where['uid']=session('user_auth.uid');
        $where['id']=$id;
        $map['uid']=session('user_auth.uid');
        $map['id']=array('neq',$id);
        $data->where($map)->setField('def',0);
        $data->where($where)->setField('def',1);
        $this->success('地址更改成功');
    }

    public function yubaolist(){
        $data=M('Yubao');
        $where['uid']=session('user_auth.uid');
        if (I('get.status')) {
            $this->assign('status',I('get.status'));
            $where['status']=I('get.status');
        }
        if (I('get.keyunsong')) {
            $this->assign('status',9);
            $this->assign('keyunsong',1);
            $where['status']=array('eq',2);
            $where['ydid']=0;
        }
        if (I('get.kdsn')) {
            $where['kdsn']=array('like','%'.I('get.kdsn').'%');
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
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
        $this->assign('list',$list);
        $ybids=M('YubaoSelect')->where('uid='.session('user_auth.uid'))->getField('ybid',true);
        $this->assign('ybids',$ybids);
        $this->assign('title','物品列表 - ');
    	$this->display();
    }
    public function select(){
        $yubao=M('Yubao')->find(I('post.ybid'));
    if (empty($yubao)||$yubao['status']>2) {
        $this->error('预报信息不存在，或者已经出库');
        exit();
    }

    if (I('post.selected')==1) {
        $add=array(
        'ybid'=>I('post.ybid'),
        'uid'=>session('user_auth.uid'),
        );
        M('YubaoSelect')->add($add);
        $this->success('添加成功');
    }elseif (I('post.selected')==0) {
        M('YubaoSelect')->where('ybid='.I('post.ybid').' AND uid='.session('user_auth.uid'))->delete();
        $this->success('删除成功');
    }
    }
    public function tjdingdan(){
        $ybids=M('YubaoSelect')->where('uid='.session('user_auth.uid'))->getField('ybid',true);
        if (empty($ybids)) {
            $this->error('没有选择包裹，无法提交订单');
        }
        $ybwhere['id']=array('in',$ybids);
        $yblist=M('Yubao')->where($ybwhere)->select();
        if (empty($yblist)) {
            $this->error('没有选择包裹，无法提交订单');
        }
        $this->assign('yblist',$yblist);
        $address=M('Address')->where('uid='.session('user_auth.uid').' AND def=1')->find();
        $this->assign('address',$address);
        $user=M('Users')->find(session('user_auth.uid'));
        if ($user['utype']>0) {
            $utype=get_usertypeid($user['utype']);
        }else{
            $utype=getusertypeid($user['score']);
        }
        $delivery=M('Delivery')->where('areaid='.$address['areaid'])->select();
        $this->assign('delivery',$delivery);
        $addresslist=M('Address')->where('uid='.session('user_auth.uid'))->order('id desc')->select();
        $this->assign('addresslist',$addresslist);
        $state=M('State')->select();
        $this->assign('state',$state);
        $this->display();
    }
    public function tjdingdanadd(){
        $addressid=I('post.addressid');
        $deliveryid=I('post.deliveryid');
        $remark=I('post.remark');
        $isxian=I('post.isxian');
        $ybids=M('YubaoSelect')->where('uid='.session('user_auth.uid'))->getField('ybid',true);
        if (empty($ybids)) {
            $this->error('没有选择包裹，无法提交订单');
        }
        if (empty($addressid)) {
            $this->error('请选择收货地址');
        }
        if (empty($deliveryid)) {
            $this->error('请选择运送方式');
        }
        $address=M('Address')->find($addressid);
        $add=array(
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'status'=>1,
            'addtime'=>time(),
            'edittime'=>time(),
            'areaid'=>$address['areaid'],
            'areaname'=>$address['areaname'],
            'consignee'=>$address['consignee'],
            'tel'=>$address['tel'],
            'zipcode'=>$address['zipcode'],
            'address'=>$address['address'],
            'deliveryid'=>$deliveryid,
            'remark'=>$remark,
            'isxian'=>$isxian
        );
        if (M('Yundan')->create($add)) {
            $id=M('Yundan')->add();
            if ($id>0) {
                M('Yundan')->where('id='.$id)->setField('sn',C('DD_PREFIX').sprintf("%07d",$id));
                $ybwhere['id']=array('in',$ybids);
                M('Yubao')->where($ybwhere)->setField('ydid',$id);
                M('YubaoSelect')->where('uid='.session('user_auth.uid'))->delete();
                $allshenbao=M('Yubao')->where($ybwhere)->Sum('shenbao');
                M('Yundan')->where('id='.$id)->setField('shenbao',$allshenbao);
                $this->success('订单添加成功,请至订单列表查看包裹最新状态及支付国际运费',U('User/dingdan'));
            }else{
                $this->error('订单添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function dingdan(){
        $data=M('Yundan');
        $where['uid']=session('user_auth.uid');
        if (I('get.status')) {
            $this->assign('status',I('get.status'));
            $where['status']=I('get.status');
        }
        if (I('get.keywords')) {
            $where['sn']=array('like','%'.I('get.keywords').'%');
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
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['delivery']=M('Delivery')->find($value['deliveryid']);
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function dingdanview($id){
        $data=M('Yundan');
        $info=$data->where('id='.$id.' AND uid='.session('user_auth.uid'))->find();
        if($info['isxian']==1){
            $users=D('Users');
            $score=$users->where('uid='.$info['uid'])->getField('score');
            $dengji=getusertype($score);
            $baoxian=M('Baoxian');
            $bxinfo=$baoxian->where('rank="'.$dengji.'"')->find();
            if($info['shenbao']>$bxinfo['money']){
                $baomoney=$bxinfo['money']*$bxinfo['biv'];
            }else{
                $baomoney=$info['shenbao']*$bxinfo['biv'];
            }
            $this->assign('baomoney',$baomoney);
        }
        if ($info) {
            $info['delivery']=M('Delivery')->find($info['deliveryid']);
            
            $bglist=M('YundanBaoguo')->where('ydid='.$info['id'])->select();
            foreach ($bglist as $key => $value) {
                if ($value['banpao']==1) {
                $bglist[$key]['shoufeileixing']='半泡';
            }else{
                if ($value['weight']>$value['voweight']) {
                    $bglist[$key]['shoufeileixing']='实重';
                }else{
                    $bglist[$key]['shoufeileixing']='泡重';
                }
            }
            }
            $this->assign('bglist',$bglist);
            $yblist=M('Yubao')->where('ydid='.$info['id'])->select();
            foreach ($yblist as $key => $value) {
                $shenbao+=$value['shenbao'];
            }
            $info['shenbaojiazhi']=$shenbao;
            $this->assign('ddinfo',$info);
            $this->assign('yblist',$yblist);
        }else{
            $this->redirect('User/dingdan');
        }
        $this->display();
    }
    public function dingdanyichu(){
        $id=I('post.id');
        if ($id) {
            $yubao=M('Yubao')->where('id='.$id.' AND uid='.session('user_auth.uid'))->find();
        $ydcount=M('Yubao')->where('ydid='.$yubao['ydid'])->count();
        if ($ydcount==1) {
            M('Yundan')->where('id='.$yubao['ydid'].' AND uid='.session('user_auth.uid'))->delete();
        }
        M('Yubao')->where('id='.$id.' AND uid='.session('user_auth.uid'))->setField('ydid',0);
        $this->success('移除成功');
        }else{
            $this->error('信息错误');
        }

    }
    public function dayindingdan($id){
        $data=M('Yundan');
        $info=$data->where('id='.$id.' AND uid='.session('user_auth.uid'))->find();
        if ($info) {
            $info['delivery']=M('Delivery')->find($info['deliveryid']);
            
            $bglist=M('YundanBaoguo')->where('ydid='.$info['id'])->select();
            $this->assign('bglist',$bglist);
            $yblist=M('Yubao')->where('ydid='.$info['id'])->select();
            foreach ($yblist as $key => $value) {
                $shenbao+=$value['shenbao'];
            }
            $info['shenbaojiazhi']=$shenbao;
            $this->assign('ddinfo',$info);
            $this->assign('yblist',$yblist);
        }else{
            $this->error('该订单不存在或者不是您的订单');
        }
        $this->display();
    }
    public function paydingdan($id){
        $yd=M('Yundan');
        $ydinfo=$yd->where('id='.$id.' AND uid='.session('user_auth.uid'))->find();
        $users=D('Users');
        if($ydinfo['isxian']==1){
            $score=$users->where('uid='.$ydinfo['uid'])->getField('score');
            $dengji=getusertype($score);
            $baoxian=M('Baoxian');
            $bxinfo=$baoxian->where('rank="'.$dengji.'"')->find();
            if($ydinfo['shenbao']>$bxinfo['money']){
                $baomoney=$bxinfo['money']*$bxinfo['biv'];
            }else{
                $baomoney=$ydinfo['shenbao']*$bxinfo['biv'];
            }
            $this->assign('baomoney',$baomoney);
        }
        if ($ydinfo['status']==3||$ydinfo['payed']==2) {
            $this->assign('ddinfo',$ydinfo);
            $bglist=M('YundanBaoguo')->where('ydid='.$ydinfo['id'])->select();
            $this->assign('bglist',$bglist);
            $money=M('Users')->where('uid='.session('user_auth.uid'))->getField('money');
            $xinyong=M('Users')->where('uid='.session('user_auth.uid'))->getField('xinyong');
            $this->assign('money',$money);
            $this->assign('xinyong',$xinyong);
        }else{
            $this->error('该订单不存在或没有支付信息');
        }
        $ziliao=$users->where('uid='.$ydinfo['uid'])->getField('ziliao');
        $this->assign('pingzheng',$ziliao);
        $this->display();
    }

    public function getscore(){
        $users=D('Users');
        $score=$users->where('uid='.session('user_auth.uid'))->getField('score');
        $huascore=$users->where('uid='.session('user_auth.uid'))->getField('huascore');
        $dengji=getusertype($score);
        $baoxian=M('Baoxian');
        $bxinfo=$baoxian->where('rank="'.$dengji.'"')->find();
        if($huascore>=$bxinfo['huascore']){
            $data['score']=$bxinfo['huascore'];
            $data['huascore']=$bxinfo['huascore']/100;
        }else{
            $data['score']=$huascore;
            $data['huascore']=$huascore/100;
        }
           $this->ajaxReturn($data);
    }

    public function paydingdanadd(){
        $ddid=I('request.ddid');
        $bgid=I('request.bgid');
        $baomoney=I('request.baomoney');
        //echo $baomoney; exit;
        $huascore=I('request.score');
        $scoremoney=$huascore/100;
        if (empty($ddid)) {
            $this->error('订单错误');
        }
        if (empty($bgid)) {
            $this->error('没有选择需要支付的包裹');
        }
        $info=M('Yundan')->where('uid='.session('user_auth.uid').' AND id='.$ddid)->find();
        if (empty($info)) {
            $this->error('订单错误');
        }
        $where['uid']=session('user_auth.uid');
        $where['ydid']=$ddid;
        $where['id']=array('in',$bgid);
        $bginfo=M('YundanBaoguo')->where($where)->select();
        foreach ($bginfo as $key => $value) {
            $money=M('Users')->where('uid='.session('user_auth.uid'))->getField('money');
            $xinyong=M('Users')->where('uid='.session('user_auth.uid'))->getField('xinyong');
            if ($money+$xinyong<$value['freight']) {
                $this->error('余额不足以支付您的订单');
            }else{
                M('Users')->where('uid='.session('user_auth.uid'))->setDec('money',$value['freight']);
                M('Users')->where('uid='.session('user_auth.uid'))->setInc('score',$value['freight']*C('scorelv'));  // 用户交易获得积分 
                M('Users')->where('uid='.session('user_auth.uid'))->setInc('huascore',$value['freight']*C('scorelv')); 
                $ybag=array(
                    'id'=>$value['id'],
                    'haspay'=>1,
                    'status'=>4,
                    'paytime'=>time(),
                );
                M('YundanBaoguo')->save($ybag);
                D('Record')->addrecord(session('user_auth.uid'),'-'.$value['freight'],2,2,'国际包裹 <a href="###">'.$value['bgsn'].'</a> 付款');
                D('Record')->addrecordscore(session('user_auth.uid'),$value['freight']*C('scorelv'),1,8,'国际包裹 <a href="###">'.$value['bgsn'].'</a> 获得积分');
                if($baomoney!=0){
                     M('Users')->where('uid='.session('user_auth.uid'))->setDec('money',$baomoney); //支付保险费用
                    D('Record')->addrecord(session('user_auth.uid'),'-'.$baomoney,2,2,'国际包裹商业险<a href="###">'.$value['bgsn'].'</a> 付款');
                }
                if($huascore!=0){
                    M('Users')->where('uid='.session('user_auth.uid'))->setInc('money',$scoremoney);
                    D('Record')->addrecord(session('user_auth.uid'),$scoremoney,1,2,'国际包裹<a href="###">'.$value['bgsn'].'</a> 积分抵现返现金'); 
                     M('Users')->where('uid='.session('user_auth.uid'))->setDec('huascore',$huascore); //积分消费
                    D('Record')->addrecordscore(session('user_auth.uid'),'-'.$huascore,2,8,'国际包裹<a href="###">'.$value['bgsn'].'</a> 积分消费');
                }
            }
        }
        $bgcount=M('YundanBaoguo')->where('uid='.session('user_auth.uid').' AND ydid='.$ddid)->count();
        $paycount=M('YundanBaoguo')->where('uid='.session('user_auth.uid').' AND ydid='.$ddid.' AND haspay=1')->count();
        if ($bgcount>$paycount) {
            if ($info['status']<4) {
                M('Yundan')->where('id='.$ddid)->setField('status',4);
            }
            $wwwh['uid']=session('user_auth.uid');
            $wwwh['ydid']=$ddid;
            $wwwh['haspay']=array('neq',1);
            M('YundanBaoguo')->where($wwwh)->setField('secpay',1);
            M('Yundan')->where('id='.$ddid)->setField('payed',2);
            $this->success('订单部分付款成功',U('User/dingdan','status=4'));
        }else{
            if ($info['status']<4) {
                M('Yundan')->where('id='.$ddid)->setField('status',4);
            }
            M('Yundan')->where('id='.$ddid)->setField('payed',1);
            $this->success('订单付款成功',U('User/dingdan','status=4'));
        }
    }
    public function shouhuo($id){
        $data=array('status'=>6,'shouhuotime'=>time());
        $info=M('Yundan')->where('id='.$id.' AND status=5')->find();
        if (empty($info)) {
            $this->error('订单错误');
        }
        M('Yundan')->where('id='.$id.' AND status=5')->setField($data);
        M('YundanBaoguo')->where('ydid='.$id.' AND status=5')->setField($data);
        $this->success('确认收货完成,请您评价,谢谢',U('User/dingdan','status=6'));
    }
    public function baoguoshouhuo($id){
        $data=array('status'=>6,'shouhuotime'=>time());
        $info=M('YundanBaoguo')->where('id='.$id.' AND status=5')->find();
        if (empty($info)) {
            $this->error('包裹错误');
        }
        M('YundanBaoguo')->where('id='.$id.' AND status=5')->setField($data);
        $this->success('收货完成',U('User/baoguolist','status=6'));
    }
    public function baoguolist(){
        $data=M('YundanBaoguo');
        if (I('request.id')) {
            $where['id']=I('request.id');
        }
        $where['uid']=session('user_auth.uid');
        if (I('get.status')) {
            $this->assign('status',I('get.status'));
            $where['status']=I('get.status');
        }
        if (I('get.keywords')) {
            $where['bgsn']=array('like','%'.I('get.keywords').'%');
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
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['dingdan']=M('Yundan')->where('id='.$value['ydid'])->find();
            $list[$key]['delivery']=M('Delivery')->find($list[$key]['dingdan']['deliveryid']);
            if ($value['banpao']==1) {
                $list[$key]['shoufeileixing']='半泡';
            }else{
                if ($value['weight']>$value['voweight']) {
                    $list[$key]['shoufeileixing']='实重';
                }else{
                    $list[$key]['shoufeileixing']='泡重';
                }
            }
        }
        $this->assign('list',$list);
    	$this->display();
    }
    public function setting(){
    	if (IS_POST) {
    		$user=M('Users');
            $uwhere['uid']=array('neq',session('user_auth.uid'));
            $uwhere['email']=I('post.email');
            $uinfo=$user->where($uwhere)->find();
            if ($uinfo) {
                $this->error('邮箱已经存在');
            }
            $uwhere1['uid']=array('neq',session('user_auth.uid'));
            $uwhere1['nickname']=I('post.nickname');
            $uinfo1=$user->where($uwhere1)->find();
            if ($uinfo1) {
                $this->error('昵称已经存在');
            }
    		$save=array(
    			'uid'=>session('user_auth.uid'),
    			'nickname'=>I('post.nickname'),
    			'xingming'=>I('post.xingming'),
    			'sex'=>I('post.sex'),
    			'mobile'=>I('post.mobile'),
    			'qq'=>I('post.qq'),
                'email'=>I('post.email'),
    		);
    		if ($user->create($save)) {
    			if (false!==$user->save()) {
    				$this->success('更新成功',U('User/index'));
    			}else{
    				$this->error('更新失败');
    			}
    		}else{
    			$this->error('数据创建失败');
    		}
    	}else{
    		$this->display();
    	}
    }

    public function set(){
        if (IS_POST) {
            $user=M('Users');
            $uwhere['uid']=array('neq',session('user_auth.uid'));
            $uwhere['email']=I('post.email');
            $uinfo=$user->where($uwhere)->find();
            if ($uinfo) {
                $this->error('邮箱已经存在');
            }
            $uwhere1['uid']=array('neq',session('user_auth.uid'));
            $uwhere1['nickname']=I('post.nickname');
            $uinfo1=$user->where($uwhere1)->find();
            if ($uinfo1) {
                $this->error('昵称已经存在');
            }
            $save=array(
                'uid'=>session('user_auth.uid'),
                'nickname'=>I('post.nickname'),
                'xingming'=>I('post.xingming'),
                'sex'=>I('post.sex'),
                'mobile'=>I('post.mobile'),
                'qq'=>I('post.qq'),
                'email'=>I('post.email'),
            );
            if ($user->create($save)) {
                if (false!==$user->save()) {
                    $user->where("uid=".session('user_auth.uid'))->setInc('score',200);// 完成详细资料后获得200积分
                    $user->where("uid=".session('user_auth.uid'))->setInc('huascore',200);
                    D('Record')->addrecordscore(session('user_auth.uid'),'200',1,8,'完善资料送积分');
                    $this->success('更新成功',U('User/index'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{
            $this->display();
        }
    }

    public function setpass(){
        $tel=C('SERVICE_TEL');
    	if (IS_POST) {
    		$user=M('Users');
    		$info=$user->where('uid='.session('user_auth.uid'))->getField('password');
    		if ($info!=md5(I('post.oldpassword'))) {
    			$this->error('原密码错误，请修改');
    		}

    		if (I('post.password')!=I('post.repassword')) {
    			$this->error('两次密码输入不一致，请修改');
    		}
    		if (I('post.oldpassword')==I('post.password')) {
    			$this->error('新密码与旧密码相同，无需修改');
    		}
    		$user->where('uid='.session('user_auth.uid'))->setField('password',md5(I('post.password')));
            $password=I('post.password');
            $email=session('user_auth.email');
            $username=session('user_auth.username');
            $sendresult=send_mail($email,'','账户登陆密码修改函','尊敬的'.$username.'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ProcurementChina已收到您的账户登录密码变更申请,
如非本人操作此项变更，请即刻致电'.$tel.' 或 发邮件至： service@procurementchina.com<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina官方网址是：www.procurementchina.com<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br><br><br>Dear '.$username.'：<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Your application for changing login password has been received. 
If this amendment is not done by yourself, please contact us at: '.$tel.' or email to service@procurementchina.com<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Official Website is www.procurementchina.com<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Should you have any complaints and suggestions, please email to complaint@procurementchina.com <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>
This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
    		$this->success('密码修改成功',U('User/setpass'));
    		
    	}else{
    		$this->display();
    	}
    }
    public function settouxiang(){
    	$this->display();
    }
    public function uploadimg(){
    	$user_id=I('request.uid');
        if (empty($user_id)) {
             $this->error('系统错误，请联系管理员');//取值取用户子目录失败，故不应继续执行
             exit;
         }
        $upload = new \Think\Upload;                  // 实例化上传类
        $upload->maxSize = 1*1024*1024;                 //设置上传图片的大小
        $upload->exts = array('jpg','png');  //设置上传图片的后缀
        $upload->replace = true;  
        $upload->autoSub=false;  
        $upload->saveExt='jpg';            
        $upload->saveName = 'temp';                   //设置上传头像命名规则(临时图片),修改了UploadFile上传类
        //完整的头像路径
        if (!is_dir('./avatar/'.$user_id.'/')) {
            mkdir('./avatar/'.$user_id.'/');
        }
        $upload->rootPath='./avatar/'.$user_id.'/';
        $info=$upload->upload();
        if(false==$info) {                        // 上传错误提示错误信息
            $this->ajaxReturn('','上传错误',0,'json');
        }else{                                          // 上传成功 获取上传文件信息
            $temp_size = getimagesize($path.'temp.jpg');
            if($temp_size[0] < 100 || $temp_size[1] < 100){//判断宽和高是否符合头像要求
                $this->ajaxReturn('','图片宽或高不得小于100px！',0,'json');
            }
            $data=array(
            	'file'=>__ROOT__.'/avatar/'.$user_id.'/temp.jpg',
            	'info'=>$info,
            	'status'=>1,
            );
            $this->ajaxReturn($data);
        }
    }
    public function cropImg(){
        //图片裁剪数据
        $params = I('post.');                       //裁剪参数
        if(!isset($params) && empty($params)){
            return;
        }
        
        //头像目录地址
        $path = './avatar/'.session('user_auth.uid').'/';
        //要保存的图片
        $real_path = $path.'avatar.jpg';
        //临时图片地址
        $pic_path = $path.'temp.jpg';
        $Think_img = new \Think\Image; 
        //裁剪原图
        $Think_img->open($pic_path)->crop($params['w'],$params['h'],$params['x'],$params['y'])->save($real_path);
        //生成缩略图
        $Think_img->open($real_path)->thumb(200,200, 1)->save($path.'avatar_200.jpg');
        $Think_img->open($real_path)->thumb(100,100, 1)->save($path.'avatar_100.jpg');
        $Think_img->open($real_path)->thumb(50,50, 1)->save($path.'avatar_50.jpg');
        $data=M('Users');
        $data->where('uid='.session('user_auth.uid'))->setField('touxiang','/avatar/'.session('user_auth.uid').'/avatar_200.jpg');
        $this->success('上传头像成功',U('User/settouxiang'));
    }
    public function address(){
        $state=M('State')->select();
        $this->assign('state',$state);
        $address=M('Address')->where('uid='.session('user_auth.uid'))->order('id desc')->select();
        $this->assign('addresslist',$address);
    	$this->display();
    }
    public function addressadd(){
        if (!I('post.consignee')) {
            $this->error('请填写收货人');
        }
        if (!I('post.tel')) {
            $this->error('请填写联系电话');
        }
        if (!I('post.areaid')) {
            $this->error('请选择收货国家');
        }else{
            $state=M('State')->find(I('post.areaid'));
        }
        if (!I('post.address')) {
            $this->error('请输入详细地址');
        }
        $add=array(
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'consignee'=>I('post.consignee'),
            'tel'=>I('post.tel'),
            'areaid'=>I('post.areaid'),
            'areaname'=>$state['name'],
            'zipcode'=>I('post.zipcode'),
            'address'=>I('post.address'),
            'def'=>1,
        );
        if (M('Address')->create($add)) {
            $id=M('Address')->add();
            if ($id>0) {
                $map['id']=array('neq',$id);
                $map['uid']=session('user_auth.uid');
                M('Address')->where($map)->setField('def',0);
                $this->success('添加成功',U('User/address'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function addressedit(){
        if (!I('post.consignee')) {
            $this->error('请填写收货人');
        }
        if (!I('post.tel')) {
            $this->error('请填写联系电话');
        }
        if (!I('post.areaid')) {
            $this->error('请选择收货国家');
        }else{
            $state=M('State')->find(I('post.areaid'));
        }
        if (!I('post.address')) {
            $this->error('请输入详细地址');
        }
        $save=array(
            'id'=>I('post.id'),
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'consignee'=>I('post.consignee'),
            'tel'=>I('post.tel'),
            'areaid'=>I('post.areaid'),
            'areaname'=>$state['name'],
            'zipcode'=>I('post.zipcode'),
            'address'=>I('post.address'),
        );
        if (M('address')->create($save)) {
            $id=M('address')->save();
            if ($id!==false) {
                $this->success('编辑成功',U('User/address'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function addressdel(){
        $id=I('post.id');
        $data=M('address')->where('uid='.session('user_auth.uid').' AND id='.$id)->find();
        if ($data) {
            M('Address')->where('uid='.session('user_auth.uid').' AND id='.$id)->delete();
            $this->success('删除成功');
        }else{
            $this->error('删除地址错误');
        }
    }
    public function msg(){
        $type=I('request.type');

        if (empty($type)) {
            $type="liuyan";
        }
        $data=M('Msg');
        if ($type=="notice") {
            $where['uid']=array(array('eq',0),array('eq',session('user_auth.uid')),'or');
        }
        if ($type=="liuyan") {
            $where['uid']=session('user_auth.uid');
        }
        $where['type']=$type;
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
        $this->assign('list',$list);
        
        $this->assign('type',$type);
        $this->display();
    }
    public function liuyanadd(){
        $content=I('post.content');
        if (empty($content)) {
            $this->error('留言内容不能为空');
        }
        $add=array(
            'content'=>$content,
            'add_time'=>time(),
            'uid'=>session('user_auth.uid'),
            'type'=>"liuyan",
        );
        if(M('Msg')->add($add)){
            $this->success('添加留言成功');
        }else{
            $this->error('添加留言失败');
        };

    }
    public function getyubaoinfo($id){
        $info=M('Yubao')->find($id);
        $this->ajaxReturn($info);
    }
    public function yubaoedit(){
        $kdsn=I('post.kdsn');
        $kdname=I('post.kdname');
        $bgname=I('post.bgname');
        if (empty($kdsn)) {
            $this->error('快递单号不能为空');
        }
        if (empty($kdname)) {
            $this->error('快递公司不能为空');
        }
        if (empty($bgname)) {
            $this->error('包裹名称不能为空');
        }
        $where['kdsn']=$kdsn;
        $where['id']=array('neq',I('post.id'));
        $ybinfo=M('Yubao')->where($where)->find();
        if ($ybinfo) {
            $this->error('快递单号已经存在');
        }
        $ybadd=array(
            'id'=>I('post.id'),
                        'kdsn'=>$kdsn,
                        'kdname'=>$kdname,
                        'bgname'=>$bgname,
                        'shenbao'=>I('post.shenbao'),
                        'bgleixing'=>I('post.bgleixing'),
                        'chaibao'=>I('post.chaibao'),
                        'edittime'=>time(),
                        'remark'=>I('post.remark'),
        );
        if (M('Yubao')->where('uid='.session('user_auth.uid').' AND id='.I('post.id'))->save($ybadd)) {
            $this->success('编辑成功');
        }else{
            $this->error('编辑失败');
        }
    }
    public function yubaodel($id){
        $del=M('Yubao')->where('id='.$id.' AND uid='.session('user_auth.uid'))->delete();
        if ($del) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    public function myaccount(){
        $data=M('Record');
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $where['uid']=session('user_auth.uid');
        $where['leixing']=array('neq',8);
        $total= $data->where($where)->count();
        // $total= $data->where('uid='.session('user_auth.uid'))->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        // $list=$data->where('uid='.session('user_auth.uid'))->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('list',$list);
    	$this->display();
    }
   
    public function myscore(){
        $data=M('Record');
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $where['uid']=session('user_auth.uid');
        $where['leixing']=array('eq',8);
        $total= $data->where($where)->count();
        // $total= $data->where('uid='.session('user_auth.uid'))->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        // $list=$data->where('uid='.session('user_auth.uid'))->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
   //// 支付宝充值
    public function chongzhi(){
        $yinhanglist=M('Yinhang')->select();
        $this->assign('yinhanglist',$yinhanglist);
        $yumoney=M('Users')->where('uid='.session('user_auth.uid'))->find();
        $this->assign('yumoney',$yumoney);
        $ziliao=$yumoney['ziliao'];
        $this->assign('ziliao',$ziliao);
        $price=$_GET['price'];
        if($price>0){
             $uid=session('user_auth.uid');
             $username=session('user_auth.username');
                $value["gtitle"]="神州代运用户".$username."账户充值".$price;
                $priceCount=sprintf("%01.2f",$price);//价格
                $OrdersId=$uid."_".time();
                $sysconfigfile="./Apps/Home/Conf/config.php";
                if(file_exists($sysconfigfile)){
                    $config=include($sysconfigfile);
                }
                include("./Apps/Home/alipay/config_pay_alipay.php");//支付配置文件
                //处理付款操作
                $open="window.open ('".$reqUrl."', 'newwindow');";
                
            //dump($parameter);
            //echo '<html><head><title>转到支付页面</title></head><body onLoad="document.gotopay.submit();"><form name="gotopay" action="'.$strRequestUrl.'" method="post" target="_blank">'.$hiddeninput.'</form></body></html>';
                redirect($strRequestUrl);
            }
        $this->display();
    }
    public function chongzhiadd(){
        if (!I('post.yhname')) {
            $this->error('汇款银行名称必须填写');
        }
        if (!I('post.runame')) {
            $this->error('汇入银行名称必须填写');
        }
        if (!I('post.amount')) {

            $this->error('金额必须填写');
        }
        if (!I('post.bizhong')) {

            $this->error('币种必须填写');
        }
        if (is_numeric(I('post.amount'))) {
            if (I('post.amount')<=0) {
                $this->error('金额不能小于等于0');
            }
        }else{
            $this->error('金额必须为数字');
        }
        if (I('post.huikuantime')) {
            $hktime=strtotime(I('post.huikuantime'));
        }else{
            $hktime=time();
        }
        $add=array(
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'fangshi'=>'线下汇款',
            'amount'=>I('post.amount'),
            'yhname'=>I('post.yhname'),
            'zhanghao'=>I('post.zhanghao'),
            'zhname'=>I('post.zhname'),
            'remark'=>I('post.remark'),
            'email'=>I('post.email'),
            'huikuantime'=>$hktime,
            'runame'=>I('post.runame'),
            'bizhong'=>I('post.bizhong'),
            'pic'=>I('post.pic'),
            'addtime'=>time(),
            'yinhangid'=>I('post.yinhangid'),
        );
        if (M('Huikuan')->create($add)) {
            if (false!==M('Huikuan')->add()) {
                $this->success('添加汇款单成功',U('User/chongzhi'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    
    public function daichongzhi(){
        if (IS_POST) {
            if (!I('post.zhanghao')) {
                $this->error('请填写财付通账号');
            }
            if (!I('post.amount')||I('post.amount')<=0) {
                $this->error('充值金额错误');
            }
            $zhifuamount=round(I('post.amount')/C('MAIN_HUILV'),2);
            // $money=M('Users')->where('uid='.session('user_auth.uid'))->getField('money');
            // $xinyong=M('Users')->where('uid='.session('user_auth.uid'))->getField('xinyong');
            // if ($zhifuamount>$money+$xinyong) {
            //     $this->error('您的余额不足');
            // }
            $add=array(
                'uid'=>session('user_auth.uid'),
                'uname'=>session('user_auth.username'),
                'zhanghao'=>I('post.zhanghao'),
                'zhifuamount'=>$zhifuamount,
                'remark'=>I('post.remark'),
                'addtime'=>time(),
            );
            $did=M('Daichongzhi')->add($add);
            if ($did>0) {
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }
    }
    public function kdgenzong(){
        $kdsn=I('get.kdsn');
        $where['kdsn']=$kdsn;
        $ybinfo=M('Yubao')->where($where)->find();
        if ($ybinfo) {
            $ybinfo['yundan']=M('Yundan')->where('id='.$ybinfo['ydid'])->find();
            $ybinfo['ydbaoguo']=M('YundanBaoguo')->where('id='.$ybinfo['bgid'])->find();
        }else{
            $ybinfo=null;
        }
        $this->assign('ybinfo',$ybinfo);
        $this->display();
    }
    public function uploadpingzheng(){
        $config = array(   
                'maxSize'    =>    2*1024*1024,
                'rootPath'   =>    './Uploads/pingzheng/',  
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                'autoSub'    =>    true,   
                'subName'    =>    array('date','Ymd'),
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $info=__ROOT__.'/Uploads/pingzheng/'.$info['Filedata']['savepath'].$info['Filedata']['savename'];
           echo $info;
        }else{                                          // 上传成功 获取上传文件信息
            $this->error($upload->getError());//获取失败信息
        }
    }
    public function yubaoonly(){
        $this->display();
    }
    public function ybonlyadd(){
        $kdname=I('post.kdname');
        $kdsn=I('post.kdsn');
        $shenbao=I('post.shenbao');
        $bgname=I('post.bgname');
        $remark=I('post.remark');
        $chaibao=I('post.chaibao');
        $bgleixing=I('post.bgleixing');
        foreach ($kdname as $key => $value) {
            $tianjia[$key]['kdname']=$value;
        }
        foreach ($kdsn as $key => $value) {
            $tianjia[$key]['kdsn']=$value;
            $tianjia[$key]['uid']=session('user_auth.uid');
            $tianjia[$key]['uname']=session('user_auth.username');
            $tianjia[$key]['shuliang']=1;
        }
        foreach ($shenbao as $key => $value) {
            $tianjia[$key]['shenbao']=$value;
        }
        foreach ($bgname as $key => $value) {
            $tianjia[$key]['bgname']=$value;
        }
        foreach ($remark as $key => $value) {
            $tianjia[$key]['remark']=$value;
        }
        foreach ($chaibao as $key => $value) {
            $tianjia[$key]['chaibao']=$value;
        }
        foreach ($bgleixing as $key => $value) {
            $tianjia[$key]['bgleixing']=$value;
        }
        foreach ($tianjia as $key => $value) {
            if (empty($value['kdsn'])) {
                $this->error('快递单号不能为空');
            }
            if (empty($value['kdname'])) {
                $this->error('快递公司不能为空');
            }
            if (empty($value['bgname'])) {
                $this->error('包裹名称不能为空');
            }
            if (empty($value['shenbao'])) {
                $this->error('申报价值不能为空');
            }
            $ybwhere['kdsn']=$value['kdsn'];
            $ybwhere['uid']=array(array('gt',0),array('neq',session('user_auth.uid')),'AND');
                
            $yubaoinfo=M('Yubao')->where($ybwhere)->find();
            if ($yubaoinfo) {
                $kdsns[$key]=$yubaoinfo['kdsn'];
            }
        }
        if ($kdsns) {
                $kdsnscount=count($kdsns);
                $kdsns=implode(' , ', $kdsns);
                $this->error('快递单号 '.$kdsns.' 等 '.$kdsnscount.' 个包裹已经预报');
            }
        foreach ($tianjia as $key => $value) {
            $add=array(
                    'uid'=>session('user_auth.uid'),
                    'uname'=>session('user_auth.username'),
                    'kdname'=>$value['kdname'],
                    'kdsn'=>$value['kdsn'],
                    'bgname'=>$value['bgname'],
                    'shenbao'=>$value['shenbao'],
                    'bgleixing'=>$value['bgleixing'],
                    'chaibao'=>$value['chaibao'],
                    'remark'=>$value['remark'],
                    'addtime'=>time(),
                    'edittime'=>time(),
                );
                $wwhere['kdsn']=$value['kdsn'];
                $wwhere['uid']=array(array('eq',0),array('eq',session('user_auth.uid')),'or');
                $ybinfo=M('Yubao')->where($wwhere)->find();
                if ($lists=M('Yubao')->create($add)) {
                    if ($ybinfo) {
                        $lists['id']=$ybinfo['id'];
                        M('Yubao')->save($lists);
                    }else{
                        $lists['status']=1;
                        M('Yubao')->add($lists);
                    }
                }else{
                    $this->error('数据创建失败');
                }
        }
        $this->success('添加预报成功,请在预报列表中查看是否入库');
    }
    public function excelin(){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //要导入的xls文件，位于根目录下的Public文件夹
        $filename=$_FILES["excel"]["tmp_name"];
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");
        //如果excel文件后缀名为.xlsx，导入这下类
        //import("Org.Util.PHPExcel.Reader.Excel2007");
        //$PHPReader=new \PHPExcel_Reader_Excel2007();

        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=3;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            }
        
        }
            foreach ($arr as $key => $value) {
                if (empty($value['A'])) {
                    $this->error('快递公司不能为空');
                }
                if (empty($value['B'])) {
                    $this->error('快递单号不能为空');
                }
                $ybwhere['kdsn']=trim($value['B']);
                $ybwhere['uid']=array(array('gt',0),array('neq',session('user_auth.uid')),'AND');
                
                $yubaoinfo=M('Yubao')->where($ybwhere)->find();
                if ($yubaoinfo) {
                    $kdsns[$key]=$yubaoinfo['kdsn'];
                }
                if (empty($value['C'])) {
                    $this->error('包裹名称不能为空');
                }
                if (!is_numeric($value['D'])) {
                    $this->error('有申报价值格式错误');
                }
                if (in_array($value['E'], array('0','1'))) {
                    
                }else{
                    $this->error('有包裹类型格式错误');
                }
                if (in_array($value['F'], array('0','1'))) {
                    
                }else{
                    $this->error('有是否拆包格式错误');
                }
            }
            if ($kdsns) {
                $kdsnscount=count($kdsns);
                $kdsns=implode(' , ', $kdsns);
                $this->error('快递单号 '.$kdsns.' 等 '.$kdsnscount.' 个包裹已经预报');
            }
            foreach ($arr as $key => $value) {
                $add=array(
                    'uid'=>session('user_auth.uid'),
                    'uname'=>session('user_auth.username'),
                    'kdname'=>$value['A'],
                    'kdsn'=>trim($value['B']),
                    'bgname'=>$value['C'],
                    'shenbao'=>$value['D'],
                    'bgleixing'=>$value['E'],
                    'chaibao'=>$value['F'],
                    'remark'=>$value['G'],
                    'addtime'=>time(),
                    'edittime'=>time(),
                );
                $wwhere['kdsn']=$value['B'];
                $wwhere['uid']=array(array('eq',0),array('eq',session('user_auth.uid')),'or');
                $ybinfo=M('Yubao')->where($wwhere)->find();
                if ($lists=M('Yubao')->create($add)) {
                    if ($ybinfo) {
                        $lists['id']=$ybinfo['id'];
                        M('Yubao')->save($lists);
                    }else{
                        $lists['status']=1;
                        M('Yubao')->add($lists);
                    }
                    
                }else{
                    $this->error('数据创建失败');
                }
            }
            $this->success('数据导入成功',U('User/yubaolist','keyunsong=1'));
    }
    public function dingdanexcelin(){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //要导入的xls文件，位于根目录下的Public文件夹
        $filename=$_FILES["excel"]["tmp_name"];
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");
        //如果excel文件后缀名为.xlsx，导入这下类
        //import("Org.Util.PHPExcel.Reader.Excel2007");
        //$PHPReader=new \PHPExcel_Reader_Excel2007();

        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=22;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            }
        
        }
            foreach ($arr as $key => $value) {
                if (empty($value['A'])) {
                    $this->error('快递公司不能为空');
                }
                if (empty($value['B'])) {
                    $this->error('快递单号不能为空');
                }
                $ybwhere['kdsn']=trim(str_replace("'","",$value['B']));
                // $ybwhere['uid']=array(array('gt',0),array('neq',session('user_auth.uid')),'AND');
                // $ybwhere['ydid']=array()
                $yubaoinfo=M('Yubao')->where($ybwhere)->find();
                if ($yubaoinfo) {
                    if ($yubaoinfo['uid']!==session('user_auth.uid')) {
                        $kdsns[$key]=$yubaoinfo['kdsn'];
                    }
                    if ($yubaoinfo['ydid']>0) {
                        $kdsns[$key]=$yubaoinfo['kdsn'];
                    }
                }
                if (empty($value['C'])) {
                    $this->error('包裹名称不能为空');
                }
                if (!is_numeric($value['D'])) {
                    $this->error('有申报价值格式错误');
                }
                if (in_array($value['E'], array('0','1'))) {
                    
                }else{
                    $this->error('有包裹类型格式错误');
                }
                if (in_array($value['F'], array('0','1'))) {
                    
                }else{
                    $this->error('有是否拆包格式错误');
                }
                $deli=M('Delivery')->where('dname="'.trim($value['I']).'"')->getField('areaname',true);
                if ($deli) {
                    if (!in_array(trim($value['H']),$deli)) {
                        $this->error('国家不存在或者该国家与配送方式不匹配');
                    }
                }else{
                    $this->error('配送方式不存在');
                }

                if (empty($value['J'])) {
                    $this->error('联系人不能为空');
                }
                if (empty($value['K'])) {
                    $this->error('联系电话不能为空');
                }
                if (empty($value['L'])) {
                    $this->error('邮编不能为空');
                }
                if (empty($value['M'])) {
                    $this->error('地址不能为空');
                }
            
            }
            if ($kdsns) {
                $kdsnscount=count($kdsns);
                $kdsns=implode(' , ', $kdsns);
                $this->error('快递单号 '.$kdsns.' 等 '.$kdsnscount.' 个包裹已经预报');
            }
            
            foreach ($arr as $key => $value) {
                $wh['areaname']=trim($value['H']);
                $wh['dname']=trim($value['I']);
                $deliveryid=M('Delivery')->where($wh)->getField('id');
                    $add1=array(
                        'uid'=>session('user_auth.uid'),
                        'uname'=>session('user_auth.username'),
                        'status'=>1,
                        'addtime'=>time(),
                        'areaid'=>M('State')->where('name="'.trim($value['H']).'"')->getField('id'),
                        'areaname'=>trim($value['H']),
                        'consignee'=>$value['J'],
                        'tel'=>$value['K'],
                        'zipcode'=>$value['L'],
                        'address'=>$value['M'],
                        'deliveryid'=>$deliveryid,
                        'remark'=>$value['G'],
                        'edittime'=>time(),
                    );
                    if ($list1=M('Yundan')->create($add1)) {
                        $ydid=M('Yundan')->add($list1);
                        M('Yundan')->where('id='.$ydid)->setField('sn',C('DD_PREFIX').sprintf('%07d',$ydid));
                        $add=array(
                            'uid'=>session('user_auth.uid'),
                            'uname'=>session('user_auth.username'),
                            'kdname'=>$value['A'],
                            'kdsn'=>trim(str_replace("'", "", $value['B'])),
                            'ydid'=>$ydid,
                            'bgname'=>$value['C'],
                            'shenbao'=>trim($value['D']),
                            'bgleixing'=>trim($value['E']),
                            'chaibao'=>trim($value['F']),
                            'remark'=>$value['G'],
                        );
                $wwhere['kdsn']=trim(str_replace("'", "", $value['B']));
                $wwhere['uid']=array(array('eq',0),array('eq',session('user_auth.uid')),'or');
                $ybinfo=M('Yubao')->where($wwhere)->find();
                if ($lists=M('Yubao')->create($add)) {
                    if ($ybinfo) {
                        $lists['id']=$ybinfo['id'];
                        M('Yubao')->save($lists);
                    }else{
                        $lists['status']=1;
                        $lists['addtime']=time();
                        $lists['edittime']=time();
                        M('Yubao')->add($lists);
                    }
                    
                }else{
                    $this->error('包裹数据创建失败');
                }
                    }else{
                        $this->error('订单数据创建失败');
                    }

                
            }
            $this->success('数据导入成功',U('User/dingdan'));
    }
    public function daigou(){
        $data=M('Daigou');
        $where['uid']=session('user_auth.uid');
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
            $list[$key]['goods']=M('DaigouGoods')->where('dgid='.$value['id'])->order('goodsseller')->select();
            $list[$key]['goodscount']=count($list[$key]['goods']);
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function deldaigou(){
        $id=I('post.id');
        $where['id']=$id;
        $where['uid']=session('user_auth.uid');
        $where['status']=array('eq',1);
        $info=M('Daigou')->where($where)->find();
        if ($info) {
            M('DaigouGoods')->where('dgid='.$id)->delete();
            M('Daigou')->where('id='.$id)->delete();
            $this->success('订单删除成功');
        }else{
            $this->error('订单信息错误');
        }
    }
    public function paydaigou(){
        $id=I('request.id');
        $where['id']=$id;
        $where['uid']=session('user_auth.uid');
        $where['status']=array('eq',1);
        $info=M('Daigou')->where($where)->find();
        if ($info) {
            $info['goufei']=sprintf('%.2f',($info['totalgoodsprice']+$info['totalgoodsfreight'])*C('GOU_FU'));
            $info['totalprice']=sprintf('%.2f',$info['totalprice']+$info['goufei']);
            $info['zhifuprice']=sprintf('%.2f',$info['totalprice']/C('MAIN_HUILV'));
            if($info['isxian']==1){
                $users=D('Users');
                $score=$users->where('uid='.$info['uid'])->getField('score');
                $dengji=getusertype($score);
                $baoxian=M('Baoxian');
                $bxinfo=$baoxian->where('rank="'.$dengji.'"')->find();
                if($info['totalgoodsprice']>$bxinfo['money']){
                    $baomoney=sprintf('%.2f',$bxinfo['money']*$bxinfo['biv']);
                }else{
                    $baomoney=sprintf('%.2f',$info['totalgoodsprice']*$bxinfo['biv']);
                    //dump($baomoney);
                }
                $this->assign('baomoney',$baomoney);
            }
            //dump($baomoney);
            $info['zhifuprice']+=$baomoney;
            $info['goods']=M('DaigouGoods')->where('dgid='.$id)->select();

            //$info['goods']['gcount']=$info['goods']['goodsprice']*$info['goods']['goodsnum'];
            $ziliao=D('Users')->where('uid='.$info['uid'])->getField('ziliao');
            //dump($info);
            $this->assign('ziliao',$ziliao);
            $this->assign('info',$info);
            $this->display();
        }else{
            $this->error('订单信息错误，或者已付款');
        }
    }
    public function daigouview($id){
        $where['id']=$id;
        $where['uid']=session('user_auth.uid');
        $info=M('Daigou')->where($where)->find();
        
        if ($info) {
            $info['goufei']=sprintf('%.2f',($info['totalgoodsprice']+$info['totalgoodsfreight'])*C('GOU_FU'));
            $info['totalprice']=sprintf('%.2f',$info['totalprice']+$info['goufei']);
            $info['zhifuprice']=sprintf('%.2f',$info['totalprice']/C('MAIN_HUILV'));
            if($info['isxian']==1){
                $users=D('Users');
                $score=$users->where('uid='.$info['uid'])->getField('score');
                $dengji=getusertype($score);
                $baoxian=M('Baoxian');
                $bxinfo=$baoxian->where('rank="'.$dengji.'"')->find();
                if($info['zhifuprice']>$bxinfo['money']){
                    $baomoney=sprintf('%.2f',$bxinfo['money']*$bxinfo['biv']);
                }else{
                    $baomoney=sprintf('%.2f',$info['totalgoodsprice']*$bxinfo['biv']);
                }
                $this->assign('baomoney',$baomoney);
            }
            $info['zhifuprice']+=$baomoney;
            $info['totalprice']+=$baomoney;
            $info['goods']=M('DaigouGoods')->where('dgid='.$id)->select();
            $this->assign('info',$info);
            $this->display();
        }else{
            $this->error('订单信息错误');
        }
    }
    public function paydaigouadd(){
        $id=I('post.id');
        $baomoney=I('post.baomoney');
        $info=M('Daigou')->where('uid='.session('user_auth.uid').' AND id='.$id)->find();
        if (empty($info)) {
            $this->error('订单错误');
        }
        if ($info['status']>1) {
            $this->error('订单已经付款');
        }
        $info['goufei']=sprintf('%.2f',($info['totalgoodsprice']+$info['totalgoodsfreight'])*C('GOU_FU'));
        $info['totalprice']=sprintf('%.2f',$info['totalprice']+$info['goufei']);
        $zhifuprice=sprintf('%.2f',$info['totalprice']/C('MAIN_HUILV'));
        $zhifuprice+=$baomoney;
        //echo $zhifuprice; exit;
        $money=M('Users')->where('uid='.session('user_auth.uid'))->getField('money');
            $xinyong=M('Users')->where('uid='.session('user_auth.uid'))->getField('xinyong');
            if ($money+$xinyong<$zhifuprice) {
                $this->error('余额不足以支付您的订单');
            }else{
                M('Users')->where('uid='.session('user_auth.uid'))->setDec('money',$zhifuprice);
                $save=array(
                    'id'=>$id,
                    'status'=>2,
                    'payed'=>$zhifuprice,
                    'paytime'=>time(),
                );
                M('Daigou')->where('id='.$id)->save($save);
                M('DaigouGoods')->where('dgid='.$id)->setField('status',2);
                D('Record')->addrecord(session('user_auth.uid'),'-'.$zhifuprice,2,7,'代购订单 <a href="###">'.$info['dgsn'].'</a> 付款');
            }
        $this->success('代购订单支付成功',U('User/daigou','status=2'));
    }

    public function huikuan(){
         if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= M('Huikuan')->where('uid='.session('user_auth.uid'))->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $huikuan=M('Huikuan')->where('uid='.session('user_auth.uid'))->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        $this->assign('huikuanlist',$huikuan);
        $this->display();
    }
      // 支付宝直接支付代运费用
    public function zjpay(){
        $price=$_GET['price'];
        $oid=$_GET['oid'];
        $uid=session('user_auth.uid');
        $username=session('user_auth.username');
        $value["gtitle"]="神州代运用户".$username."支付运费".$price;
        $priceCount=sprintf("%01.2f",$price);//价格
        $OrdersId=$oid;
        $sysconfigfile="./Apps/Home/Conf/config.php";
        if(file_exists($sysconfigfile)){
            $config=include($sysconfigfile);
        }
        include("./Apps/Home/alipay2/config_pay_alipay.php");//支付配置文件
        //处理付款操作
        $open="window.open ('".$reqUrl."', 'newwindow');";
        
    //dump($parameter);
    //echo '<html><head><title>转到支付页面</title></head><body onLoad="document.gotopay.submit();"><form name="gotopay" action="'.$strRequestUrl.'" method="post" target="_blank">'.$hiddeninput.'</form></body></html>';
        redirect($strRequestUrl);
    }

    public function actionreturn(){
        session_start();
        include("./Apps/Home/alipay/return_url.php");//反回处理
    }
    public function notify(){
        include("./Apps/Home/alipay/notify_url.php");//返回处理
    }

    public function return2(){
        session_start();
        include("./Apps/Home/alipay2/return_url.php");//反回处理
    }
    public function notify2(){
        include("./Apps/Home/alipay2/notify_url.php");//返回处理
    }
    // 支付宝直接支付代购费用
    public function zjpaydai(){
        $price=$_GET['price'];
        $oid=$_GET['oid'];
        $uid=session('user_auth.uid');
        $username=session('user_auth.username');
        $value["gtitle"]="神州代运用户".$username."支付代购费".$price;
        $priceCount=sprintf("%01.2f",$price);//价格
        $OrdersId=$oid;
        $sysconfigfile="./Apps/Home/Conf/config.php";
        if(file_exists($sysconfigfile)){
            $config=include($sysconfigfile);
        }
        include("./Apps/Home/alipay3/config_pay_alipay.php");//支付配置文件
        //处理付款操作
        $open="window.open ('".$reqUrl."', 'newwindow');";
        
    //dump($parameter);
    //echo '<html><head><title>转到支付页面</title></head><body onLoad="document.gotopay.submit();"><form name="gotopay" action="'.$strRequestUrl.'" method="post" target="_blank">'.$hiddeninput.'</form></body></html>';
        redirect($strRequestUrl);
    }

    public function return3(){
        session_start();
        include("./Apps/Home/alipay3/return_url.php");//反回处理
    }
    public function notify3(){
        include("./Apps/Home/alipay3/notify_url.php");//返回处理
    }
}