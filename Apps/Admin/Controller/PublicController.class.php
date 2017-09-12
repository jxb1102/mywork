<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
	public function _empty(){
		$this->redirect('Index/index');
	}
    public function login(){
		if(is_login()){

                $this->redirect('Index/index');

            }else{
    	$this->display();
    }
    }
    public function verify(){

        $verify = new \Think\Verify();

        $verify->entry(1);

    }
    public function checklogin($username = null, $password = null, $verify = null){
    	if(!check_verify($verify)){
            $this->error('验证码错误');
        }
        $Member=D('Member');
        $info=$Member->login($username,$password);
        if ($info>0) {
            $cartwh=array(
                'uid'=>0,
                'addtime'=>array('lt',time()-60*60*24*3),
            );
            M('Cart')->where($cartwh)->delete();
        	$this->success('登录成功！', U('Index/index'));
        }elseif($info==-1){
        	$this->error('密码错误');
        }elseif($info==-2){
        	$this->error('用户不存在或者被禁用');
        }
    	
    }
    public function logout(){
    	if(is_login()){

            D('Member')->logout();

            session('[destroy]');

            $this->success('退出成功！', U('login'));

        } else {

            $this->redirect('login');

        }
    }
}