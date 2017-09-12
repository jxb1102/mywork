<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController {
    public function index(){

        $this->display();
    }
    public function ceshi(){
    	$aaa=send_mail('lgs821@qq.com','小帅','测试邮件，请勿回复','测试邮件，请勿回复，谢谢',null);
    	dump($aaa);
    }
}