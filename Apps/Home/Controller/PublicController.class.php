<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller {
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
            $cartcount=M('Cart')->where('uid='.session('user_auth.uid'))->count();
            $this->assign('cartcount',$cartcount?$cartcount:0);
        }else{
            $cartcount=M('Cart')->where('anonymous="'.$anonymous.'"')->count();
            $this->assign('cartcount',$cartcount?$cartcount:0);
        }
        $cate=M('Category');
        $whe['status']=0;
        $whe['lang']=$langcoo;
        $groy=$cate->field('id,title')->where($whe)->limit(5)->order('id asc')->select();
         foreach ($groy as $key => $value) {
             $art=M('Article');
             $wh['cid']=$value['id'];
             $mat=$art->field('id,title')->where($wh)->limit(6)->select();
             $groy[$key]['article']=$mat;
         }
         $this->assign('cateaa',$groy);
    }
	public function _empty(){
		$this->redirect('Index/index');
	}
    public function login(){
		if(is_login()){

                $this->redirect('Index/index');

            }else{
            $backurl=base64_decode(I('get.url'));
            $this->assign('backurl',$backurl);
    	$this->display();
    }
    }
    public function verify(){
        $config =    array(   
         'fontSize'    =>    12.5,    // 验证码字体大小   
         'length'      =>    4,     // 验证码位数   
         'useNoise'=>false);
        $verify = new \Think\Verify($config);
        $verify->entry();
    }
    public function checklogin($username=null, $password=null, $verify=null,$backurl=null){
    	 if(!check_verify($verify)){
                $this->error('验证码不正确');
            }

        if (empty($username)) {
            $this->error(L('write_username'));
        }
        if (empty($password)) {
            $this->error(L('write_password'));
        }
        $Member=D('Users');
        $info=$Member->login($username,$password);
        if ($info>0) {
            if (I('post.rememberpassword')==1) {
                cookie('local_username',$username,7200);
                cookie('local_password',$password,7200);
            }else{
                cookie('local_username',null);
                cookie('local_password',null);
            }
            M('Cart')->where('anonymous="'.cookie('anonymous').'" AND uid=0')->setField('uid',$info);
        	$this->success(L('login_success'), $backurl?$backurl:U('Index/index'));
        }elseif($info==-1){
            $info=array(
                'info'=>L('password_error'),
                'status'=>0,
            );
        	$this->ajaxReturn($info);
        }elseif($info==-2){
            $info=array(
                'info'=>L('user_none'),
                'status'=>0,
            );
        	$this->ajaxReturn($info);
        }
    	
    }
    public function reg(){
    	if (is_login()) {
    		$this->redirect('Index/index');
    	}else{
    		$this->display();
    	}
    	
    }

    public function code(){
        $code=mt_rand(100000,999999);
        $email=I('post.email');
        $username=I('post.username');
        $where['email']=$email;
        $where['username']=$username;
        $user=D('Users');
        $zhen=$user->where($where)->find();
        if(!$zhen){
           $this->error('用户名与邮箱不符');
        }
            $sendresult=send_mail($email,'','找回密码','尊敬的'.$username.'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本次邮箱验证码为：'.$code.',请在官网键入此验证码，用以设置新密码。<br>ProcurementChina欢迎您开启中国代运（代购+国际转运）之旅。我们的官方网址是：www.procurementchina.com<br>如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com <br>投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br><br><br><br>Dear '.$username.'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email verification code is:'.$code.',Please use this code for login password reset.<br>Welcome to Procurementchina and here goes our procurement and international express service.Official website: www.procurementchina.com<br>Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com<br>Should you have any complaints and suggestions, please also email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>This is an automatic email sent out by ProcurementChina system, please do not reply.<br>',null);
            if($sendresult==true) {
                   $send=M('scode');
                   $data=array(
                      'code'=>$code,
                      'email'=>$email,
                    );
                 $id=$send->add($data);
                 $arr=array('id'=>$id);
                 echo json_encode($arr);
                }
         }

    public function register($username=null,$password=null,$email=null,$repassword=null,$code=null,$id=null,$verify=null){
        $users=D('Users');
        if(empty($username)){
            $this->error(L('write_username'));
        }
        if(!check_verify($verify)){
                $this->error('验证码不正确');
        }
        if (empty($email)) {
            $this->error(L('write_email'));
        }
        $rule = array( //email验证规则
            array('email','email',L('email_error')),  
        );
        
        if(!$users->validate($rule)->create()){
            $this->error(L('email_error'));
        };
        if (empty($password)) {
            $this->error(L('write_password'));
        }
        if ($password!=$repassword) {
        	$this->error(L('repassword_error'));
        }
            $info=$users->reg($username,$password,$email);
            // $info 返回注册的uid
            if ($info>0) {
        		$users->login($username,$password);
                $users->where("uid=$info")->setInc('score',500);//注册完成送积分500
                $users->where("uid=$info")->setInc('huascore',500);
                D('Record')->addrecordscore($info,'500',1,8,'注册送积分');
        		$this->success(L('reg_login_success'),U('Index/index'));	
        	}elseif($info==-1){
        		$this->error(L('has_username'));
        	}elseif($info==-2){
        		$this->error(L('has_email'));
        	}elseif($info==-3){
        		$this->error(L('reg_error'));
        	}elseif($info==-4){
                $usid=$users->where('username="'.$username.'"')->getField('uid');
                $users->where("uid=$usid")->setInc('score',500);//注册完成送积分500
                $users->where("uid=$usid")->setInc('huascore',500);
                D('Record')->addrecordscore($usid,'500',1,8,'注册送积分');
                $code=md5($usid.'-'.time());
                $users->where('uid='.$usid)->setField('tempcode',$code);
                $url='http://'.C('DOMAIN_URL').U('Public/checkreg','code='.$code);
                $result=send_mail($email,$username,'请激活您的账户','尊敬的'.$username.'：<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;恭喜您注册成为ProcurementChina会员。<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的邮箱验证链接:<a href="'.$url.'">'.$url.'</a><br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina欢迎您开启中国代运（代购+国际转运）之旅。我们的官方网址是：www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br><br><br>Dear '.$username.'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks for your registration on ProcurementChina. <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please click web link to complete final registration:<a href="'.$url.'">'.$url.'</a><br>

 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Procurementchina and here goes our procurement and international express service 

Official website: www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Should you have any question, please feel free to contact our online customer service or send an email to service@procurementchina.com
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any complaints and suggestions, please also email to complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>If this email arrives at you by mistake, please skip it.<br>
This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
                if ($result==true) {
                    $this->success('注册成功，请前往您的邮箱激活账户',U('Public/login'));
                }else{
                    $this->error('邮件发送失败，请重新发送');
                }
            } 
    
    }

    public function checkreg(){
        $code=I('get.code');
        $users=D('Users');
        $jieguo=$users->where('tempcode="'.$code.'"')->setField('status',1);
        if($jieguo==true){
            $users->where('tempcode="'.$code.'"')->setField('tempcode','');
            $this->success('用户激活成功请登录',U('Public/login'));
        }else{
            $this->error('链接已失效');
        }
    }

    public function logout(){
    	if(is_login()){

            D('Users')->logout();

            session('[destroy]');

            $this->redirect('Index/index');

        } else {

            $this->redirect('Public/login');

        }
    }

    public function zhaomima(){
        $this->display();
    }

    public function checkzhao(){
        $password=I('post.password');
        $username=I('post.username');
        $scode=M('scode');
        $where['id']=I('post.id');
        $row=$scode->where($where)->find();
        $user=D('users');
        $zhaoinfo=$user->where('username="'.$username.'"')->find();
        $uid=$zhaoinfo['uid'];
        if($row['code']==I('post.code')){
           $cheng=$user->where('uid='.$uid)->setField('password',md5($password));
           if($cheng){
             $this->success('密码重置成功，请登录',U('Public/login'));
           }else{
             $this->error('密码重置失败，请重新修改');
           }
        }else{
            $this->error('邮箱验证码错误');
        }
    }
}