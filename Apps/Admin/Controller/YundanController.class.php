<?php
namespace Admin\Controller;
use Think\Controller;
class YundanController extends AdminController {
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
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['sn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['consignee']=array('like','%'.$keyword.'%');
                $map['tel']=array('like','%'.$keyword.'%');
                $map['areaname']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
    	$yd=M('Yundan');
    	if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $yd->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, $REQUEST);
        // if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        // }

        $p =$page->show();
    	$list=$yd->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
    	foreach ($list as $key => $value) {
    		$list[$key]['user']=get_user($value['uid']);
            $list[$key]['dname']=M('Delivery')->where('id='.$value['deliveryid'])->getField('dname');
            $list[$key]['daoqi']='<b style="color:blue;">已到齐</b>';
            $yblist=M('Yubao')->where('ydid='.$value['id'])->select();
        foreach ($yblist as $k => $v) {
            if ($v['status']==1) {
                $list[$key]['daoqi']="<b style='color:red;'>未到齐</b>";
            }
        }
    	}
        $area=M('State')->select();
        $this->assign('area',$area);
        $this->assign('data',$REQUEST);
    	$this->assign('_page', $p? $p: '');
    	$this->assign('list',$list);
    	$this->display();
    }
    public function view($id){
        $id=I('get.id');
        $yd=M('Yundan');
        $yb=M('Yubao');
        $ydbg=M('YundanBaoguo');
        $yblist=$yb->where('ydid='.$id)->order('id desc')->select();
        foreach ($yblist as $key => $value) {
            $yblist[$key]['bginfo']=M('YundanBaoguo')->where('id='.$value['bgid'])->find();
        }
        $ydinfo=$yd->find($id);
        if (empty($ydinfo)) {
            $this->redirect('Yundan/lists');
        }
        $ydbglist=$ydbg->where('ydid='.$ydinfo['id'])->select();
        foreach ($ydbglist as $key => $value) {
            $ydbglist[$key]['yubao']=$yb->where('bgid='.$value['id'])->select();
            if ($value['banpao']==1) {
                $ydbglist[$key]['shoufeileixing']='半泡';
            }else{
                if ($value['weight']>$value['voweight']) {
                    $ydbglist[$key]['shoufeileixing']='实重';
                }else{
                    $ydbglist[$key]['shoufeileixing']='泡重';
                }
            }
        }
        $ydinfo['delivery']=M('Delivery')->find($ydinfo['deliveryid']);
        $this->assign('ydbglist',$ydbglist);
        $this->assign('ydinfo',$ydinfo);
        $this->assign('yblist',$yblist);
        $this->display();
    }
    public function queren($id){
        
        $yblist=M('Yubao')->where('ydid='.$id)->select();
        foreach ($yblist as $key => $value) {
            if ($value['status']==1) {
                $this->error('订单快递未到齐，暂不能确认订单');
                exit();
            }
        }
        M('Yundan')->where('id='.$id)->setField('status',2);
        $this->success('订单已确认');
    }
    public function qufahuo($id){
        $ydinfo=M('YundanBaoguo')->find($id);
        $ydinfo['user']=M('Users')->where('uid='.$ydinfo['uid'])->find();
        $data=array('status'=>5,'fahuotime'=>time());
        M('Yundan')->where('id='.$id.' AND status=4')->setField($data);
        M('YundanBaoguo')->where('ydid='.$id.' AND status=4 AND haspay=1')->setField($data);
        $sendresult=send_mail($ydinfo['user']['email'],'','您的订单已经发货，请及时查收，感谢您的支持','尊敬的神州代运用户:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的包裹/文件已顺利发货。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;国际快递渠道：'.$ydinfo['chuhuogs'].'国际运单号：'.$ydinfo['bgsn'].' <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您可至神州代运官网实时查询运单详情，感谢您对神州代运网的信任和支持。<br><br><br>ProcurementChina官方网址是：www.procurementchina.com<br>如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。
            <br><br><br><br>Dear ProcurementChina User：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The packages/documents you ordered have been shipped out by logistics/express department of ProcurementChina.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;International express Name:'.$ydinfo['chuhuogs'].'tracking number of International express:'.$ydinfo['bgsn'].'<br>Official ProcurementChina:www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
        if ($sendresult==true) {
                $this->success('订单发货成功,并且Email发送成功',U('lists'));
            }else{
                $this->success('订单发货成功,Email发送失败',U('lists'));
            }
    }
    public function baoguoqufahuo($id){
        $id=explode(',', $id);
        $id=array_filter($id);
        foreach ($id as  $row) {
            $data=array('status'=>5,'fahuotime'=>time());
            M('YundanBaoguo')->where('id='.$row.' AND status=4 AND haspay=1')->setField($data);
            $ydbg=M('YundanBaoguo')->find($row);
            $ydinfo=M('Yundan')->where('id='.$ydbg['ydid'])->find();
            if ($ydinfo['status']==4) {
                M('Yundan')->where('id='.$ydbg['ydid'])->setField($data);
            }
        }
        
        $this->success('包裹发货完成');
    }
    public function getyundan($id){
        $data=M('Yundan')->find($id);
        $this->ajaxReturn($data);
    }
    public function getdelivery($id){
        $areaid=M('Yundan')->where('id='.$id)->getField('areaid');
        $data=M('Delivery');
        $list=$data->where('areaid='.$areaid)->select();
        $this->ajaxReturn($list);
    }
    public function delete($id){
        if (false!==M('Yundan')->where('id='.$id)->delete()) {
            $this->success('预报信息删除成功',U('Yubao/lists'));
        }else{
            $this->error('删除失败'.M('Yubao')->getError());
        }
    }
    public function edit(){
        $data=M('Yundan');
        if (!I('post.shenbao')) {
            $this->error('请填写价值');
        }
        $areaname=M('State')->where('id='.I('post.areaid'))->getField('name');
        $list=array(
            'id'=>I('post.id'),
            'deliveryid'=>I('post.deliveryid'),
            'shenbao'=>I('post.shenbao'),
            'remark'=>I('post.remark'),
            'bgleixing'=>I('post.bgleixing'),
            'chaibao'=>I('post.chaibao'),
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
                $this->success('编辑成功',U('Yundan/lists'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function dabao(){
        $id=I('get.id');
        $yd=M('Yundan');
        $yb=M('Yubao');
        $ydbg=M('YundanBaoguo');
        $yblist=$yb->where('ydid='.$id)->order('id desc')->select();
        foreach ($yblist as $key => $value) {
            if ($value['status']<3) {
                $weichuku=1;
            }
        }
        $ydinfo=$yd->find($id);
        if (empty($ydinfo)) {
            $this->redirect('Yundan/lists');
        }
        $ydbglist=$ydbg->where('ydid='.$ydinfo['id'])->order('id desc')->select();
        foreach ($ydbglist as $key => $value) {
            $ydbglist[$key]['yubao']=$yb->where('bgid='.$value['id'])->select();
        }
        $ydinfo['delivery']=M('Delivery')->find($ydinfo['deliveryid']);
        if ($weichuku) {
            $weichuku=$weichuku;
        }else{
            $weichuku=0;
        }
        $this->assign('weichuku',$weichuku);
        $this->assign('ydbglist',$ydbglist);
        $this->assign('ydinfo',$ydinfo);
        $this->assign('yblist',$yblist);
        $this->display();
    }
    public function yichu(){
        $id=I('post.id');
        if ($id) {
            $yubao=M('Yubao')->find($id);
        $ydcount=M('Yubao')->where('ydid='.$yubao['ydid'])->count();
        if ($ydcount==1) {
            M('Yundan')->where('id='.$yubao['ydid'])->delete();
        }
        if ($yubao['status']==4) {
            $bgcount=M('Yubao')->where('bgid='.$yubao['bgid'])->count();
            if ($bgcount==1) {
                M('YundanBaoguo')->where('id='.$yubao['bgid'])->delete();
            }
            M('Yubao')->where('id='.$id)->setField('status',2);
            M('Yubao')->where('id='.$id)->setField('ydid',0);
            M('Yubao')->where('id='.$id)->setField('bgid',0);
        }
        M('Yubao')->where('id='.$id)->setField('ydid',0);
        $this->success('移除成功');
        }else{
            $this->error('信息错误');
        }
    }
    public function adddabao(){
        $ydbg=M('YundanBaoguo');
        $bgsn=trim(I('post.bgsn'));
        $weight=I('post.weight');
        $chang=I('post.chang');
        $kuan=I('post.kuan');
        $gao=I('post.gao');
        $jishu=I('post.jishu');
        $voweight=I('post.voweight');
        $kdsn=I('post.kdsn');
        $ydid=I('post.ydid');
        $freight=I('post.freight');
        if (!$bgsn) {
            $this->error('请填写运单单号。');
        }
        if (!$weight) {
            $this->error('请称重');
        }
        if (!$ydid) {
            $this->error('订单错误');
        }
        if (!$kdsn) {
            $this->error('物品不能为空');
        }
        if (!$freight) {
            $this->error('运费必须填写');
        }
        $ydinfo=M('Yundan')->find($ydid);
        $deli=M('Delivery')->find($ydinfo['deliveryid']);
        if ($weight>0&&$weight<=$deli['first_weight']) {
            $chengben=$deli['first_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['first_weight']&&$weight<=$deli['third_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['second_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['third_weight']&&$weight<=$deli['fourth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['third_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['fourth_weight']&&$weight<=$deli['fifth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['fourth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['fifth_weight']&&$weight<=$deli['sixth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['fifth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['sixth_weight']&&$weight<=$deli['seventh_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['sixth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['seventh_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['seventh_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        $yundan=M('Yundan')->find($ydid);
        $add=array(
            'uid'=>$yundan['uid'],
            'uname'=>$yundan['uname'],
            'bgsn'=>$bgsn,
            'ydid'=>$ydid,
            'weight'=>$weight,
            'chang'=>$chang,
            'kuan'=>$kuan,
            'gao'=>$gao,
            'jishu'=>$jishu,
            'voweight'=>($chang*$kuan*$gao)/$jishu,
            'addtime'=>time(),
            'freight'=>I('post.freight'),
            'chengben'=>$chengben,
            'remark'=>I('post.remark'),
            'status'=>3,
            'bgleixing'=>I('post.bgleixing'),
            'chuhuogs'=>I('post.chuhuogs'),
            'banpao'=>I('post.banpao'),
            'bpweight'=>I('post.bpweight'),
        );
        if ($ydbg->create($add)) {
            $ydbgid=$ydbg->add();
            if ($ydbgid) {
                $ybwhere['kdsn']=array('in',$kdsn);
                M('Yubao')->where($ybwhere)->setField('bgid',$ydbgid);
                M('Yubao')->where($ybwhere)->setField('status',4);
                M('Yubao')->where($ybwhere)->setField('chukutime',time());
                M('DaigouGoods')->where($ybwhere)->setField('status',6);
                M('DaigouGoods')->where($ybwhere)->setField('bgsn',$bgsn);
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
            # code...
        }else{
            $this->error('数据创建失败');
        }
    }
    public function editdabao(){
        $ydbg=M('YundanBaoguo');
        $bgsn=trim(I('post.bgsn'));
        $weight=I('post.weight');
        $chang=I('post.chang');
        $kuan=I('post.kuan');
        $gao=I('post.gao');
        $jishu=I('post.jishu');
        $voweight=I('post.voweight');
        $kdsn=I('post.kdsn');
        $ydid=I('post.ydid');
        $freight=I('post.freight');
        if (!$bgsn) {
            $this->error('请填写运单单号。');
        }
        if (!$weight) {
            $this->error('请称重');
        }
        if (!$ydid) {
            $this->error('订单错误');
        }
        if (!$kdsn) {
            $this->error('物品不能为空');
        }
        if (!$freight) {
            $this->error('运费必须填写');
        }
        $ydinfo=M('Yundan')->find($ydid);
        $deli=M('Delivery')->find($ydinfo['deliveryid']);
        if ($weight>0&&$weight<=$deli['first_weight']) {
            $chengben=$deli['first_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['first_weight']&&$weight<=$deli['third_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['second_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['third_weight']&&$weight<=$deli['fourth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['third_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['fourth_weight']&&$weight<=$deli['fifth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['fourth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['fifth_weight']&&$weight<=$deli['sixth_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['fifth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['sixth_weight']&&$weight<=$deli['seventh_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['sixth_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        if ($weight>$deli['seventh_weight']) {
            $chengben=$deli['first_cb_fee']+ceil(($weight-$deli['first_weight'])/$deli['xu_weight'])*$deli['seventh_cb_fee']+$deli['baoguan_fee']+$deli['ranyou_fee'];
        };
        $yundan=M('Yundan')->find($ydid);
        $save=array(
            'id'=>I('post.id'),
            'uid'=>$yundan['uid'],
            'uname'=>$yundan['uname'],
            'bgsn'=>$bgsn,
            'ydid'=>$ydid,
            'weight'=>$weight,
            'chang'=>$chang,
            'kuan'=>$kuan,
            'gao'=>$gao,
            'jishu'=>$jishu,
            'voweight'=>($chang*$kuan*$gao)/$jishu,
            'addtime'=>time(),
            'freight'=>I('post.freight'),
            'chengben'=>$chengben,
            'remark'=>I('post.remark'),
            'bgleixing'=>I('post.bgleixing'),
            'chuhuogs'=>I('post.chuhuogs'),
            'banpao'=>I('post.banpao'),
            'bpweight'=>I('post.bpweight'),
        );
        if ($ydbg->create($save)) {
            $ydbgid=$ydbg->save();
            if ($ydbgid!==false) {
                $ybwhere['kdsn']=array('in',$kdsn);
                M('Yubao')->where($ybwhere)->setField('bgid',I('post.id'));
                M('Yubao')->where($ybwhere)->setField('status',4);
                M('Yubao')->where($ybwhere)->setField('chukutime',time());
                M('DaigouGoods')->where($ybwhere)->setField('status',6);
                M('DaigouGoods')->where($ybwhere)->setField('bgsn',$bgsn);
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
            # code...
        }else{
            $this->error('数据创建失败');
        }
    }
    public function wanchengdabao(){
        $id=I('post.id');

        if ($id) {
            $info=M('Yundan')->where('id='.$id)->find();
            $info['user']=M('Users')->where('uid='.$info['uid'])->find();
            $ydbg=M('YundanBaoguo')->where('ydid='.$id)->select();
            foreach ($ydbg as $key => $value) {
                $freight+=$value['freight'];
            }
            M('Yundan')->where('id='.$id)->setField('status',3);
            M('Yundan')->where('id='.$id)->setField('freight',$freight);
            M('Daigou')->where('id='.$info['dgid'])->setField('status',6);
            $sendresult=send_mail($info['user']['email'],'',''.$info['sn'].'打包完成，请付款！','尊敬的'.$info['user']['username'].'：<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的订单'.$info['sn'].'已经打包完成，请至神州代运官网用户中心付款。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina官方网址是：www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br><br><br>Dear '.$info['user']['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your packages(Order No.'.$info['sn'].') are to be sent to destination,Please visit Official Website to pay for freight charge 
accordingly.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Official Website is www.procurementchina.com <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
            if ($sendresult==true) {
                $this->success('打包成功,并且Email发送成功',U('lists'));
            }else{
                $this->success('打包成功,Email发送失败',U('lists'));
            }
            
        }else{
            $this->error('打包失败');
        }
    }
    public function baoguolist(){
        $REQUEST    =   (array)I('request.');
        if ($REQUEST['status']) {
            $where['status']=$REQUEST['status'];
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['bgsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        $data=M('YundanBaoguo');
        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
            $total= $data->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }

        $p =$page->show();
        $yblist=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
        foreach ($yblist as $key => $value) {
            $yblist[$key]['user']=get_user($value['uid']);
            $yblist[$key]['dingdan']=M('Yundan')->find($value['ydid']);
            $yblist[$key]['dname']=M('Delivery')->where('id='.$yblist[$key]['dingdan']['deliveryid'])->getField('dname');
            if ($value['banpao']==1) {
                $yblist[$key]['shoufeileixing']='半泡';
            }else{
                if ($value['weight']>$value['voweight']) {
                    $yblist[$key]['shoufeileixing']='实重';
                }else{
                    $yblist[$key]['shoufeileixing']='泡重';
                }
            }
            $yblist[$key]['hastracking']=M('Tracking')->where('bgsn="'.$value['bgsn'].'"')->count();
        }
        $this->assign('data',$REQUEST);
        $this->assign('_page', $p? $p: '');
        $this->assign('list',$yblist);
        $this->display();
    }
    public function zankou($id){
        $id=explode(',', $id);
        $id=array_filter($id);
        foreach ($id as $row) {
            $info=M('YundanBaoguo')->find($row);
        if ($info['status']==3) {
            M('YundanBaoguo')->where('id='.$row)->setField('status',9);
        }elseif($info['status']==9){
            M('YundanBaoguo')->where('id='.$row)->setField('status',3);
        }
        }
        $this->success('设置成功');
        
    }

    public function exportexcel(){
        if (I('request.status')) {
            $where['status']=I('request.status');
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['sn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['consignee']=array('like','%'.$keyword.'%');
                $map['tel']=array('like','%'.$keyword.'%');
                $map['areaname']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
            if (I('request.ydids')) {
                $where['id']=array('in',I('request.ydids'));
            }
        $list=M('Yundan')->where($where)->order('status asc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['user']=get_user($value['uid']);
            $list[$key]['leixing']=getbgleixing($value['bgleixing']);
            $list[$key]['zhuangtai']=getyundanstatus($value['status']);
            $list[$key]['dname']=M('Delivery')->where('id='.$value['deliveryid'])->getField('dname');
            $list[$key]['kdlist']=M('Yubao')->where('ydid='.$value['id'])->select();
            $list[$key]['kdlistcount']=M('Yubao')->where('ydid='.$value['id'])->count();
            foreach ($list[$key]['kdlist'] as $k => $v) {
                $list[$key]['kdlist'][$k]['leixing']=getbgleixing($value['bgleixing']);
                $list[$key]['kdlist'][$k]['zhuangtai']=getyubaostatus($value['status']);
                if ($v['chaibao']==1) {
                $list[$key]['kdlist'][$k]['baozhuang']="拆包";
            }else{
                $list[$key]['kdlist'][$k]['baozhuang']="不拆包";
            }
            }
        }
        if (empty($list)){
            $this->error('没有可以导出的内容');
        }
        import("Org.Util.PHPExcel");
        $fileName = $this->fileName;
        $fileName = empty($fileName)?'订单导出-'.date('Y-m-d',time()):$fileName;
        
        $PHPExcel = new \PHPExcel();
        
        //设置基本信息
        $PHPExcel->getProperties()->setCreator("zzqss")
        ->setLastModifiedBy("zzqss")
        ->setTitle("郑州全搜索")
        ->setSubject("全搜索")
        ->setDescription("")
        ->setKeywords("全搜索")
        ->setCategory("");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle($fileName);
        
        //存储Excel数据源到其他工作薄
        $PHPExcel->createSheet();
        $a=1;
        $k = 0;
        foreach($list as $ke => $v){
            
        //填入主标题
        $PHPExcel->getActiveSheet()->setCellValue('A'.($ke+$a), C('DOMAIN_TITLE').'订单详细 - '.$v['zhuangtai']);
        //填入副标题
        if ($v['remark']) {
            $PHPExcel->getActiveSheet()->setCellValue('A'.($ke+$a+1), '所属用户：'.$v['user']['username'].'     订单号码：'.$v['sn'].'    配送方式：'.$v['dname'].'    订单日期：'.date("Y/m/d",$v['addtime'])."\n".'订单备注：'.$v['remark']);
        }else{
            $PHPExcel->getActiveSheet()->setCellValue('A'.($ke+$a+1), '所属用户：'.$v['user']['username'].'     订单号码：'.$v['sn'].'    配送方式：'.$v['dname'].'    订单日期：'.date("Y/m/d",$v['addtime']));
        }
        
        $PHPExcel->getActiveSheet()->setCellValue('A'.($ke+$a+2), '收货信息：'.$v['consignee'].'('.$v['tel'].')'.'    '.$v['areaname'].'    '.$v['address'].'('.$v['zipcode'].')');
        
        //填入表头
        $PHPExcel->getActiveSheet()->setCellValue('A'.($ke+$a+3), '序号');
        $PHPExcel->getActiveSheet()->setCellValue('B'.($ke+$a+3), '快递单号');
        $PHPExcel->getActiveSheet()->setCellValue('C'.($ke+$a+3), '快递名称');
        $PHPExcel->getActiveSheet()->setCellValue('D'.($ke+$a+3), '包裹名称');
        $PHPExcel->getActiveSheet()->setCellValue('E'.($ke+$a+3), '申报价值');
        $PHPExcel->getActiveSheet()->setCellValue('F'.($ke+$a+3), '包裹类型');
        $PHPExcel->getActiveSheet()->setCellValue('G'.($ke+$a+3), '备注');
        $PHPExcel->getActiveSheet()->setCellValue('H'.($ke+$a+3), '状态');
        $PHPExcel->getActiveSheet()->setCellValue('I'.($ke+$a+3), '时间');
        $PHPExcel->getActiveSheet()->setCellValue('J'.($ke+$a+3), '是否拆包');
        
        //填入列表
        
        
        foreach ($v['kdlist'] as $key => $value){
            
            $PHPExcel->getActiveSheet()->setCellValue('A'.($key+$k+$a+4), ($key+1));
            $PHPExcel->getActiveSheet()->setCellValue('B'.($key+$k+$a+4), ' '.$value['kdsn']);
            //$PHPExcel->getActiveSheet()->getStyle('B'.($key+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $PHPExcel->getActiveSheet()->setCellValue('C'.($key+$k+$a+4), $value['kdname']);

            $PHPExcel->getActiveSheet()->setCellValue('D'.($key+$k+$a+4), $value['bgname']);
            
            $PHPExcel->getActiveSheet()->setCellValue('E'.($key+$k+$a+4), $value['shenbao']);
            $PHPExcel->getActiveSheet()->getStyle('E'.($key+$k+$a+4))->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $PHPExcel->getActiveSheet()->setCellValue('F'.($key+$k+$a+4), $value['leixing']);
            

            $PHPExcel->getActiveSheet()->setCellValue('G'.($key+$k+$a+4), $value['remark']);
            
            $PHPExcel->getActiveSheet()->setCellValue('H'.($key+$k+$a+4), $value['zhuangtai']);
            $PHPExcel->getActiveSheet()->setCellValue('I'.($key+$k+$a+4), date('Y-m-d',$value['addtime']));
            $PHPExcel->getActiveSheet()->setCellValue('J'.($key+$k+$a+4), $value['baozhuang']);
            //设置每一行行高
            $PHPExcel->getActiveSheet()->getRowDimension($key+$k+$a+4)->setRowHeight(20);
            //设置单元格边框
            $PHPExcel->getActiveSheet()->getStyle('A'.($key+$k+$a+4).':J'.($key+$k+$a+4))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $PHPExcel->getActiveSheet()->getStyle('A'.($key+$k+$a+4).':J'.($key+$k+$a+4))->getFont()->setSize(10);
        }
        //合并单元格
        $PHPExcel->getActiveSheet()->mergeCells('A'.($ke+$a).':J'.($ke+$a));
        $PHPExcel->getActiveSheet()->mergeCells('A'.($ke+$a+1).':J'.($ke+$a+1));
        $PHPExcel->getActiveSheet()->mergeCells('A'.($ke+$a+2).':J'.($ke+$a+2));
        //行高
        $PHPExcel->getActiveSheet()->getRowDimension($ke+$a)->setRowHeight(35);
        if ($v['remark']) {
            $PHPExcel->getActiveSheet()->getRowDimension($ke+$a+1)->setRowHeight(30);
        }else{
            $PHPExcel->getActiveSheet()->getRowDimension($ke+$a+1)->setRowHeight(20);
        }
        
        $PHPExcel->getActiveSheet()->getRowDimension($ke+$a+2)->setRowHeight(20);
        $PHPExcel->getActiveSheet()->getRowDimension($ke+$a+3)->setRowHeight(20);
        //设置居中
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a).':J'.($ke+$a))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置字体样式
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a))->getFont()->setName('微软雅黑');
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a))->getFont()->setSize(12);
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a))->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a+3).':J'.($ke+$a+3))->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a+1).':J'.($ke+$a+1))->getFont()->setSize(10);
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a+2).':J'.($ke+$a+2))->getFont()->setSize(10);
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a+3).':J'.($ke+$a+3))->getFont()->setSize(10);
        //设置单元格边框
        $PHPExcel->getActiveSheet()->getStyle('A'.($ke+$a+3).':J'.($ke+$a+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        
        
        $k++;
        $a+=$v['kdlistcount']+4;
        //设置自动换行
        $PHPExcel->getActiveSheet()->getStyle('A1:J'.($ke+$a))->getAlignment()->setWrapText(true);
        //所有垂直居中
        $PHPExcel->getActiveSheet()->getStyle('A1:J'.($ke+$a))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

    }
        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+3), '序号');
        // $PHPExcel->getActiveSheet()->setCellValue('B'.($k+3), '快递单号');
        // $PHPExcel->getActiveSheet()->setCellValue('C'.($k+3), '快递名称');
        // $PHPExcel->getActiveSheet()->setCellValue('D'.($k+3), '包裹名称');
        // $PHPExcel->getActiveSheet()->setCellValue('E'.($k+3), '品牌');
        // $PHPExcel->getActiveSheet()->setCellValue('F'.($k+3), '单位');
        // $PHPExcel->getActiveSheet()->setCellValue('G'.($k+3), '数量');
        // $PHPExcel->getActiveSheet()->setCellValue('H'.($k+3), '单价');
        // $PHPExcel->getActiveSheet()->setCellValue('I'.($k+3), $list['jine']);
        // $PHPExcel->getActiveSheet()->getStyle('I'.($k+3))->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+4), '备注：'.$list['cgbeizhu']);
        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+5), '经办人：'.$list['add_by']);
        // $PHPExcel->getActiveSheet()->setCellValue('D'.($k+5), '采购签字：_______________         验收签字：_______________        ');
        
        //合并单元格
        
        
        // $PHPExcel->getActiveSheet()->mergeCells('A'.($k+4).':I'.($k+4));
        // $PHPExcel->getActiveSheet()->mergeCells('A'.($k+5).':C'.($k+5));
        // $PHPExcel->getActiveSheet()->mergeCells('D'.($k+5).':I'.($k+5));
        
        //设置单元格宽度
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11);

        
        //设置表头行高
        // $PHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(35);
        // $PHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(22);
        // $PHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
        //$PHPExcel->getActiveSheet()->getRowDimension(($k+3))->setRowHeight(20);
        // $PHPExcel->getActiveSheet()->getRowDimension(($k+4))->setRowHeight(22);
        // $PHPExcel->getActiveSheet()->getRowDimension(($k+5))->setRowHeight(22);
        
        
        //设置居中
        //$PHPExcel->getActiveSheet()->getStyle('A1:I'.($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        

        //页边距
        $sheet = $PHPExcel->getActiveSheet();
        $pageMargins = $sheet->getPageMargins();
       //$margin = '0.5/0.7'; 
        $pageMargins->setTop(0);    //上边距
        $pageMargins->setBottom(0.21); //下
        $pageMargins->setLeft(0.2);   //左
        $pageMargins->setRight(0.2);  //右    

        
        // admin_log('导出采购单编号'.$list['cgsn'].'的Excel文件');
        //保存为2003格式
        $objWriter = new \PHPExcel_Writer_Excel5($PHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        
        //多浏览器下兼容中文标题
        $encoded_filename = urlencode($fileName);
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
        }
        
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
    public function dayinlist(){
        if (I('request.status')) {
            $where['status']=I('request.status');
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['sn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['consignee']=array('like','%'.$keyword.'%');
                $map['tel']=array('like','%'.$keyword.'%');
                $map['areaname']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        if (I('request.ydids')) {
            $where['id']=array('in',I('request.ydids'));
        }
        $list=M('Yundan')->where($where)->order('status asc,id desc')->select();
        foreach ($list as $key => $value) {
        $list[$key]['user']=get_user($value['uid']);
            $list[$key]['leixing']=getbgleixing($value['bgleixing']);
            $list[$key]['zhuangtai']=getyundanstatus($value['status']);
            $list[$key]['dname']=M('Delivery')->where('id='.$value['deliveryid'])->getField('dname');
            $list[$key]['kdlist']=M('Yubao')->where('ydid='.$value['id'])->select();
            $list[$key]['kdlistcount']=M('Yubao')->where('ydid='.$value['id'])->count();
            foreach ($list[$key]['kdlist'] as $k => $v) {
                $list[$key]['kdlist'][$k]['leixing']=getbgleixing($value['bgleixing']);
                $list[$key]['kdlist'][$k]['zhuangtai']=getyubaostatus($value['status']);
                if ($v['chaibao']==1) {
                $list[$key]['kdlist'][$k]['baozhuang']="拆包";
            }else{
                $list[$key]['kdlist'][$k]['baozhuang']="不拆包";
            }
            }
        }
        if (empty($list)){
            $this->error('没有可以打印的内容');
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function baoguodayinlist(){
        $REQUEST    =   (array)I('request.');
        if ($REQUEST['status']) {
            $where['status']=$REQUEST['status'];
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['bgsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        if (I('request.ydids')) {
            $where['id']=array('in',I('request.ydids'));
        }
        $data=M('YundanBaoguo');

        $yblist=$data->where($where)->order('status asc,id desc')->select();
        foreach ($yblist as $key => $value) {
            $yblist[$key]['user']=get_user($value['uid']);
            $yblist[$key]['dingdan']=M('Yundan')->find($value['ydid']);
            $yblist[$key]['dname']=M('Delivery')->where('id='.$yblist[$key]['dingdan']['deliveryid'])->getField('dname');
        }
        if (empty($yblist)){
            $this->error('没有可以打印的内容');
        }
        $this->assign('data',$REQUEST);
        $this->assign('list',$yblist);
        $this->display();
    }
    public function dayinyundan(){
        $id=I('request.ydid');
        $info=M('Yundan')->find($id);
        if ($info) {
            $delivery=M('Delivery')->find($info['deliveryid']);
            $geshi=M('DeliveryGeshi')->find($delivery['geshiid']);
            $content=str_replace('{$info.consignee}',$info['consignee'],$geshi['content']);
            $content=str_replace('{$info.address}',$info['address'],$content);
            $content=str_replace('{$info.tel}',$info['tel'],$content);
            $content=str_replace('{$info.zipcode}',$info['zipcode'],$content);
            $content=str_replace('{$info.areaname}',$info['areaname'],$content);
            $content=str_replace('{$info.ddsn}',$info['sn'],$content);
            $this->assign('content',$content);
            $this->assign('info',$info);
            $this->display();
        }else{
            $this->error('没有可以打印的内容');
        }
    }
    public function paydingdan($ydid){
        $ydinfo=M('Yundan')->find($ydid);
        $bglist=M('YundanBaoguo')->where('ydid='.$ydid.' AND status=3 AND haspay=0')->select();
        $price=M('YundanBaoguo')->where('ydid='.$ydid.' AND status=3 AND haspay=0')->sum('freight');
        $money=M('Users')->where('uid='.$ydinfo['uid'])->getField('money');
        $xinyong=M('Users')->where('uid='.$ydinfo['uid'])->getField('xinyong');
        if ((float)$price<=$money+$xinyong) {
            foreach ($bglist as $key => $value) {
                $ybag=array(
                    'id'=>$value['id'],
                    'haspay'=>1,
                    'status'=>4,
                    'paytime'=>time(),
                );
                M('YundanBaoguo')->save($ybag);
                M('Users')->where('uid='.$value['uid'])->setDec('money',$value['freight']);
                D('Record')->addrecord($value['uid'],'-'.$value['freight'],2,2,'国际包裹 <a href="###">'.$value['bgsn'].'</a> 付款');
            }
            if ($ydinfo['status']<4) {
                M('Yundan')->where('id='.$ydid)->setField('status',4);
            }
            M('Yundan')->where('id='.$ydid)->setField('payed',1);
            M('Yundan')->where('id='.$ydid)->setField('payedby',1);
            $this->success('订单付款成功');
        }else{
            $this->error('用户余额不足 '.$price.'，支付失败');
        }
    }
    public function paybaoguo($id){
        $where['id']=array('in',$id);
        $list=M('YundanBaoguo')->where($where)->select();
        foreach ($list as $key => $value) {
            $money=M('Users')->where('uid='.$value['uid'])->getField('money');
            $xinyong=M('Users')->where('uid='.$value['uid'])->getField('xinyong');
            if ($value['freight']<=$money+$xinyong) {
                $bgsave=array(
                    'id'=>$value['id'],
                    'haspay'=>1,
                    'status'=>4,
                    'paytime'=>time(),
                    'payedby'=>1,
                );
                M('YundanBaoguo')->save($bgsave);
                M('Users')->where('uid='.$value['uid'])->setDec('money',$value['freight']);
                D('Record')->addrecord($value['uid'],'-'.$value['freight'],2,2,'国际包裹 <a href="###">'.$value['bgsn'].'</a> 付款');
                $ydinfo=M('Yundan')->where('uid='.$value['uid'].' AND id='.$value['ydid'])->find();
                $bgcount=M('YundanBaoguo')->where('ydid='.$value['ydid'])->count();
                $paycount=M('YundanBaoguo')->where('ydid='.$value['ydid'].' AND haspay=1')->count();
                if ($bgcount>$paycount) {
                    if ($ydinfo['status']<4) {
                        M('Yundan')->where('id='.$value['ydid'])->setField('status',4);
                    }
                    $wwhe['ydid']=$value['ydid'];
                    $wwhe['haspay']=array('neq',1);
                    M('YundanBaoguo')->where($wwhe)->setField('secpay',1);
                    M('Yundan')->where('id='.$value['ydid'])->setField('payed',2);
                }else{
                    if ($ydinfo['status']<4) {
                        M('Yundan')->where('id='.$value['ydid'])->setField('status',4);
                    }
                    M('Yundan')->where('id='.$value['ydid'])->setField('payed',1);
                }
            }else{
                $this->error('用户'.$value['uname'].'余额不足，'.$value['bgsn'].'未支付成功');
            }
        }
        $this->success('包裹支付成功');
    }
    public function chexiaodd(){
        $id=I('post.id');
        $ddinfo=M('Yundan')->find($id);
        if ($ddinfo) {
            M('YundanBaoguo')->where('ydid='.$id)->delete();
            $ybdata=array(
                'status'=>1,
                'ydid'=>0,
                'bgid'=>0,
            );  
            M('Yubao')->where('ydid='.$id)->save($ybdata);
            M('Yundan')->where('id='.$id)->delete();
            $this->success('订单撤销成功，运单已删除，预报已设置成在途');
        }else{
            $this->error('订单错误');
        }
    }
    public function getbaoguo($id){
        $data=M('YundanBaoguo')->find($id);
        $this->ajaxReturn($data);
    }
    public function editbaoguo(){
        $data=M('YundanBaoguo');
        if ($post=$data->create()) {
            if (empty($_POST['banpao'])) {
                $post['banpao']=0;
                $post['bpweight']=0;
            }
            if (false!==$data->save($post)) {
                $this->success('更新成功');
            }else{
                $this->error('更新错误');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function tracking(){
        $bgsn=I('request.bgsn');
        $where['bgsn']=trim($bgsn);
        $list=M('Tracking')->where($where)->order('addtime desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
    public function trackingadd(){
        $data=I('post.');
        $addtime=strtotime($data['riqi'].' '.$data['xiaoshi'].':'.$data['fenzhong']);
        $add=array(
            'bgsn'=>$data['bgsn'],
            'addtime'=>$addtime,
            'context'=>$data['context'],
        );
        $id=M('Tracking')->add($add);
        if ($id>0) {
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }
    public function trackingdel(){
        $id=I('post.id');
        $del=M('Tracking')->where('id='.$id)->delete();
        if ($del) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    public function gettracking(){
        $bgsn=I('request.bgsn');
        $list=M('Tracking')->where('bgsn="'.$bgsn.'"')->order('addtime desc')->select();
        $track='';
        foreach ($list as $key => $value) {
            $track .= '<div>'.date('Y-m-d H:i',$value['addtime']).'&nbsp;&nbsp;'.$value['context'].'</div>';
        }
        $data=array(
            'track'=>$track,
            'bgsn'=>$bgsn,
        );

        $this->ajaxReturn($data);
    }
}