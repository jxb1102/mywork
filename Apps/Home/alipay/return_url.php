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
		    
		$us=D('Users');
			$userinfo=split("_",$dingdan);
			$where['uid']=array("eq",$userinfo[0]);
            $usmonry=$us->where($where)->getField('money')+$total_fee;
            $data['money']=$usmonry;
			$us->where($where)->save($data);
			$users=$us->where('uid='.$userinfo[0])->select();
		    $balance=floatval($users[0]['money'])+floatval($total_fee);
			$addrecordd=D('Record')->addrecord($userinfo[0],$total_fee,1,9,'支付宝充值');
			$this->redirect('User/index','',2,'<font size=6 color="red">付款已成功！</font>');
			//$this->message("支付成功！",U("User/index"));
		
	}else{
		//输出支付失败提示
		echo 'error';
		$this->redirect('User/chongzhi','',2,'<font size=6 color="red">付款失败！</font>');
		//$this->message("支付未完成！","User/chongzhi");
	
	}
}else{
	//输出支付失败提示
	echo 'success';
	$this->redirect('User/chongzhi');
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