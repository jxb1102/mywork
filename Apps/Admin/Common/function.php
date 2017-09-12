<?php
$config =   M('Config')->getField('name,value');
        C($config);
function check_verify($code, $id = 1){

    $verify = new \Think\Verify();

    return $verify->check($code, $id);

}
function is_login(){
    $user = session('user_auth_admin');
    if (empty($user)) {
        return 0;
    } else {
        return  $user['uid'];
    }
}
function getgroupname($uid){
	$aga=M('AuthGroupAccess');
	$group=M('AuthGroup');
	$info=$aga->where('uid='.$uid)->getField('group_id');
	$groupname=$group->where('id='.$info)->getField('title');
	return $groupname;
}
function getauthgroup($uid){
	$aga=M('AuthGroupAccess');
	$group=M('AuthGroup');
	$info=$aga->where('uid='.$uid)->getField('group_id');
	$groupname=$group->where('id='.$info)->find();
	return $groupname;
}
function get_member($mid){
	$data=M('Member');
	$list=$data->find($mid);
	return $list;
}
function getyundanstatus($status){
    if ($status==1) {
        return '待确认';
    }elseif($status==2){
        return '已确认';
    }elseif($status==3){
        return '已打包';
    }elseif($status==4){
        return '已付款';
    }elseif($status==5){
        return '已发货';
    }elseif ($status==6) {
        return '已收货';
    }elseif ($status==7) {
        return '已评价';
    }elseif ($status==9) {
        return '已暂扣';
    }
}
function getdaigoustatus($status){
    if ($status==1) {
        return '未付款';
    }elseif ($status==2) {
        return '已付款';
    }elseif ($status==3) {
        return '已确认';
    }elseif ($status==4) {
        return '已采购';
    }elseif ($status==5) {
        return '已入库';
    }elseif ($status==6) {
        return '已发货';
    }
}

?>