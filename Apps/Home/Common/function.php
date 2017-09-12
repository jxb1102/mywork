<?php
header("Content-type:text/html;charset=utf-8");
$config =   M('Config')->getField('name,value');
C($config);
$lang=M('Lang')->where('status=1')->getField('type',true);
C('LANG_LIST',implode(',',$lang));
function check_verify($code){

    $verify = new \Think\Verify();

    return $verify->check($code);

}
function is_login(){
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return  $user['uid'];
    }
}
function get_userinfo($mid){
	$data=M('Users');
	$list=$data->find($mid);
	return $list;
}
function getyundanstatus($status){
    if ($status==1) {
        return '待确认';
    }elseif($status==2){
        return '已确认';
    }elseif($status==3){
        return '待付款';
    }elseif($status==4){
        return '待发货';
    }elseif($status==5){
        return '待收货';
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
function getgoodsid($q){

  if (!empty($q) && (!(strpos($q,"taobao.com") === false) || !(strpos( $q,"tmall.com")===false))) {
            $Query = parse_url ( strtolower ( $q ) );
            parse_str ( $Query ['query'], $GET );
            $idend=strpos($Query ['path'],'.htm');//手机版的淘宝地址 path部分
            $ampos=strpos($Query['host'],'a.m');//手机版的淘宝地址  host部分
            if (! empty ( $GET ['item_num_id'] ) && strlen ( $GET ['item_num_id'] ) > 5){
                $GET ['id'] = $GET ['item_num_id'];
            }elseif(! empty( $GET['mallstitemid'] ) && strlen ( $GET ['mallstitemid'] ) > 5){
                $GET ['id'] = $GET['mallstitemid'];
            }elseif(!($ampos===false) && empty ( $GET ['id'] )){
                $GET ['id']=substr($Query['path'],2,$idend-2);
            }
            if (! empty ( $GET ['id'] ) && strlen ( $GET ['id'] ) > 5) {
                $id =  $GET ['id'];
            }
            }

return $id;
}
function gettaobao($q){
        $id=getgoodsid($q);
        $taobao= new\ Think\TaoClient();
        $taodata = $taobao->itemview($id,"Y");
        return $taodata;



}

function getdomesticfreight($id){//根据传递的商品id,获取淘宝商品国内运费
    // $cfg_area_code="310104@上海徐汇区";
    // $choosearea=explode('@',$cfg_area_code);
    // if(!empty($choosearea[1])){
    //     $area_name=$choosearea[1];
    // }else{
    //     $area_name='上海徐汇区';
    // }
    
    // if(!empty($choosearea[0])){
    //     $area_code=$choosearea[0];
    // }else{
    //     $area_code='310104';
    // }
     $taobao= new\ Think\TaoClient();

    $freightarr=$taobao->getfreight($id,310104);
    $domestic_freight=(float)$freightarr['freight'];
    $data['area_name']=$area_name;
    $data['domestic_freight']=$domestic_freight;
    return $data;
    //return $freightarr;
    
}







?>