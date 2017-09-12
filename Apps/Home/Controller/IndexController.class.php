<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
    	$state=M('State')->select();
    	$this->assign('state',$state);
        $lang=$_GET['l']?$_GET['l']:(cookie('think_language')?cookie('think_language'):'zh-cn');
    	$noticelist=M('Article')->where('cid=31 AND lang="'.$lang.'"')->order('id desc')->limit(4)->select();
    	$indexad=M('Indexad')->where('status=1')->order('sort desc')->select();
    	$this->assign('indexad',$indexad);
    	$this->assign('noticelist',$noticelist);
    	$this->assign('title',L('index').' - ');
        $wuliu=M('Gjwu')->where('status=1')->order('sort desc')->limit(1)->select();
        $this->assign('wuliu',$wuliu);
        $pay=M('Pay')->where('status=1')->order('sort desc')->limit(1)->select();
        $this->assign('pay',$pay);
        $car=M('Cart')->where('uid='.session('user_auth.uid'))->select();
        if($car){
            $ccount=count($car);
        }
        $ccount=0;
        $this->assign('ccount',$ccount);
        $ywhere['uid']=session('user_auth.uid');
        $ywhere['status']=5;
        $yundan=M('Yundan')->where($ywhere)->select();
        $yuncount=count($yundan);
        $this->assign('yuncount',$yuncount);
        $muid=session('user_auth.uid');
		$sql="select * from ls_msg where uid='".$muid."' and reply is null";
        $msg=D()->query($sql);
        $msgcount=count($msg);
        $this->assign('msgcount',$msgcount);
        $wwhere['uid']=session('user_auth.uid');
        $wwhere['status']=1;
        $mruku=M('Yundan')->where($wwhere)->select();
        $meicount=count($mruku);
        $this->assign('mcount',$meicount);
        $this->display();
        
    }
    public function tracking(){
        $kdsn=I('request.kdsn');
        $poweredkd100 = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
        $this->assign('kdsn',$kdsn);
        if (empty($kdsn)) {
            $this->display();
            exit();
        }
        $kdsns= explode("\n", trim($kdsn)) ;
        $kdlist=array();
        foreach ($kdsns as $key => $value) {
            $result=$this->gettracking(trim($value));
        if ((int)$result['status']!==1) {
            if (S($value)) {
                $kdlist[$key]=S($value);
            }else{
                $result['nu']=$value;
                $kdlist[$key]=$result;
            }
        }else{
            S($value,$result);
            $result['nu']=$value;
            $kdlist[$key]=$result;
        }
        }

        $this->assign('powered',$poweredkd100);
        $this->assign('kdlist',$kdlist);
        $this->display();
    }
    private function gettracking($kdsn){
        $info=M('YundanBaoguo')->where('bgsn="'.$kdsn.'"')->find();
        if (!$info) {
            $data=array(
                'nu'=>$kdsn,
                'message'=>'系统不存在该单号',
                'status'=>0,
            );
            return $data;
        }
        $tklist=M('Tracking')->where('bgsn="'.$kdsn.'"')->order('addtime desc')->select();
        if ($tklist) {
            foreach ($tklist as $key => $value) {
                $tklist[$key]['time']=date('Y-m-d H:i',$value['addtime']);
            }
            $data=array(
                'status'=>1,
                'message'=>"ok",
                'data'=>$tklist,
            );
            return $data;
        }
        $deliveryid=M('Yundan')->where('id='.M('YundanBaoguo')->where('bgsn="'.$kdsn.'"')->getField('ydid'))->getField('deliveryid');
        $typeCom = M('Delivery')->where('id='.$deliveryid)->getField('daima');//快递公司
        $typeNu = $kdsn;  //快递单号

$AppKey='66d7ca3deb998533';//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=0&muti=1&order=desc';

//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。

  $curl = curl_init();
  curl_setopt ($curl, CURLOPT_URL, $url);
  curl_setopt ($curl, CURLOPT_HEADER,0);
  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
  $get_content = curl_exec($curl);
  curl_close ($curl);
  return json_decode($get_content,true);
// print_r($get_content . '<br/>' . $powered);
    }
}