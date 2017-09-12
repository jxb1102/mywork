<?php

require_once(dirname(__FILE__)."/alipay_config.php");
require_once(dirname(__FILE__)."/alipay_notify.php");
//判断用户是否登录
if(!empty($_USERS)){ 

}
$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();

//获取支付宝的反馈参数
$dingdan					= $_GET['out_trade_no'];		//获取订单号
$total_fee				= $_GET['total_fee'];    		//获取总价格
 
$receive_name    	= $_GET['receive_name'];  	//获取收货人姓名
$receive_address 	= $_GET['receive_address']; //获取收货人地址
$receive_zip     	= $_GET['receive_zip'];  		//获取收货人邮编
$receive_phone   	= $_GET['receive_phone']; 	//获取收货人电话
$receive_mobile  	= $_GET['receive_mobile']; 	//获取收货人手机
if($verify_result) {
	if($_GET['trade_status'] == 'TRADE_FINISHED' ||$_GET['trade_status'] == 'TRADE_SUCCESS'){
		//支付成功处理更新数据库操作
	    $bgid=$dingdan;
        $where['id']=array('in',$bgid);
        $yuninfo=M('YundanBaoguo')->where($where)->find();
        $ddid=$yuninfo['ydid'];
        $uid=$yuninfo['uid'];
        $info=M('Yundan')->where('uid='.$uid.' AND id='.$ddid)->find();
        $bginfo=M('YundanBaoguo')->where($where)->select();
        foreach ($bginfo as $key => $value) {
           $ybag=array(
                'id'=>$value['id'],
                'haspay'=>1,
                'status'=>4,
                'paytime'=>time(),
            );
          M('YundanBaoguo')->save($ybag);
        }
        $bgcount=M('YundanBaoguo')->where('uid='.$uid.' AND ydid='.$ddid)->count();
        $paycount=M('YundanBaoguo')->where('uid='.$uid.' AND ydid='.$ddid.' AND haspay=1')->count();
        if ($bgcount>$paycount) {
	         if ($info['status']<4) {
	           M('Yundan')->where('id='.$ddid)->setField('status',4);
	        }
	        $wwwh['uid']=$uid;
	        $wwwh['ydid']=$ddid;
	        $wwwh['haspay']=array('neq',1);
	        M('YundanBaoguo')->where($wwwh)->setField('secpay',1);
	        M('Yundan')->where('id='.$ddid)->setField('payed',2);
	        $this->redirect('User/dingdan','',2,'<font color="red",size=6>部分付款已成功！</font>');
        }else{
            if ($info['status']<4) {
                M('Yundan')->where('id='.$ddid)->setField('status',4);
            }
            M('Yundan')->where('id='.$ddid)->setField('payed',1);
            $money=$total_fee;
            M('Users')->where('uid='.$uid)->setInc('score',$money*C('SCORELV'));
            M('Users')->where('uid='.$uid)->setInc('huascore',$money*C('SCORELV'));
            $addrecordd=D('Record')->addrecordscore($uid,$money*C('SCORELV'),1,8,'支付订单获得积分');
            $this->redirect('User/dingdan','',2,'<font color="red",size=6>付款已成功！</font>');
        }
			//$this->message("支付成功！",U("User/index"));
		
	}else{
		//输出支付失败提示
		echo 'error';
		$this->redirect('User/paydingdan');
		//$this->message("支付未完成！","User/chongzhi");
	
	}
}else{
	//输出支付失败提示
	echo 'success';
	$this->redirect('User/paydingdan');
	//$this->message("支付成功！",U("User/index"));
	
}
//日志消息,把支付宝反馈的参数记录下来
function  log_result($word) { 
	$fp = fopen("log.txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."：执行日期：".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
}
?>