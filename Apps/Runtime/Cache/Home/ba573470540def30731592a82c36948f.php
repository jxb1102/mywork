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



 <script>
  $(function(){
    setInterval("getTime();",1000); //每隔一秒执行一次
    })
    //取得系统当前时间
    function getTime(){
    var myDate = new Date();
    var year = myDate.getFullYear();    //获取完整的年份(4位,1970-????)
    var month = myDate.getMonth()+1;       //获取当前月份(0-11,0代表1月)
    var day = myDate.getDate(); 
    var hours = myDate.getHours();
    var minutes = myDate.getMinutes();
    var seconds = myDate.getSeconds();
    if(month<10){
      month='0'+month;
    }
    if(day<10){
      day='0'+day;
    }
    if(hours<10){
      hours='0'+hours;
    }
    if(minutes<10){
      minutes='0'+minutes;
    }
    if(seconds<10){
      seconds='0'+seconds;
    }
    $("#time").html(year+"-"+month+"-"+day+" "+hours+":"+minutes+":"+seconds); 
  } 
 </script>

<div class="middle">
  <div class="column1200">
    <div class="banner">
      <div id="playBox">
        <div class="pre"></div>
        <div class="next"></div>
        <div class="smalltitle">
          <ul>
            <?php if(is_array($indexad)): $k = 0; $__LIST__ = $indexad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vk): $mod = ($k % 2 );++$k;?><li <?php if($k == 1): ?>class="thistitle"<?php endif; ?> ></li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        </div>
        <ul class="oUlplay">
        <?php if(is_array($indexad)): $i = 0; $__LIST__ = $indexad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($v["uri"]); ?>"><img src="<?php echo ($v["pic"]); ?>" /></a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
    <div class="column290">

    <?php if($_SESSION['user_auth']!= ''): ?><div class="login">
        
        <h3><?php echo L('welcome_ni');?>,<b><?php echo ($user["nickname"]); ?></b></h3>
        <ul class="loginlist">
            <li><a href="<?php echo U('Cart/index');?>" style="color:#333;"><?php echo L('mycart');?><font style="color:red">(<?php echo ($cartcount); ?>)</font></a></li>
            <li><a href="<?php echo U('User/yubaolist','status=2');?>" style="color:#333;"><?php echo L('index2');?><font style="color:red"></font></a></li>
            <li><a href="<?php echo U('User/dingdan');?>" style="color:#333;"><?php echo L('index3');?><font style="color:red">(<?php echo ($yuncount); ?>)</font></a></li>
            <li><a href="<?php echo U('User/msg');?>" style="color:#333;"><?php echo L('index4');?><font style="color:red">(<?php echo ($msgcount); ?>)</font></a></li>
        </ul>
        <p class="loginend">
          <a href="<?php echo U('User/setting');?>"><?php echo L('setting_user');?></a>&nbsp;|
          <a href="<?php echo U('User/index');?>"><?php echo L('user_center');?></a>&nbsp;|
          <a href="<?php echo U('Public/logout');?>"><?php echo L('logout');?></a>
        </p>

      </div>

      <?php else: ?>
      <div class="login">
        
        <form id="toplogin" action="<?php echo U('Public/checklogin');?>" method="post" style="padding:3px 0;">
        <p class="login_username loginbg" >
          <input name="username" type="text" class="login_set" placeholder="<?php echo L('username');?>">
        </p>
        <p class="login_username passwordbg" >
          <input name="password" type="password" class="login_set" placeholder="<?php echo L('password');?>"
        >
        </p>
        <div class="yzm">
        <span style="float:left;"><?php echo L('yzm');?>：&nbsp;</span><img src="<?php echo U('Public/verify');?>" title="看不清点我刷新" onclick="this.src=this.src+'?'+Math.random()" style="float:left;" id="code"/>&nbsp;&nbsp;<input type="text" name="verify" id="verify" value="" class="" maxlength="6" placeholder="<?php echo L('yzm');?>" style="width:80px; height:20px;">
        <a href="<?php echo U('Public/zhaomima');?>" style="float:right;color:blue;font-size:12px;text-decoration:underline"><?php echo L('wwjj');?></a>
        </div>
        <!-- <div style="clear:both;"></div> -->
        <p class="login_username2">
          <button type="submit" class="login_btn logincolor" id="submit"><?php echo L('login');?></button>
        </p>
      </form>
      <p class="login_username2">
          <a href="<?php echo U('Public/reg');?>"><button  class="login_btn logincolor2" value="免费注册"><?php echo L('reg');?></button></a>
       </p>
      </div><?php endif; ?>
      <div class="notice">
        <dl class="notice_title">
          <dt><?php echo L('site_notice');?></dt>
          <dd><a href="<?php echo U('Cate/lists','id=31');?>"><?php echo L('more');?>>></a></dd>
        </dl>
        <ul class="notice_list">

          <?php if(is_array($noticelist)): $i = 0; $__LIST__ = $noticelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Article/view','id='.$v['id']);?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="column1200">
    <div class="column900 mg_T10">
      <ul class="zhuanyun">
        <li><a href="<?php echo U('User/yubaoonly');?>"><img src="/Public/Home/default/images/zhuanyun.jpg" alt="国际转运" /></a><span><?php echo L('zhuanyun_xiang');?><a href="<?php echo U('Cate/gjzy');?>">[<?php echo L('more');?>]</a></span><a href="<?php echo U('Cate/zyyanshi');?>" class="Demonstrate"><?php echo L('zyyanshi');?></a></li>
        <li style="width:389px;"><a href="<?php echo U('Daigou/index');?>"><img src="/Public/Home/default/images/daigou.jpg" alt="海外代购" /></a><span><?php echo L('daigou_xiang');?><a href="<?php echo U('Daigou/index');?>">[<?php echo L('more');?>]</a></span><a href="<?php echo U('Cate/dgyanshi');?>" class="Demonstrate"><?php echo L('dgyanshi');?></a></li>
      </ul>
      <style>
      .logistics img{border:1px solid #eee; border-radius: 5px; padding: 3px;}
      </style>
      <ul class="logistics">
        <h2><?php echo L('guojiwuliu');?></h2>
        <?php if(is_array($wuliu)): $i = 0; $__LIST__ = $wuliu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wg): $mod = ($i % 2 );++$i;?><li><img src="<?php echo ($wg["pic"]); ?>" title="<?php echo ($wg["title"]); ?>"/></li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      <ul class="logistics">
        <h2><?php echo L('zhufufangshi');?></h2>
        <?php if(is_array($pay)): $i = 0; $__LIST__ = $pay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pg): $mod = ($i % 2 );++$i;?><li><img src="<?php echo ($pg["pic"]); ?>" title="<?php echo ($pg["title"]); ?>" style="padding-left:15px;"/></li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      <p class="theme_pic mg_T10"><img src="/Public/Home/default/images/mian.jpg" alt="免" style="padding-right:16px;"/><img src="/Public/Home/default/images/te.jpg" alt="特"/></p>
    </div>
    <div class="column290">
      <dl class="chaxun mg_T10">
        <dt style="font-size:16px;color:#5a5a5a;"><?php echo L('yundanchaxun');?></dt>
        <dd><form action="<?php echo U('Index/tracking');?>" method="post">
          <textarea name="kdsn" cols="" rows="" class="cx"></textarea>
          <input type="submit" value="<?php echo L('chaxun');?>" class="cha">
          <input type="reset" value="<?php echo L('qingchu');?>" class="qing"></form>
          <span style="float:right; color:#333;font-size:14px;line-height:30px;"><a href="http://m.kuaidi100.com" target="_blank">快递查询</a></span>
        </dd>
      </dl>
    <ul class="freight mg_T10">
        <h2 style="color:#5a5a5a"><b><?php echo L('gongju');?></b></h2>
        <div class="blank"></div>
        <li><img src="/Public/Home/default/images/fygs.png" title="<?php echo L('gusuan');?>"/>&nbsp;<a href="<?php echo U('Cate/gusuan');?>"><?php echo L('gusuan');?></a></li>
        <li><img src="/Public/Home/default/images/gjyf.png" title="<?php echo L('guojiyunfei');?>"/>&nbsp;<a href="<?php echo U('Cate/gjyf');?>"><?php echo L('guojiyunfei');?></a></li>
        <li><img src="/Public/Home/default/images/hl.png" title="<?php echo L('huilv');?>"/>&nbsp;<a href="http://www.boc.cn/sourcedb/whpj/" target="_blank" ><?php echo L('huilv');?></a></li>
        <li><img src="/Public/Home/default/images/cc.png" title="<?php echo L('chicun');?>"/>&nbsp;<a href="<?php echo U('Cate/huansuan');?>"><?php echo L('chicun');?></a></li>
      </ul>
     <ul class="contact">
         <li><span><?php echo L('kfemail');?>：</span></li>
         <li><a href="#">service@procurementchina.com</a></li>  
         <li><span><?php echo L('touemail');?>:</span></li>
         <li><a href="#">complaint@procurementchina.com</a></li>
         <li><span><?php echo L('mobile');?>：</span></li>
         <li><span style="font-size:16px;"><?php echo C('service_tel');?></span></li>
         <li><span><?php echo L('time');?>：</span><?php echo C('time');?></li>
         <li style="background:#666;padding:2px 0;margin-bottom:5px;color:#FFF;"> <?php echo L('bjtime');?>：<span id="time" style="color:#FFF;margin-left:5px;"></span></li>
      </ul>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('form#toplogin').submit(function(){
          var verify=$("#verify").val();
          if(verify==""){
             alert('验证码必须填写');
             return false;
          }
          var self = $(this);
          $.post(self.attr("action"), self.serialize(), success, "json");
          return false;
          function success(data){
            if (data.status) {
              $.thinkbox.success(data.info);
              setTimeout('location.reload()',1000);
            }else{
              $.thinkbox.error(data.info);
              $('#code').click();
            };
          }
        })
    })
</script>
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