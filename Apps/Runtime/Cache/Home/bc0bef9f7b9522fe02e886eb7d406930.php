<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html>

<head>

<meta charset="utf-8" />

<title><?php echo ($title); ?>星松科技</title>
<link href="/icon.gif" type="image/gif" rel="shortcut icon" />
<link href="/Public/Home/default/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/Public/Home/default/css/shopping-nav.css" />

<script type="text/javascript" src="/Public/Home/default/js/jquery.min.js"></script>

<script type="text/javascript" src="/Public/Home/default/js/lrtk.js"></script>

<script type="text/javascript" src="/Public/Home/default/js/tabulous.js"></script>

<script type="text/javascript" src="/Public/Home/default/js/shopping_nav.js"></script>

<script type="text/javascript" src="/Public/Common/js/thinkbox/jquery.thinkbox.js"></script>

<script type="text/javascript" src="/Public/Home/default/js/style.js"></script>

<script type="text/javascript" src="/Public/Home/default/js/bigpicroll.js"></script>

<SCRIPT>

  function toueme(){

    document.getElementById("top_adv").style.display="none";

  }

</SCRIPT>

</head>

<style>

  body a{text-decoration:none;}

</style>

<body>

<!-- <div id="top_adv"><a style="CURSOR: hand" onClick=toueme()><img 

      src="/Public/Home/default/images/close.jpg" width=18 height=16></a></div> -->

<div class="top">

  <dl class="head_nav">

  <?php if($_SESSION['user_auth']!= ''): ?><dt>欢迎访问<?php echo C('DOMAIN_TITLE');?>,<?php echo ($user["nickname"]); ?>！ <a href="<?php echo U('Public/logout');?>"><?php echo L('logout');?></a></dt>

    <?php else: ?>

    <dt>欢迎访问<?php echo C('DOMAIN_TITLE');?>&nbsp;<a href="<?php echo U('Public/login');?>"><?php echo L('login');?></a>&nbsp;<?php echo L('huo');?>&nbsp;<a href="<?php echo U('Public/reg');?>"><?php echo L('reg');?></a></dt><?php endif; ?>

    <dd>

      <a href="/"><?php echo L('site_index');?></a> | <a href="<?php echo U('User/index');?>"><?php echo L('my_account');?></a> | <a href="<?php echo U('Cart/index');?>"><?php echo L('mycart');?>(<?php echo ($cartcount); ?>) |<a href="<?php echo U('User/baoguolist');?>"> <?php echo L('yundanchaxun');?> | <a href="<?php echo U('Cate/gjyf');?>"><?php echo L('guojiyunfei');?></a> | <a href="<?php echo U('Cate/bangzhu');?>"><?php echo L('bangzhuzhongxin');?></a>

    </dd>

  </dl>

  <dl class="logo column1200">

    <dt><a href="/"><img src="/Public/Home/default/images/logo.jpg"  alt="神州代运"/></a><span><?php echo L('zhuti');?></span></dt>

    <dd>

      <div class="search">

        <a style="background:#C30D24;color:#FFF;padding:8px 24px;"><?php echo L('daigou');?></a><a href="<?php echo U('User/yubaoonly');?>" style="background:#92d050;color:#FFF;"><?php echo L('guojizhuanyun');?></a>

        <div class="search_container">

            <form action="<?php echo U('Daigou/index');?>" method="get">

              <input name="url" type="text" class="search_bk" id="url" value="<?php echo L('dglian');?>">

              <input type="submit" value="<?php echo L('mydaigou');?>" class="search_btn">

            </form>

          <!-- <div id="tabs-2">

            <form action="" method="get">

              <input name="" type="text" class="search_bk">

              <input name="" type="搜索" value="我要代运" class="search_btn">

            </form>

          </div> -->

        </div>

        <!--End tabs container--> 

        

      </div>

      

       <script type="text/javascript" >

          $(document).ready(function($) {

               $('#tabs').tabulous({effect: 'scale'});

          });

     </script> 

    </dd>

  </dl>

  <ul class="mainnav column1200">

    <li><a href="<?php echo U('Index/index');?>"><?php echo L('site_index');?></a></li>

    <li><a href="<?php echo U('User/yubaoonly');?>"><?php echo L('guojizhuanyun');?></a></li>

    <li><a href="<?php echo U('Daigou/index');?>"><?php echo L('daigou');?></a></li>

    <li><a href="<?php echo U('Cate/gwzn');?>"><?php echo L('gouwuzhinan');?></a></li>

    <li><a href="<?php echo U('Cate/active');?>"><?php echo L('youhuihuodong');?></a></li>

    <li><a href="<?php echo U('Cate/lists','id=32');?>"><?php echo L('shangyehezuo');?></a></li>

  </ul>

