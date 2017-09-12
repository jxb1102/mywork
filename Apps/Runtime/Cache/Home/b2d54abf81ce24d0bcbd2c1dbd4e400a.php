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


<div class="middle">

  <div class="column1200">
  <div class="yufeigusuanbox1 mg_T10">
           <h3 style="text-align:center;font-size:16px;color:red;"><?php echo L('yfduibi');?></h3>
           <div class="blank"></div>
                     <div class="titlebox1124-1">
                        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="btnppx1124-1">
                            <a id="s_<?php echo ($k); ?>" href="javascript:;" onmouseover="show_country(<?php echo ($k); ?>);" <?php if($v['id'] == $this_zid): ?>class="selected1"<?php else: endif; ?> ><?php echo ($v["name"]); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
                     </div>
                    <?php if(is_array($list)): $kk = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$n): $mod = ($kk % 2 );++$kk;?><div class="remengj-box1" <?php if($n['id'] == $this_zid): else: ?>style="display:none;"<?php endif; ?>id="country_<?php echo ($kk); ?>">
                        <?php if(is_array($n['list'])): $i = 0; $__LIST__ = $n['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><div class="guojia-1"><a href="<?php echo U('Cate/gjyf','areaid='.$g['id']);?>" ><?php echo ($g["name"]); ?>&nbsp;</a></div><?php endforeach; endif; else: echo "" ;endif; ?>
                      </div><?php endforeach; endif; else: echo "" ;endif; ?>
          
      <div  class="jisonggj-box1">　　
        <?php echo L('jsguojia');?>：<span id="country"><?php echo ($st["name"]); ?></span>
      </div>
      <style>
       .preview td{font-size: 13px;font-weight: 600;color:#eee;background-color: rgb(0,176,180);}
       .preview th{font-weight: 400;border-bottom:1px #dedede solid;}
      </style>
        <div class="liebiao1124-box1"> 
           <table cellspacing="0" cellpadding="5" border="0" align="center" width="100%" class="preview">
             <tr>
              <td width="140" height="32" bgcolor="#9c9c9c"><?php echo L('ysfangshi');?></td>
              <td width="140" bgcolor="#9c9c9c"><?php echo L('shouzhong');?></td>
              <td width="140" bgcolor="#9c9c9c"><?php echo L('xuzhong');?></td>
              <td bgcolor="#9c9c9c" width="140"><?php echo L('jszhouqi');?></td>
              <td bgcolor="#9c9c9c" width="150"><?php echo L('tese');?></td>
              <td bgcolor="#9c9c9c"><?php echo L('yjwupin');?></td>
              <td bgcolor="#9c9c9c"><?php echo L('tjxianzhi');?></td>
             </tr>
             <?php if(is_array($dellist)): $i = 0; $__LIST__ = $dellist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vd): $mod = ($i % 2 );++$i;?><tr>
                  <th><?php echo ($vd["dname"]); ?></th>
                  <th><b style="color:red;"><?php echo ($vd["first_fee"]); ?></b>/<?php echo ($vd["first_weight"]); ?></th>
                  <th><b style="color:red;"><?php echo ($vd["second_fee"]); ?></b>/<?php echo ($vd["xu_weight"]); ?></th>
                  <th><?php echo ($vd["zhouqi"]); ?></th>
                  <th><?php echo ($vd["tese"]); ?></th>
                  <th><?php echo ($vd["yanjin"]); ?></th>
                  <th><?php echo ($vd["tiji"]); ?></th>
                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
           </table>
        </div>
 
  <div  class="bottom1124-box1"><img src="/Public/Home/default/images/wenhao333.png">&nbsp;<?php echo L('tishi');?></div>
 
    </div>
      
    </div>

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
  function show_country(id){
      $(".titlebox1124-1").children().children().removeClass();
      $("#s_"+id).addClass("selected1");
      $(".remengj-box1").hide();
      $("#country_"+id).show();
  }  
  function show_desc(id){
      $("#td_ul_"+id).show();
  }
  function hide_desc(id){
      $("#td_ul_"+id).hide();
  }
  
</script>