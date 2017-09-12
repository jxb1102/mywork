<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends CommonController {
    public function index(){
    	$data=M('Cart');
        if (is_login()) {
            $where['uid']=session('user_auth.uid');
        }else{
            $where['anonymous']=cookie('anonymous');
            // $where['uid']=0;
        }
        $list=$data->field('shopname,max(goodsfreight),goodsseller')->where($where)->group('goodsseller')->select();

        $goodslist=$data->where($where)->select();
        foreach ($goodslist as $key => $value) {
            $totalgoodsprice += $value['goodsprice']*$value['goodsnum'];
        }

        foreach ($list as $key => $value) {
            $totalgoodsfreight +=$value['max(goodsfreight)'];
        }
        //dump($totalgoodsfreight);
        $totalprice=$totalgoodsfreight+$totalgoodsprice+($totalgoodsprice+$totalgoodsfreight)*C('GOU_FU');
        $zhifuprice=$totalprice/C('MAIN_HUILV');
        $goufei=($totalgoodsprice+$totalgoodsfreight)*C('GOU_FU');
        $price=array(
            'totalgoodsprice'=>sprintf('%.2f',$totalgoodsprice),
            'totalgoodsfreight'=>sprintf('%.2f',$totalgoodsfreight),
            'totalprice'=>sprintf('%.2f',$totalprice),
            'zhifuprice'=>sprintf('%.2f',$zhifuprice),
            'goufei'=>sprintf('%.2f',$goufei)
        );
        $this->assign('price',$price);
        foreach ($list as $key => $value) {
            $where['goodsseller']=$value['goodsseller'];

            $list[$key]['goods']=$data->where($where)->select();
            $list[$key]['goodscount']=$data->where($where)->count();
        }
        $this->assign('list',$list);
    	$this->assign('title','我的购物车 - ');
        // $gou_fu=C('GOU_FU');
        // $this->assign('goufu',$gou_fu);
        $this->display();
    }
    public function setnum(){
        $id=I('post.id');
        $num=I('post.num');
        M("Cart")->where('id='.$id)->setField('goodsnum',$num);
    }
    public function getcartinfo($id){
        $info=M('Cart')->find($id);
        $this->ajaxReturn($info);
    }
    public function editcart(){
        $edit=array(
            'id'=>I('post.id'),
            'goodssize'=>I('post.goodssize'),
            'goodscolor'=>I('post.goodscolor'),
            'goodsremark'=>I('post.goodsremark'),
        );
        if (M('Cart')->create($edit)) {
            if (M('Cart')->save()) {
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function del($did){
        if (is_login()) {
            $where['uid']=session('user_auth.uid');
        }else{
            $where['anonymous']=cookie('anonymous');
        }
        $where['id']=$did;
        M('Cart')->where($where)->delete();
        $this->success('删除成功');
    }
    public function checkout(){
        if (!is_login()) {
            header('location:http://'.$_SERVER['HTTP_HOST'].'/Public/login.html?url=http://'.$_SERVER['HTTP_HOST'].'/Cart/checkout.html');
        }
        $data=M('Cart');
        if (is_login()) {
            $where['uid']=session('user_auth.uid');
        }else{
            $where['anonymous']=cookie('anonymous');
            // $where['uid']=0;
        }
        $list=$data->field('shopname,max(goodsfreight),goodsseller')->where($where)->group('goodsseller')->select();
        $goodslist=$data->where($where)->select();
        foreach ($goodslist as $key => $value) {
            $totalgoodsprice += $value['goodsprice']*$value['goodsnum'];
        }

        foreach ($list as $key => $value) {
            $totalgoodsfreight +=$value['max(goodsfreight)'];
        }
        //dump($totalgoodsfreight);
        $totalprice=$totalgoodsfreight+$totalgoodsprice+($totalgoodsprice+$totalgoodsfreight)*C('GOU_FU');
        $zhifuprice=$totalprice/C('MAIN_HUILV');
        $goufei=($totalgoodsprice+$totalgoodsfreight)*C('GOU_FU');
        //$goufei=$totalgoodsprice*C('GOU_FU');
        $price=array(
            'totalgoodsprice'=>sprintf('%.2f',$totalgoodsprice),
            'totalgoodsfreight'=>sprintf('%.2f',$totalgoodsfreight),
            'totalprice'=>sprintf('%.2f',$totalprice),
            'zhifuprice'=>sprintf('%.2f',$zhifuprice),
            'goufei'=>sprintf('%.2f',$goufei)
        );
        $this->assign('price',$price);
        foreach ($list as $key => $value) {
            $where['goodsseller']=$value['goodsseller'];

            $list[$key]['goods']=$data->where($where)->select();
            $list[$key]['goodscount']=$data->where($where)->count();
        }
        
        $this->assign('list',$list);
        $address=M('Address')->where('uid='.session('user_auth.uid').' AND def=1')->find();
        $this->assign('address',$address);
        $user=M('Users')->find(session('user_auth.uid'));
        if ($user['utype']>0) {
            $utype=get_usertypeid($user['utype']);
        }else{
            $utype=getusertypeid($user['score']);
        }
        $delivery=M('Delivery')->where('areaid='.$address['areaid'])->select();
        $this->assign('delivery',$delivery);
        $addresslist=M('Address')->where('uid='.session('user_auth.uid'))->order('id desc')->select();
        $this->assign('addresslist',$addresslist);
        $state=M('State')->select();
        $this->assign('state',$state);
        $this->display();
    }
    public function tjdingdan(){
        if (!is_login()) {
            header('location:http://'.$_SERVER['HTTP_HOST'].'/Public/login.html?url=http://'.$_SERVER['HTTP_HOST'].'/Cart/checkout.html');
        }
        $addressid=I('post.addressid');
        $deliveryid=I('post.deliveryid');
        $isxian=I('post.isxian');
        //echo $isxian; exit;
        $cartlist=M('Cart')->where('uid='.session('user_auth.uid'))->select();
        if (empty($cartlist)) {
            $this->error('购物车数据错误，请确认');
        }
        if (empty($addressid)) {
            $this->error(L('select_address'));
        }
        if (empty($deliveryid)) {
            $this->error(L('select_shipping_method'));
        }
        $address=M('Address')->find($addressid);
        foreach ($cartlist as $key => $value) {
            $totalgoodsprice+=$value['goodsprice']*$value['goodsnum'];
        }
        $cartgrouplist=M('Cart')->field('shopname,max(goodsfreight),goodsseller')->where('uid='.session('user_auth.uid'))->group('goodsseller')->select();
        foreach ($cartgrouplist as $key => $value) {
            $totalgoodsfreight +=$value['max(goodsfreight)'];
        }
        $dgadd=array(
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'status'=>1,
            'areaid'=>$address['areaid'],
            'areaname'=>$address['areaname'],
            'consignee'=>$address['consignee'],
            'tel'=>$address['tel'],
            'zipcode'=>$address['zipcode'],
            'address'=>$address['address'],
            'deliveryid'=>$deliveryid,
            'addtime'=>time(),
            'edittime'=>time(),
            'totalgoodsprice'=>$totalgoodsprice,
            'totalgoodsfreight'=>$totalgoodsfreight,
            'totalprice'=>$totalgoodsfreight+$totalgoodsprice,
            'isxian'=>$isxian,
            'shenhe'=>0,
        );
        if (M('Daigou')->create($dgadd)) {
            $id=M('Daigou')->add();
            if ($id>0) {
                M('Daigou')->where('id='.$id)->setField('dgsn',C('DG_PREFIX').sprintf("%07d",$id));
                foreach ($cartlist as $key => $value) {
                    $dggadd=array(
                        'uid'=>session('user_auth.uid'),
                        'uname'=>session('user_auth.username'),
                        'dgid'=>$id,
                        'goodsimg'=>$value['goodsimg'],
                        'goodsurl'=>$value['goodsurl'],
                        'goodsname'=>$value['goodsname'],
                        'goodsprice'=>$value['goodsprice'],
                        'goodsfreight'=>$value['goodsfreight'],
                        'goodssize'=>$value['goodssize'],
                        'goodscolor'=>$value['goodscolor'],
                        'goodsnum'=>$value['goodsnum'],
                        'goodsremark'=>$value['goodsremark'],
                        'goodsseller'=>$value['goodsseller'],
                        'shopname'=>$value['shopname'],
                        'maxnum'=>$value['maxnum'],
                        'addtime'=>time(),
                        'status'=>1,
                    );
                    M('DaigouGoods')->add($dggadd);
                }
                M('Cart')->where('uid='.session('user_auth.uid'))->delete();
                $this->success('订单提交成功，请支付！',U('User/paydaigou','id='.$id));
            }else{
                $this->error('下单失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
}