</div>

 <script>

   $(document).ready(function(){

      $('.search_btn').click(function(){

        var url=$("#url").val();

        var urlreg = /(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/;

        if(url==''){

          return false;

        }

        if(!urlreg.test(url)){

          $('#url').val('请输入有效的网址').css({'color':'red'});

          return false;

        }

      })

      $('#url').focus(function(){

        $('#url').val('').css({'color':''});

      })

   })

 </script>


	<div class="blank"></div>
	<style type="text/css">
		.catelist li{line-height: 30px;border-bottom: 1px dashed #dfdfdf;font-weight: bold;}
		.catelist li a{display: block;padding-left: 30px; font-size: 14px;font-family: "宋体";}
		.catelist li a:hover{background: #f2f2f2;}
		.main_r .articlelist li{border-bottom: 1px dashed #dfdfdf;list-style: none;background: none;text-indent: 0;padding:5px 10px;}
		.main_r .articlelist li a{font-size: 14px;color: #666;font-weight: bold;}
		.main_r .articlelist li a:hover{text-decoration: underline;}
		.main_r .articlelist li p{margin: 0;line-height: 18px;color: #8c8c8c;font-size: 12px;}
		a{text-decoration: none; color: #8c8c8c;}
        a:hover{color: #333;}
	</style>
<!--zhuti1 begin-->
<div class="lgs-main">
<div class="fl" style="width: 250px;height: auto;">
<div style="border:1px dashed #dfdfdf;border-bottom: none;font-size: 16px;color:#8c8c8c;padding: 5px 10px;"><b><?php echo L('site_category');?></b></div>
<div style="width: 228px;padding:10px;height: auto;border: 1px dashed #dfdfdf;">
	<ul class="catelist">
		<?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Cate/lists','id='.$c['id']);?>" <?php if(($c["id"]) == $cateinfo["id"]): ?>style="background:#f2f2f2;"<?php endif; ?>><?php echo ($c["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	<!-- <div style="padding-top: 10px;">
		<div style="position: relative;margin-left: 30px;">
			<i class="kefutel"></i>
			<span style="font-size: 16px;color:#333;"><?php echo L('tel');?></span>&nbsp;<span style="font-size: 12px;"><?php echo L('yingye_time');?></span> <br>
			<span style="font-size: 16px;color:red; font-weight:600;"><?php echo C('SERVICE_TEL');?></span>
		</div>
	</div> -->
</div>
</div>
<div class="main_r " style="width:930px;height: auto; margin-left:269px;">
<div style="border:1px dashed #dfdfdf;border-bottom: none;font-size: 14px;color:#333;padding: 5px 10px;"><?php echo L('your_position');?>：<a href="<?php echo U('Index/index');?>"><?php echo L('site_index');?></a> > <?php echo ($cateinfo["title"]); ?></div>
<?php if($cateinfo["model"] == 0): ?><div class="main_r_con" style="padding: 10px;border: 1px dashed #dfdfdf;min-height: 400px;">
<ul class="articlelist">
	<?php if($articlelist): if(is_array($articlelist)): $i = 0; $__LIST__ = $articlelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><li>
			<a href="<?php echo U('Article/view','id='.$a['id']);?>"><?php echo ($a["title"]); ?></a>
			<p><?php echo ($a["jianjie"]); ?></p>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	<?php else: ?>
	<li><?php echo L('no_infos');?></li><?php endif; ?>
</ul>
<div class="page"><?php echo ($_page); ?></div>
</div><?php endif; ?>
</div>
<?php if($cateinfo["model"] == 1): ?><style type="text/css">
		p{display: block;margin-bottom: 10px;}
	</style>
<div style="width: 898px;margin-left:270px;border: 1px dashed #ccc;padding:15px;min-height: 400px;">
	<div style="font-size: 18px;font-weight: bold;text-align: center;border-bottom: 1px dashed #dfdfdf;padding-bottom: 15px;"><?php echo ($cateinfo["title"]); ?></div>
	<div style="padding: 10px 5px;font-size:14px;">
		<?php echo ($cateinfo["content"]); ?>
	</div>
</div><?php endif; ?>
<div class="clear"></div>
</div>
<!--zhuti1 end-->
<div class="clear"></div>
<div class="bottom mg_T10">
    <div class="column1200">
     <ul class="bottom_list">
        <?php if(is_array($cateaa)): $i = 0; $__LIST__ = $cateaa;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ct): $mod = ($i % 2 );++$i;?><li><h2><?php echo ($ct["title"]); ?></h2><div class="blank"></div>
                <?php if(is_array($ct['article'])): $i = 0; $__LIST__ = $ct['article'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$at): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Article/bangarticle','id='.$at['id']);?>"><?php echo ($at["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
         <li style="border:none;font-family:'微软雅黑';width:180px;"><div class="erweima"><img src="/Public/Home/default/images/erweima.png" width="109" height="109" style="padding-bottom:5px;"/><br />&nbsp;&nbsp;关注<font style="color:#E7291D;">神州代运</font></div><div class="share">
           <a href="<?php echo C('facebook');?>" target="_blank"><img src="/Public/Home/default/images/face.png" title="神州代运facebook官方主页"></a>
            <a href="<?php echo C('weibo');?>" target="_blank"><img src="/Public/Home/default/images/weobo.png" title="神州代运新浪微博"></a>
            <a href="<?php echo C('twitter');?>" target="_blank"><img src="/Public/Home/default/images/twitter.png" title="神州代运推特官方主页"></a>
         </div></li>
         
     </ul>
      
     <p class="bottom_text" style="margin-right:20px;">备案/许可证编号为：沪ICP备14038017号<br />神州代运所有权&COPY;上海纯通经贸发展有限公司</p>
            
     
     
     </div>
</div>
</body>
<script>

function initPlaceHolders(){
    if('placeholder' in document.createElement('input')){ //如果浏览器原生支持placeholder
        return ;
    }
    function target (e){
        var e=e||window.event;
        return e.target||e.srcElement;
    };
    function _getEmptyHintEl(el){
        var hintEl=el.hintEl;
        return hintEl && g(hintEl);
    };
    function blurFn(e){
        var el=target(e);
        if(!el || el.tagName !='INPUT' && el.tagName !='TEXTAREA') return;//IE下，onfocusin会在div等元素触发 
        var    emptyHintEl=el.__emptyHintEl;
        if(emptyHintEl){
            //clearTimeout(el.__placeholderTimer||0);
            //el.__placeholderTimer=setTimeout(function(),600);
        }
    };
    function focusFn(e){
        var el=target(e);
        if(!el || el.tagName !='INPUT' && el.tagName !='TEXTAREA') return;//IE下，onfocusin会在div等元素触发 
        var emptyHintEl=el.__emptyHintEl;
        if(emptyHintEl){
            //clearTimeout(el.__placeholderTimer||0);
            emptyHintEl.style.display='none';
        }
    };
    if(document.addEventListener){
        document.addEventListener('focus',focusFn, true);
        document.addEventListener('blur', blurFn, true);
    }
    else{
        document.attachEvent('onfocusin',focusFn);
        document.attachEvent('onfocusout',blurFn);
    }

    var elss=[document.getElementsByTagName('input'),document.getElementsByTagName('textarea')];
    for(var n=0;n<2;n++){
        var els=elss[n];
        for(var i =0;i<els.length;i++){
            var el=els[i];
            var placeholder=el.getAttribute('placeholder'),
                emptyHintEl=el.__emptyHintEl;
            if(placeholder && !emptyHintEl){
                emptyHintEl=document.createElement('span');
                emptyHintEl.innerHTML=placeholder;
                emptyHintEl.className='emptyhint';
                emptyHintEl.onclick=function (el){return function(){try{el.focus();}catch(ex){}}}(el);
                if(el.value) emptyHintEl.style.display='none';
                el.parentNode.insertBefore(emptyHintEl,el);
                el.__emptyHintEl=emptyHintEl;
            }
        }
    }
}

initPlaceHolders();

</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".side ul li").hover(function(){
		$(this).find(".sidebox").stop().animate({"width":"124px"},200).css({"opacity":"1","filter":"Alpha(opacity=100)","background":"#ae1c1c"})	
	},function(){
		$(this).find(".sidebox").stop().animate({"width":"54px"},200).css({"opacity":"0.8","filter":"Alpha(opacity=80)","background":"#000"})	
	});
	
});
//回到顶部
function goTop(){
	$('html,body').animate({'scrollTop':0},600); //滚回顶部的时间，越小滚的速度越快~
}
</script>
<div class="side">
	<ul>
	    <li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo C('KEFU');?>&site=qq&menu=yes" target="_blank"><div class="sidebox"><img src="/Public/Home/default/images/side_icon04.png">QQ客服</div></a></li>
		<li style="border:none;"><a href="javascript:goTop();" class="sidetop"><img src="/Public/Home/default/images/side_icon05.png"></a></li>
	</ul>
</div>
</html>