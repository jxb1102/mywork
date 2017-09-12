<?php
namespace Admin\Controller;
use Think\Controller;
class CaiwuController extends AdminController {
    public function yinhang(){
    	$data=M('yinhang');
    		$id=I('get.id');
    		if ($id) {
    			$info=$data->find($id);
    			$this->assign('info',$info);
    		};
    		$yhlist=$data->order('id asc')->select();
    		$this->assign('yhlist',$yhlist);
        	$this->display();

    }
    public function yinhangadd(){
        $data=M('Yinhang');
        if (!I('post.title')) {
                $this->error('请填写银行名称');
            }
            if (!I('post.zhname')) {
                $this->error('请填写账户名称');
            }
            if (!I('post.zhanghao')) {
                $this->error('请填写银行账号');
            }
            if (!I('post.currency')) {
                $this->error('请选择币种');
            }
            if ($data->where('zhanghao="'.I('post.zhanghao').'"')->find()) {
                $this->error('该银行账号已经存在');
            }
            $add=array(
                'title'=>I('post.title'),
                'zhname'=>I('post.zhname'),
                'zhanghao'=>I('post.zhanghao'),
                'currency'=>I('post.currency'),
                'remark'=>I('post.remark'),
            );
            if ($data->create($add)) {
                $id=$data->add();
                if ($id>0) {
                    $this->success('添加成功',U('Caiwu/yinhang'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function yinhangedit(){
        $data=M('Yinhang');
        if (!I('post.title')) {
                $this->error('请填写银行名称');
            }
            if (!I('post.zhname')) {
                $this->error('请填写账户名称');
            }
            if (!I('post.zhanghao')) {
                $this->error('请填写银行账号');
            }
            if (!I('post.currency')) {
                $this->error('请选择币种');
            }
            $where['zhanghao']=I('post.zhanghao');
            $where['id']=array('neq',I('post.id'));
            if ($data->where($where)->find()) {
                $this->error('银行账号已经存在');
            }
            $save=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'zhname'=>I('post.zhname'),
                'zhanghao'=>I('post.zhanghao'),
                'currency'=>I('post.currency'),
                'remark'=>I('post.remark'),
            );
            if ($data->create($save)) {
                $id=$data->save();
                if ($id!==false) {
                    $this->success('编辑成功',U('Caiwu/yinhang'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function yinhangdel(){
        $id=I('post.id');
        $del=M('Yinhang')->where('id='.$id)->delete();
        if (false!==$del) {
            $this->success('银行删除成功',U('Caiwu/yinhang'));
        }else{
            $this->error('删除失败'.M('Yinhang')->getError());
        }
    }
    public function chongzhi(){
        $data=M('huikuan');
        $keyword=I('get.keyword');
            if ($keyword) {
                $map['uname']=array('like','%'.$keyword.'%');
                $map['zhanghao']=array('like','%'.$keyword.'%');
                $map['zhname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        $userlist=M('Users')->select();
        $this->assign('userlist',$userlist);
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
            $list[$key]['yinhangzhanghao']=M('Yinhang')->where('id='.$value['yinhangid'])->getField('zhanghao');
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function querenchongzhi(){
        $id=I('post.id');
        $email=I('post.email');
        $info=M('Huikuan')->find($id);
        $info['user']=M('Users')->where('uid='.$info['uid'])->find();
        if ($info) {
            if ($info['status']==1) {
                $this->error('已经充值，不能重复充值');
            }else{
                M('Users')->where('uid='.$info['uid'])->setInc('money',$info['amount']);
                M('Huikuan')->where('id='.$id)->setField('status',1);
                D('Record')->addrecord($info['uid'],$info['amount'],1,1,$info['remark']);
                $sendresult=send_mail($email,$info['user']['username'],'充值成功通知函','尊敬的'.$info['user']['username'].'：<br > 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;成功充值，请登录用户中心查看。<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;再次感谢您的支持！<br>
            ProcurementChina官方网址是：www.procurementchina.com<br>
            如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>
            投诉及建议，请发送邮箱至：complaint@procurementchina.com
            <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>
            若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br>
            <br><br>Dear '.$info['user']['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have received your payment successfully.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please check your account accordingly.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks for your support.<br>Official ProcurementChina: www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or  send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.
            ',null);
            if ($sendresult==true) {
                $this->success('充值成功,并且Email发送成功');
            }else{
                $this->success('充值成功,Email发送失败');
            }
            }
                
        }else{
            $this->error('没有此信息');
        }
    }
    public function weidaozhang(){
        $id=I('post.id');
        $email=I('post.email');
        $info=M('Huikuan')->find($id);
        $info['user']=M('Users')->where('uid='.$info['uid'])->find();
        if ($info) {
            if ($info['status']==1) {
                $this->error('已经充值，不能更改状态');
            }else{
                M('Huikuan')->where('id='.$id)->setField('status',2);
                $sendresult=send_mail($email,$info['user']['username'],'未达账通知函','尊敬的'.$info['user']['username'].'：<br > 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;充值失败，请核实您的充值账户！<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;再次感谢您的支持！<br>
            ProcurementChina官方网址是：www.procurementchina.com<br>
            如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>
            投诉及建议，请发送邮箱至：complaint@procurementchina.com
            <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>
            若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br>
            <br><br>Dear '.$info['user']['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have not received your remittance.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please check your account accordingly.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks for your support.<br>Official ProcurementChina: www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or  send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.
            ',null);
            if ($sendresult==true) {
                $this->success('设置成功,并且Email发送成功');
            }else{
                $this->success('设置成功,Email发送失败');
            }
            }
        }else{
            $this->error('没有此信息');
        }
    }
    public function daichongzhi(){
        $data=M('Daichongzhi');
        $keyword=I('get.keyword');
            if ($keyword) {
                $map['uname']=array('like','%'.$keyword.'%');
                $map['zhanghao']=array('like','%'.$keyword.'%');
                $map['zhname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
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
            $list[$key]['yinhangzhanghao']=M('Yinhang')->where('id='.$value['yinhangid'])->getField('zhanghao');
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function zhifubaoyichongzhi(){
        $id=I('post.id');
        $info=M('Daichongzhi')->find($id);
        $info['user']=M('Users')->where('uid='.$info['uid'])->find();
        if ($info) {
            if ($info['status']!=0) {
                $this->error('已处理过，不需重复处理');
            }else{
                M('Daichongzhi')->where('id='.$id)->setField('status',1);
                M('Users')->where('uid='.$info['uid'])->setInc('money',$info['zhifuamount']);
                D('Record')->addrecord($info['uid'],'-'.$info['zhifuamount'],1,3,'财付通帐号 '.I('post.zhanghao').' 付款');
                $sendresult=send_mail($info['user']['email'],$info['user']['username'],'充值成功通知函','尊敬的'.$info['user']['username'].'：<br > 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;成功充值，请登录用户中心查看。<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;再次感谢您的支持！<br>
            ProcurementChina官方网址是：www.procurementchina.com<br>
            如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>
            投诉及建议，请发送邮箱至：complaint@procurementchina.com
            <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>
            若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br>
            <br><br>Dear '.$info['user']['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have received your payment successfully.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please check your account accordingly.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks for your support.<br>Official ProcurementChina: www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or  send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.
            ',null);
            if ($sendresult==true) {
                $this->success('充值成功,并且Email发送成功');
            }else{
                $this->success('充值成功,Email发送失败');
            }
            }
                
        }else{
            $this->error('没有此信息');
        }
    }
    public function weichongzhi(){
        $id=I('post.id');
        $info=M('Daichongzhi')->find($id);
        $info['user']=M('Users')->where('uid='.$info['uid'])->find();
        if ($info) {
            if ($info['status']!=0) {
                $this->error('已处理过，不需重复处理');
            }else{
                M('Daichongzhi')->where('id='.$id)->setField('status',2);
                $sendresult=send_mail($info['user']['email'],$info['user']['username'],'未达账通知函','尊敬的'.$info['user']['username'].'：<br > 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;充值失败，请核实您的充值账户！<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;再次感谢您的支持！<br>
            ProcurementChina官方网址是：www.procurementchina.com<br>
            如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com<br>
            投诉及建议，请发送邮箱至：complaint@procurementchina.com
            <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>
            若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>
            此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br>
            <br><br>Dear '.$info['user']['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have not received your remittance.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please check your account accordingly.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks for your support.<br>Official ProcurementChina: www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or  send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.
            ',null);
            if ($sendresult==true) {
                $this->success('充值未成功,并且Email发送成功');
            }else{
                $this->success('充值未成功,Email发送失败');
            }
            }
                
        }else{
            $this->error('没有此信息');
        }
    }
    public function bghuizong(){
        $ydbg=M('YundanBaoguo');
        $stime=I('request.starttime');
        $etime=I('request.endtime');
        $starttime=strtotime($stime.' 00:00:00');
        $endtime=strtotime($etime.' 23:59:59');
        if (I('request.starttime')&&I('request.endtime')) {
            $cxwhere['paytime']=array(array('egt',$starttime),array('elt',$endtime));
        }
        $cxwhere['status']=array(array('gt',4),array('lt',9));
        $uids=$ydbg->distinct(true)->where($cxwhere)->getField('uid',true);
        $ydbginfo['voweight']=$ydbg->where($cxwhere)->Sum('voweight');
        $ydbginfo['weight']=$ydbg->where($cxwhere)->Sum('weight');
        $ydbginfo['freight']=$ydbg->where($cxwhere)->Sum('freight');
        $this->assign('ydbginfo',$ydbginfo);
        $where['uid']=array('in',$uids);
        $list=M('Users')->where($where)->select();
        foreach ($list as $key => $value) {
            $uwhere['uid']=$value['uid'];
            if (I('request.starttime')&&I('request.endtime')) {
            $uwhere['_complex']=$cxwhere;
        }
            
            $list[$key]['voweight']=$ydbg->where($uwhere)->Sum('voweight');
            $list[$key]['weight']=$ydbg->where($uwhere)->Sum('weight');
            $list[$key]['freight']=$ydbg->where($uwhere)->Sum('freight');
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function bgbaobiao(){
        $ydbg=M('YundanBaoguo');
        $uname=I('request.uname');
        if ($uname) {
            $cxwhere['uname']=array('like','%'.$uname.'%');
        }
        $stime=I('request.starttime');
        $etime=I('request.endtime');
        $starttime=strtotime($stime.' 00:00:00');
        $endtime=strtotime($etime.' 23:59:59');
        if (I('request.starttime')&&I('request.endtime')) {
            $cxwhere['fahuotime']=array(array('egt',$starttime),array('elt',$endtime));
        }
        $cxwhere['status']=array(array('gt',4),array('lt',9));
        $uids=$ydbg->distinct(true)->where($cxwhere)->getField('uid',true);
        $ydbginfo['voweight']=$ydbg->where($cxwhere)->Sum('voweight');
        $ydbginfo['weight']=$ydbg->where($cxwhere)->Sum('weight');
        $ydbginfo['freight']=$ydbg->where($cxwhere)->Sum('freight');
        $ydbginfo['chengben']=$ydbg->where($cxwhere)->Sum('chengben');
        $ydbginfo['lirun']=sprintf('%01.2f',$ydbginfo['freight']-$ydbginfo['chengben']);
        $ydbglist=$ydbg->where($cxwhere)->order('uname asc,fahuotime desc')->select();
        foreach ($ydbglist as $key => $value) {
            $ydbglist[$key]['dd']=M('Yundan')->find($value['ydid']);
        }
        $this->assign('ydbglist',$ydbglist);
        $this->assign('ydbginfo',$ydbginfo);
        if (I('request.type')=="dayin") {
            $this->display('baobiaodayin');
        }else{
            $this->display();
        }
        
    }
    public function houtaichongzhi(){
        $uid=I('post.uid');
        if (I('post.huikuantime')) {
            $hktime=strtotime(I('post.huikuantime'));
        }else{
            $hktime=time();
        }
        $amount=I('post.amount');
        $user=M('Users')->where('uid='.$uid)->find();
        $zhname=I('post.zhname')?I('post.zhname'):$user['username'];
        $add=array(
            'uid'=>$uid,
            'uname'=>$user['username'],
            'amount'=>$amount,
            'fangshi'=>'管理充值',
            'yhname'=>I('post.yhname'),
            'zhname'=>'管理员',
            'zhanghao'=>I('post.zhanghao'),
            'remark'=>I('post.remark'),
            'huikuantime'=>$hktime,
            'bizhong'=>'人民币',
            'addtime'=>time(),
            'status'=>1,
        );
        if (M('Huikuan')->create($add)) {
            $id=M('Huikuan')->add();
            if ($id>0) {
                M('Users')->where('uid='.$uid)->setInc('money',$amount);
                D('Record')->addrecord($uid,$amount,1,5,I('post.remark'));
                $sendresult=send_mail($user['email'],$user['username'],'您的充值单已成功充值到余额，感谢您的支持','亲爱的 '.$user['username'].' ,您好：<br > 您的充值单已成功充值到您的余额，请登录您个人中心查看<br> 再次感谢您的支持！',null);
            if ($sendresult==true) {
                $this->success('充值成功,并且Email发送成功');
            }else{
                $this->success('充值成功,Email发送失败');
            }
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    
}