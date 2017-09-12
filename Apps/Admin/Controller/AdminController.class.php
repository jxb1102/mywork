<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {

    public function _initialize(){
        $user = is_login();
        if(!$user){
            $this->redirect('Public/login');
        }
        $url = ucfirst(CONTROLLER_NAME).'/'.ACTION_NAME;
        $one = M('sidenav')->where('uri="'.ucfirst(CONTROLLER_NAME).'"')->find();
        $show_id = $one['id'];
        $parent = M('sidenav')->where('pid=0 and status=1')->order('sort asc')->select();
        foreach($parent as $k=>$v){
            $children = M('sidenav')->where('pid='.$v['id'].' and status = 1')->order('sort asc')->select();
            if($v['id'] == $show_id){
                $parent[$k]['isopen'] = 1;
            }else{
                $parent[$k]['isopen'] = 0;
            }
            if($url == $v['uri']){
                $parent[$k]['isactive'] = 1;
            }
            foreach($children as $kk=>$vv){
                if($vv['uri'] == $url){
                    $children[$kk]['isactive'] = 1;
                }else{
                    $children[$kk]['isactive'] = 0;
                }
            }
            $parent[$k]['children'] = $children;
        }
        $user = session('user_auth_admin');
        $this->assign('adminmem',$user);
        $this->assign('sidenav',$parent);
    }

}
