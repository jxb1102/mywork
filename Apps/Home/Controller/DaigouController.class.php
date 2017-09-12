<?php
namespace Home\Controller;
use Think\Controller;
class DaigouController extends CommonController {
    public function index(){
    	$url="http://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.6.Ub0D5p&id=17977539030&skuId=27156815604&areaId=410100&cat_id=2&rn=d6101fb42fa112cd0955f85b1ba59d10&user_id=633000160&is_b=1";


        $this->display();
    }

  
 
      
       
     

 

    public function insertcart(){
        $data=I('post.');
        $anonymous=cookie('anonymous');
		if($data['maxnum']<1){
		   $data['maxnum']=10000;
		}
        $add=array(
            'anonymous'=>$anonymous,
            'goodsimg'=>$data['goodsimg'],
            'goodsurl'=>$data['goodsurl'],
            'goodsname'=>$data['goodsname'],
            'goodsprice'=>$data['goodsprice'],
            'goodsfreight'=>$data['goodsfreight'],
            'goodssize'=>$data['sku_b'],
            'goodscolor'=>$data['sku_a'],
            'goodsnum'=>$data['goodsnum'],
            'goodsremark'=>$data['goodsremark'],
            'goodsseller'=>$data['goodsseller'],
            'shopname'=>$data['shopname'],
            'maxnum'=>$data['maxnum'],
            'addtime'=>time(),
        );
        if ($list=M('Cart')->create($add)) {
            if (is_login()) {
                $list['uid']=session('user_auth.uid');
            }
            $id=M('Cart')->add($list);
            if ($id>0) {
                $this->success('添加购物车成功');
            }else{
                $this->error(M('Cart')->getError());
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function get_url(){
        $info=cookie();
        dump($info);
    }
    public function getgoods($url){
        
             $taodata=gettaobao($url);
            //   echo '<pre>';
            // print_r($taodata);exit();
        $data=$taodata['p'];
        
        $skus=array();        
        $skus=$taodata['value']['skus']['sku'];
 
        foreach ($skus as $key=>$mp){
            $skuarr=explode(";",$mp['properties']);
            $q=$skuarr[0];
            $p=$skuarr[1];
           
            $arrS[$q][$p]['num']=$mp['quantity'];
            $arrS[$q][$p]['price']=$mp['price'];
        }

        foreach ($skus as $key=>$mp){
            $skuarr=explode(";",$mp['properties']);
            $q=$skuarr[0];
            $p=$skuarr[1];
            
            $arrS[$p][$q]['num']=$mp['quantity'];
            $arrS[$p][$q]['price']=$mp['price'];
        }

             
           $arrjson= json_encode($arrS);
          $id= getgoodsid($url);
        $guoarr=getdomesticfreight($id); 
        //dump($guoarr);
        //dump($guoarr); exit;
        $this->assign('domestic_freight',$guoarr['domestic_freight']);
        $this->assign('price',$taodata['value']['price']); 
        $this->assign('pic',$taodata['value']['item_imgs']['item_img'][0]['url']); 
        $this->assign('title',$taodata['value']['title']); 
        $this->assign('dianpu',$taodata['value']['nick']);
        $this->assign('data',$data);
        $this->assign('url',$url);
        $this->assign("arrjson",$arrjson);
        $this->display();
    }
    private function getsite($url){
        
        $apireturn = $this->getapi($url);
        return $apireturn;

    }

    private function getapi($url){
        $apiurl = "http://api.dayusheji.com/good_get.php?url=".urlencode($url);
        $opts = array(
            'http'=>array(
            'method'=>"GET",
            'timeout'=>10,
            )
        );
        $context = stream_context_create($opts);
        $apiarray =file_get_contents($apiurl, false, $context);
        if(!empty($apiarray)){
            return unserialize($apiarray);
        }else{
            return false;
        }
    }







































/*
 * 根据淘宝链接，获取商品属性
 * @param string $taobao_url
 * @return array array('材质: 雪纺','形状: 长方形');
 */
private function get_taobao_attribute($taobao_url) {
    $text = file_get_contents($taobao_url);
    $text = iconv('gbk','utf-8//IGNORE', $text);
    preg_match('/<(ul)[^c]*class=\"attributes-list\"[^>]*>.*<\/\\1>/is', $text, $text0);
    preg_match_all("/<li[^>]*?>(.*?)<\/li>/i", $text0[0], $contents);
    preg_match('/<img[^>]*id="J_ImgBooth"[^r]*rc=\"([^"]*)\"[^>]*>/', $text, $img);
    $data['img']=$img[1];
    preg_match('/<title>([^<>]*)<\/title>/', $text, $title);
    $data['title']=$title[1];
    preg_match('/\<em\sid="J_Price"\>(.+?)\<\/em\>/', $text, $price);
    $data['price']=$price;
    return $text;
}


/*
 * 根据天猫链接，获取商品属性
 * @param string $tmall_url
 * @return array array('货号:&nbsp;YD-905', '材质:&nbsp;铝合金');
 */
private function get_tmall_attribute($tmall_url) {
    $text = file_get_contents($tmall_url);
    $text = iconv('gbk','utf-8//IGNORE', $text);
    preg_match('/<(ul)[^c]*id=\"J_AttrUL\"[^>]*>.*<\/\\1>/is', $text, $text0);
    preg_match_all("/<li[^>]*?>(.*?)<\/li>/i", $text0[0], $contents);
    return $text;
}

}