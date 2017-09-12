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


<div style="clear:both;"></div>
<div class="lgs-main">
	<div class="lgs-loginbox">
		<div class="loginform" style="margin-top:-10px;">
			<div class="formbg"></div>
			<div class="formlist" style="margin-top:-20px;">
				<!-- &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 16px;font-weight:600;margin-bottom: 5px;"><?php echo L('login');?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?php echo U('Public/reg');?>" style="color:#333; font-size:16px;"><?php echo L('reg');?></a> -->
                <span class="formtitle formtitlebg2"><?php echo L('login');?></span>
				<span class="formtitle formtitlebg"><a href="<?php echo U('Public/reg');?>"><?php echo L('reg');?></a></span>
				<div class="blank"></div>
				<form method="post" id="login"  action="<?php echo U('Public/checklogin');?>" name="login">
				    <div class="blank" style="height:10px;"></div>
					<input type="text" name="username" id="username"  class="gray" placeholder="<?php echo L('username');?>" value="<?php echo (cookie('local_username')); ?>">
					<div class="blank" style="height:10px;"></div>
					<input type="password" name="password" id="password"  placeholder="<?php echo L('password');?>" class="gray" value="<?php echo (cookie('local_password')); ?>">
					<div class="blank" style="height:10px;"></div>
			        <span style="float:left;font-size: 16px;color: #333;"><?php echo L('yzm');?>：&nbsp;</span><img src="<?php echo U('Public/verify');?>" title="看不清点我刷新" onclick="this.src=this.src+'?'+Math.random()" style="float:left;" id="code"/>&nbsp;&nbsp;<input type="text" name="verify" id="verify" value="" class="required" maxlength="6" placeholder="<?php echo L('yzm');?>" style="width:88px;">
					<div class="blank" style="height:10px;"></div>
					<label><input type="checkbox" name="rememberpassword" <?php if(($_COOKIE['local_password']) != ""): ?>checked<?php endif; ?> value="1"> <?php echo L('remember_pass');?></label>
					<div class="blank" style="height:10px;"></div>
					<input type="hidden" name="backurl" value="<?php echo ($backurl); ?>">
					<button type="submit" class="loginbtn" id="submit"><?php echo L('login');?></button>
				</form>
			</div>
		</div>
	</div>
</div>
<div style="clear:both;"></div>
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
<script type="text/javascript">
	$(function(){
			$('form#login').submit(function(){
				var verify=$('#verify').val();
			    if(verify==""){
					alert('验证码不能为空');
					return false;
			    }
				var self = $(this);
					$.post(self.attr("action"), self.serialize(), success, "json");
					return false;
					function success(data){
						if (data.status) {
							$.thinkbox.success(data.info);
							setTimeout(jumpurl(data.url),1500);
						}else{
							$.thinkbox.error(data.info);
							$('#code').click();
						};
					}
			})
	})
</script>