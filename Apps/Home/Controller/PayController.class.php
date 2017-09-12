<?php
namespace Home\Controller;
use Think\Controller;
class PayController extends Controller {
    public function ceshi(){
        $this->redirect('User/index');
    }
    public function paypal($price,$orderid=null){
        $config=$this->paypalconfig();
        $uid=session('user_auth.uid');
    	$price = sprintf("%01.2f", $price);
        if ($orderid) {
            $subject=L('order')."：".$orderid." ".L('go_payment');
        }else{
            $subject="ProcurementChina Service";
        }
        
        $reval  = "";
        $reval .= "<input type=\"hidden\" name=\"cmd\" value=\"{$config['cmd']}\" />          \n";
        $reval .= "<input type=\"hidden\" name=\"return\" value=\"{$config['success_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"notify_url\" value=\"{$config['notify_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"business\" value=\"{$config['business']}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_name\" value=\"{$subject}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_number\" value=\"{$uid}\" /> \n";
        $reval .= "<input type=\"hidden\" name=\"rm\" value=\"2\" />\n";
        $reval .= "<input type=\"hidden\" name=\"charset\" value=\"utf-8\" />\n";
        $reval .= "<input type=\"hidden\" name=\"currency_code\" value=\"{$config['currency_code']}\">\n";
        $reval .= "<input type=\"hidden\" name=\"invoice\" value=\"{$orderid}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"amount\" value=\"{$price}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"cpp_header_image\" value=\"http://www.procurementchina.com/Public/Home/default/images/logo.jpg\">\n";
        $hiddeninput=$reval;
            echo '<html>
            <head>
               <title>'.L("go_pay_page").'</title>
            </head>
            <meta charset="utf-8" />
            <body onLoad="document.paypalpay.submit();">
                <form name="paypalpay" action="'.$config['url'].'" method="post">
                '.$hiddeninput.'
                </form>
                正在建立链接中，请稍等...
            </body>
            </html>';
            exit;
    }
    private function paypalconfig(){
        //$config['url']='https://www.paypal.com/cgi-bin/webscr';
        $config['url']='https://www.sandbox.paypal.com/cgi-bin/webscr';
        $config['success_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_return_url');
        $config['notify_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_notify_url');
        $config['currency_code']="USD"; //[USD,GBP,JPY,CAD,EUR]
        $config['cmd']="_xclick";
        $config['business']=C('PAYPAL_BUSINESS');
        return $config;
    }
    public function paypal_notify_url(){
        $config=$this->paypalconfig();
        $data=I('request.');
        $tmpAr = array_merge($data, array("cmd" => "_notify-validate"));
        $postFieldsAr = array();
        foreach ($tmpAr as $name => $value) {
            $postFieldsAr[] = "$name=$value";
        }
        $ResponseAr=$this->PPHttpPost($config['url'],implode("&", $postFieldsAr), false);
        if (!$ResponseAr['status']) {
            exit();
        }

        $money=floatval($data['mc_gross']);
        $money=($money*(1-C('SUN'))-C('MEIYUAN'))*floatval(C('MAIN_USD_HUILV'));
        $money=round($money,2);
        $uid=intval($data['item_number']);

        $changemoney=M('Users')->where('uid='.$uid)->setInc('money',$money);
        $addrecordd=D('Record')->addrecord($uid,$money,1,6,$data['item_name']);
         $this->redirect('User/index','',2,'<font color="red",size=6>付款已成功！</font>');
        if ($data['invoice']) {
            
        }
        
    }
    public function paypal_return_url(){
        $this->redirect('User/index','',2,'<font color="red",size=6>Payment Successful!</font>');
        $this->display();
    }
    private function PPHttpPost($url_, $postFields_, $parsed_)
    {
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url_);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postFields_);

        //getting response from server
        $httpResponse = curl_exec($ch);

        if(!$httpResponse) {
            return array("status" => false, "error_msg" => curl_error($ch), "error_no" => curl_errno($ch));
        }

        if(!$parsed_) {
            return array("status" => true, "httpResponse" => $httpResponse);
        }

        $httpResponseAr = explode("\n", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if(0 == sizeof($httpParsedResponseAr)) {
            $error = "Invalid HTTP Response for POST request($postFields_) to $url_.";
            return array("status" => false, "error_msg" => $error, "error_no" => 0);
        }
        return array("status" => true, "httpParsedResponseAr" => $httpParsedResponseAr);

    }
      //paypal 直接支付代购费用
    public function paypal2($price,$oid){
        $config=$this->paypalconfig2();
        $uid=session('user_auth.uid');
        $yid=$oid;
        $price=($price/C('MAIN_USD_HUILV')+C('MEIYUAN'))/(1-C('SUN'));
        $price = sprintf("%01.2f", $price);
        if ($orderid) {
            $subject=L('order')."：".$orderid." ".L('go_payment');
        }else{
            $subject="ProcurementChina Service";
        }
        
        $reval  = "";
        $reval .= "<input type=\"hidden\" name=\"cmd\" value=\"{$config['cmd']}\" />          \n";
        $reval .= "<input type=\"hidden\" name=\"return\" value=\"{$config['success_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"notify_url\" value=\"{$config['notify_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"business\" value=\"{$config['business']}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_name\" value=\"{$subject}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_number\" value=\"{$yid}\" /> \n";
        $reval .= "<input type=\"hidden\" name=\"rm\" value=\"2\" />\n";
        $reval .= "<input type=\"hidden\" name=\"charset\" value=\"utf-8\" />\n";
        $reval .= "<input type=\"hidden\" name=\"currency_code\" value=\"{$config['currency_code']}\">\n";
        $reval .= "<input type=\"hidden\" name=\"invoice\" value=\"{$orderid}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"amount\" value=\"{$price}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"cpp_header_image\" value=\"http://www.procurementchina.com/Public/Home/default/images/logo.jpg\">\n";
        $hiddeninput=$reval;
            echo '<html>
            <head>
               <title>'.L("go_pay_page").'</title>
            </head>
            <meta charset="utf-8" />
            <body onLoad="document.paypalpay.submit();">
                <form name="paypalpay" action="'.$config['url'].'" method="post">
                '.$hiddeninput.'
                </form>
                建立链接中，请稍等...
            </body>
            </html>';
            exit;
    }

     private function paypalconfig2(){
        $config['url']='https://www.paypal.com/cgi-bin/webscr';
        //$config['url']='https://www.sandbox.paypal.com/cgi-bin/webscr';
        $config['success_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_return_url2');
        $config['notify_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_notify_url2');
        $config['currency_code']="USD"; //[USD,GBP,JPY,CAD,EUR]
        $config['cmd']="_xclick";
        $config['business']=C('PAYPAL_BUSINESS');
        return $config;
    }

    public function paypal_notify_url2(){
        $config=$this->paypalconfig2();
        $data=I('request.');
        $tmpAr = array_merge($data, array("cmd" => "_notify-validate"));
        $postFieldsAr = array();
        foreach ($tmpAr as $name => $value) {
            $postFieldsAr[] = "$name=$value";
        }

        $ResponseAr=$this->PPHttpPost($config['url'],implode("&", $postFieldsAr), false);



        if (!$ResponseAr['status']) {
            exit();
        }
        $money=floatval($data['mc_gross'])-floatval($data['mc_fee']);
        $id=intval($data['item_number']);
        $save=array(
                    'id'=>$id,
                    'status'=>2,
                    'payed'=>$money,
                    'paytime'=>time(),
                );
        M('Daigou')->save($save);
        M('DaigouGoods')->where('dgid='.$id)->setField('status',2);
        $this->redirect('User/daigou','',2,'<font color="red",size=6>付款已成功！</font>');
        if ($data['invoice']) {
            
        }
        
    }

    public function paypal_return_url2(){
        $this->redirect('User/daigou','',2,'<font color="red",size=6>Payment Successful!</font>');
        $this->display();
    }
   // paypal 直接支付代运费用
    public function paypal3($price,$oid){
        $config=$this->paypalconfig3();
        $price=($price/C('MAIN_USD_HUILV')+C('MEIYUAN'))/(1-C('SUN'));
        $price = sprintf("%01.2f", $price);
        if ($orderid) {
            $subject=L('order')."：".$orderid." ".L('go_payment');
        }else{
            $subject="ProcurementChina Service";
        }
        
        $reval  = "";
        $reval .= "<input type=\"hidden\" name=\"cmd\" value=\"{$config['cmd']}\" />          \n";
        $reval .= "<input type=\"hidden\" name=\"return\" value=\"{$config['success_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"notify_url\" value=\"{$config['notify_url']}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"business\" value=\"{$config['business']}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_name\" value=\"{$subject}\" />    \n";
        $reval .= "<input type=\"hidden\" name=\"item_number\" value=\"{$oid}\" /> \n";
        $reval .= "<input type=\"hidden\" name=\"rm\" value=\"2\" />\n";
        $reval .= "<input type=\"hidden\" name=\"charset\" value=\"utf-8\" />\n";
        $reval .= "<input type=\"hidden\" name=\"currency_code\" value=\"{$config['currency_code']}\">\n";
        $reval .= "<input type=\"hidden\" name=\"invoice\" value=\"{$orderid}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"amount\" value=\"{$price}\" />\n";
        $reval .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
        $reval .= "<input type=\"hidden\" name=\"cpp_header_image\" value=\"http://www.procurementchina.com/Public/Home/default/images/logo.jpg\">\n";
        $hiddeninput=$reval;
            echo '<html>
            <head>
               <title>'.L("go_pay_page").'</title>
            </head>
            <meta charset="utf-8" />
            <body onLoad="document.paypalpay.submit();">
                <form name="paypalpay" action="'.$config['url'].'" method="post">
                '.$hiddeninput.'
                </form>
                建立链接中，请稍等...
            </body>
            </html>';
            exit;
    }

     private function paypalconfig3(){
        //$config['url']='https://www.paypal.com/cgi-bin/webscr';
        $config['url']='https://www.sandbox.paypal.com/cgi-bin/webscr';
        $config['success_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_return_url3');
        $config['notify_url']='http://'.C('DOMAIN_URL').U('Pay/paypal_notify_url3');
        $config['currency_code']="USD"; //[USD,GBP,JPY,CAD,EUR]
        $config['cmd']="_xclick";
        $config['business']=C('PAYPAL_BUSINESS');
        return $config;
    }

    public function paypal_notify_url3(){
        $config=$this->paypalconfig3();
        $data=I('request.');
        $tmpAr = array_merge($data, array("cmd" => "_notify-validate"));
        $postFieldsAr = array();
        foreach ($tmpAr as $name => $value) {
            $postFieldsAr[] = "$name=$value";
        }

        $ResponseAr=$this->PPHttpPost($config['url'],implode("&", $postFieldsAr), false);
        if (!$ResponseAr['status']) {
            exit();
        }
        $bgid=$data['item_number'];
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
            $money=floatval($data['mc_gross']);
            $money=($money*(1-C('SUN'))-C('MEIYUAN'))*floatval(C('MAIN_USD_HUILV'));
            $money=round($money,2);
            M('Users')->where('uid='.$uid)->setInc('score',$money*C('SCORELV'));
            M('Users')->where('uid='.$uid)->setInc('huascore',$money*C('SCORELV'));
            $addrecordd=D('Record')->addrecordscore($uid,$money*C('SCORELV'),1,8,'支付订单获得积分');
            $this->redirect('User/dingdan','',2,'<font color="red",size=6>付款已成功！</font>');
        }
        if ($data['invoice']) {
            
        }
    }

    public function paypal_return_url3(){
        $this->redirect('User/dingdan','',2,'<font color="red",size=6>Payment Successful!</font>');
        $this->display();
    }

}