<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends AdminController
{
    protected $member;
    public  function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $member = D('member');
        $id = I('get.id');
        if($id) {
            $info = $member->where('uid='.$id)->find();
            $groups = M('AuthGroupAccess')->where('uid=' . $id)->find();
            $info['group']['id'] = $groups['group_id'];
            $this->assign('info', $info);
        }

        $list = $member->lists();
        $this->assign('memlist',$list);
        $group = M('AuthGroup')->field('id,title')->where('status=1')->select();
        $this->assign('glist',$group);
        $this->display();
    }
    public function memberadd(){
        $member = D('Member');
        $username = I('post.username');
        if($username == ''){
            $this->error('用户名不能为空');
        }
        $password = I('post.password');
        if($password == ''){
            $this->error('密码不能为空');
        }
        $nickname = I('post.nickname');
        if($nickname == ''){
            $nickname = $username;
        }
        $mobile = I('post.mobile');
        if($mobile == ''){
            $this->error('手机号不能为空');
        }
        $group_id = I('post.groupid');
        if($group_id == ''){
            $this->error('请选择分组');
        }
        $user = $member->where('username="'.$username.'"')->find();
        if(!empty($user)){
            $this->error('用户名已存在');
        }
        $add = array(
            'username'=>$username,
            'nickname'=>$username,
            'password'=>md5($password),
            'mobile'=>$mobile,
            'reg_ip'=>get_client_ip(1),
            'reg_time'=>time(),
            'status'=>I('post.status')
        );
        $result = $member->add($add);
        if($result){
            $group_add['uid'] = $result;
            $group_add['group_id'] = $group_id;
            M('AuthGroupAccess')->add($group_add);
            $this->success('添加成功',U('Member/index'));
        }
    }
    public function memberedit(){
        $uid = I('post.uid');
        $member = D('Member');
        $groups = M('AuthGroupAccess')->where('uid=' . $uid)->find();
        if(!$groups){
            $this->error('未找到该用户信息');
        }
        $username = I('post.username');
        if($username == ''){
            $this->error('用户名不能为空');
        }
        $password = I('post.password');
        if($password == ''){
            $this->error('密码不能为空');
        }
        $nickname = I('post.nickname');
        if($nickname == ''){
            $nickname = $username;
        }
        $mobile = I('post.mobile');
        if($mobile == ''){
            $this->error('手机号不能为空');
        }
        $group_id = I('post.groupid');
        if($group_id == ''){
            $this->error('请选择分组');
        }
        $update = array(
            'username'=>$username,
            'nickname'=>$username,
            'password'=>md5($password),
            'mobile'=>$mobile,
            'status'=>I('post.status'),
            'uid'=>$uid
        );
        $member->save($update);
        if($groups['group_id'] != $group_id){
            M('AuthGroupAccess')->where('uid='.$uid)->setField('group_id',$group_id);
        }
        $this->success('编辑成功',U('Member/index'));
    }

    public function memberdel(){
        $id = I('post.id');
        $res= M('AuthGroupAccess')->where('uid='.$id)->delete();
        if($res){
            M('Member')->delete($id);
            $this->success('删除成功',U('Member/index'));
        }else{
            $this->error('删除失败');
        }

    }

    public function group(){
        $id = I('get.id');
        $authGroup = M('AuthGroup');
        if($id){
            $group = $authGroup->field('id,title,status')->where('id='.$id)->find();
            $this->assign('info',$group);
        }
        $list = $authGroup->select();
        $this->assign('list',$list);
        $access = I('get.access');
        if($access){
            $side=M('Sidenav');
            $sidelist=$side->where('pid=0')->order('sort')->select();
            foreach ($sidelist as $key => $value) {
                $sidelist[$key]['child']=$side->where('pid='.$value['id'])->order('sort')->select();
                foreach ($sidelist[$key]['child'] as $k => $v) {
                    $sidelist[$key]['child'][$k]['children']=$side->where('pid='.$v['id'])->order('sort')->select();
                }
            }
            //p($sidelist);
            $rules = M('AuthRule');
            $main_rules = $rules->field('id,name')->where('type=2')->select();
            $child_rules = $rules->field('id,name')->where('type=1')->select();
            //p($main_rules);exit;
            foreach($main_rules as $k=>$v){
                $key = str_replace('Admin/','',$v['name']);
                $main_rules[$key] = $v['id'];
            }
            foreach($child_rules as $k=>$v){
                $key = str_replace('Admin/','',$v['name']);
                $child_rules[$key] = $v['id'];
            }
            $auth_group = $authGroup->field('id,rules')->where('id='.$access)->find();
            $this->assign('auth_group',$auth_group);
            $this->assign('main_rules',$main_rules);
            $this->assign('child_rules',$child_rules);
            $this->assign('node_list',$sidelist);
            $this->display('Member_access');exit;
        }
        $this->display();

    }

    public function groupadd(){
        $title = I('post.title');
        $status = I('post.status');
        $add = array(
            'title' => $title,
            'status' => $status
        );
        $res = M('AuthGroup')->add($add);
        if($res){
            $this->success('新增成功',U('Member/group'));
        }else{
            $this->error('新增失败');
        }
    }

    public function groupedit(){
        $id = I('post.id');
        $title = I('post.title');
        $status = I('post.status');
        $update = array(
            'title' => $title,
            'status' => $status
        );
        $res = M('AuthGroup')->where('id='.$id)->save($update);
        if($res){
            $this->success('编辑成功',U('Member/group'));
        }else{
            $this->error('新增失败');
        }
    }
    public function groupdel(){
        $id = I('post.id');
        $res= M('AuthGroup')->where('id='.$id)->delete();
        if($res){
            $this->success('删除成功',U('Member/group'));
        }else{
            $this->error('删除失败');
        }
    }

    public function accessadd(){
        $rules = I('post.rules');
        $rules = implode(',',$rules);
        $id = I('post.id');
        $res = M('AuthGroup')->where('id='.$id)->setField('rules',$rules);
        if($res){
            $this->success('授权成功',U('Member/group'));
        }else{
            $this->error('授权失败');
        }
    }
}