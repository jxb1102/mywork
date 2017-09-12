<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>后台首页 - <?php echo (C("domain_title")); ?>管理平台</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->

		<link href="/Public/Admin/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/Public/Admin/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="/Public/Admin/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="/Public/Admin/css/jquery-ui-1.10.3.custom.min.css" />
		<link rel="stylesheet" href="/Public/Admin/css/jquery.gritter.css" />
		<link rel="stylesheet" href="/Public/Admin/css/chosen.css" />

		<!-- fonts -->

		<!-- <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" /> -->

		<!-- ace styles -->

		<link rel="stylesheet" href="/Public/Admin/css/ace.min.css" />
		<link rel="stylesheet" href="/Public/Admin/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="/Public/Admin/css/ace-skins.min.css" />
		<link rel="stylesheet" href="/Public/Admin/css/style.css">
		<link rel="stylesheet" type="text/css" href="/Public/Admin/js/thinkbox/skin/win7/style.css">
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/Public/Admin/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

		<script src="/Public/Admin/js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="/Public/Admin/js/html5shiv.js"></script>
		<script src="/Public/Admin/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		
		<div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="<?php echo U('Index/index');?>" class="navbar-brand">
						<small>
							<i class="icon-leaf"></i>
							管理后台
						</small>
					</a><!-- /.brand -->
				</div><!-- /.navbar-header -->

				<div class="navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="blue">
							<a href="/" target="_blank">
								<i class="icon-home home-icon"></i>
								前台首页
							</a>
						</li>
						<!-- <li class="grey">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="icon-tasks"></i>
								<span class="badge badge-grey">4</span>
							</a>

							<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="icon-ok"></i>
									4 个任务要完成
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">软件升级</span>
											<span class="pull-right">65%</span>
										</div>

										<div class="progress progress-mini ">
											<div style="width:65%" class="progress-bar "></div>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">硬件升级</span>
											<span class="pull-right">35%</span>
										</div>

										<div class="progress progress-mini ">
											<div style="width:35%" class="progress-bar progress-bar-danger"></div>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">单元测试</span>
											<span class="pull-right">15%</span>
										</div>

										<div class="progress progress-mini ">
											<div style="width:15%" class="progress-bar progress-bar-warning"></div>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">Bug 修复</span>
											<span class="pull-right">90%</span>
										</div>

										<div class="progress progress-mini progress-striped active">
											<div style="width:90%" class="progress-bar progress-bar-success"></div>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										查看任务详情
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li> -->

						<li class="purple">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="icon-bell-alt icon-animated-bell"></i>
								<span class="badge badge-important"><?php echo ($newtzcount); ?></span>
							</a>

							<ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="icon-warning-sign"></i>
									<?php echo ($newtzcount); ?> 个通知
								</li>

								<!-- <li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-pink icon-comment"></i>
												新评论
											</span>
											<span class="pull-right badge badge-info">+12</span>
										</div>
									</a>
								</li> -->

								<!-- <li>
									<a href="#">
										<i class="btn btn-xs btn-primary icon-user"></i>
										Bob 刚注册成为管理员
									</a>
								</li> -->

								<li>
									<a href="<?php echo U('Yundan/lists');?>">
										<div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-success icon-shopping-cart"></i>
												新订单
											</span>
											<span class="pull-right badge badge-success">+<?php echo ($newyundancount); ?></span>
										</div>
									</a>
								</li>
								<li>
									<a href="<?php echo U('Caiwu/chongzhi');?>">
										<div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-success icon-shopping-cart"></i>
												充值汇款单
											</span>
											<span class="pull-right badge badge-success">+<?php echo ($newhuikuancount); ?></span>
										</div>
									</a>
								</li>

								<!-- <li>
									<a href="#">
										<div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info icon-twitter"></i>
												粉丝
											</span>
											<span class="pull-right badge badge-info">+11</span>
										</div>
									</a>
								</li> -->

								<!-- <li>
									<a href="#">
										查看全部通知
										<i class="icon-arrow-right"></i>
									</a>
								</li> -->
							</ul>
						</li>

						<li class="green">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="icon-envelope icon-animated-vertical"></i>
								<span class="badge badge-success"><?php echo ($newmsgcount); ?></span>
							</a>

							<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="icon-envelope-alt"></i>
									<?php echo ($newmsgcount); ?> 条新信息
								</li>
								<?php if($newmsglist): if(is_array($newmsglist)): $i = 0; $__LIST__ = $newmsglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><li>
											<a href="#">
												<span class="msg-body" style="max-width:100%;">
													<span class="msg-title">
														<span class="blue"><?php echo ($m["user"]["nickname"]); ?>:</span>
														<?php echo (msubstr($m["content"],0,30)); ?>
													</span>

													<span class="msg-time"> <i class="icon-time"></i>
														<span><?php echo (date("Y-m-d",$m["add_time"])); ?></span>
													</span>
												</span>
											</a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
									<?php else: ?>								
									<li><a href="###">暂无新留言</a></li><?php endif; ?>

								<li>
									<a href="<?php echo U('Msg/liuyan');?>">
										查看全部留言
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="/Public/Admin/avatars/user.jpg" alt="管理员的 头像" />
								<span class="user-info">
									<small>欢迎你,</small>
									<?php echo ($_SESSION['user_auth_admin']['username']); ?>
								</span>

								<i class="icon-caret-down"></i>
							</a>

							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="<?php echo U('Setting/index');?>">
										<i class="icon-cog"></i>
										系统设置
									</a>
								</li>

								<li>
									<a href="#">
										<i class="icon-user"></i>
										个人资料
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="<?php echo U('Public/logout');?>">
										<i class="icon-off"></i>
										退出登录
									</a>
								</li>
							</ul>
						</li>
					</ul><!-- /.ace-nav -->
				</div><!-- /.navbar-header -->
			</div><!-- /.container -->
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<div class="sidebar-shortcuts" id="sidebar-shortcuts">
						<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
							<button class="btn btn-success">
								<i class="icon-signal"></i>
							</button>

							<button class="btn btn-info">
								<i class="icon-pencil"></i>
							</button>

							<button class="btn btn-warning">
								<i class="icon-group"></i>
							</button>

							<button class="btn btn-danger">
								<i class="icon-cogs"></i>
							</button>
						</div>

						<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>

							<span class="btn btn-info"></span>

							<span class="btn btn-warning"></span>

							<span class="btn btn-danger"></span>
						</div>
					</div><!-- #sidebar-shortcuts -->
					<ul class="nav nav-list">
						<?php if(is_array($sidenav)): $i = 0; $__LIST__ = $sidenav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$side): $mod = ($i % 2 );++$i; if(authcheck(MODULE_NAME.'/'.$side['uri'],$adminmem['uid'],'or','1','0') == '1'): ?><li class="<?php if(($side["isopen"]) == "1"): ?>open active<?php endif; ?> <?php if(($side["isactive"]) == "1"): ?>active<?php endif; ?>">
							<a <?php if(($side["children"]) == ""): ?>href="<?php echo U($side['uri']);?>"<?php else: ?>
								 href="#" class="dropdown-toggle"<?php endif; ?> >
								<i class="<?php echo ($side["icon"]); ?>"></i>
								<span class="menu-text"> <?php echo ($side["title"]); ?> </span>
								<?php if(($side["children"]) != ""): ?><b class="arrow icon-angle-down"></b><?php endif; ?>
							</a>
							<?php if(($side["children"]) != ""): ?><ul class="submenu" <?php if(($side["isopen"]) == "1"): ?>style="display:block"<?php endif; ?>>
								<?php if(is_array($side["children"])): $i = 0; $__LIST__ = $side["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; if(authcheck(MODULE_NAME.'/'.$c['uri'],$adminmem['uid'],'or','1','0') == '1'): ?><li <?php if(($c["isactive"]) == "1"): ?>class="active"<?php endif; ?>>
									<a href="<?php echo U($c['uri']);?>" class="green">
										<i class="icon-double-angle-right"></i>
										<?php echo ($c["title"]); ?>
									</a>
								</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
							</ul><?php endif; ?>
						</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						
							</ul>
						</li>
					</ul><!-- /.nav-list -->

					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>

					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo U('Index/index');?>">首页</a>
							</li>
							
	<li class="active">管理员</li>

						</ul><!-- .breadcrumb -->
						
						<div class="nav-search" id="nav-search">
							<form class="form-search" method="GET" action="">
								<span class="input-icon">
									<input type="text" placeholder="搜索..." class="nav-search-input" id="navsearch-input" autocomplete="off" name="keyword" value="<?php echo $_GET['keyword'] ?>" />
									<i class="icon-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- #nav-search -->
					
					</div>

					<div class="page-content">
						
	<div class="page-header">
		<h1>
			管理员
			<small> <i class="icon-double-angle-right"></i>
				管理员添加/编辑
			</small>
		</h1>
	</div>
	<!-- /.page-header -->
	<div class="row">
		<div class="col-sm-6">
			<h3 class="header smaller lighter green"> <i class="icon-bullhorn"></i>
				<?php if($info): ?>编辑成员
					<?php else: ?>
					添加新成员<?php endif; ?>
			</h3>
			<form <?php if($info): ?>action="<?php echo U('memberedit');?>"
				<?php else: ?>
				action="<?php echo U('memberadd');?>"<?php endif; ?>
			class="form-horizontal" id="validation-form" method="post">
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="username">邮箱/用户名:</label>

				<div class="col-xs-12 col-sm-9">
					<div class="clearfix">
						<input type="text" name="username" id="username" class="col-xs-12 col-sm-6" value="<?php echo ($info["username"]); ?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">密码:</label>

				<div class="col-xs-12 col-sm-9">
					<div class="clearfix">
						<input type="text" name="password" id="password" class="col-xs-12 col-sm-6" value="" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="nickname">显示名称:</label>

				<div class="col-xs-12 col-sm-9">
					<div class="clearfix">
						<input type="text" name="nickname" id="nickname" class="col-xs-12 col-sm-6" value="<?php echo ($info["nickname"]); ?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">手机号码:</label>

				<div class="col-xs-12 col-sm-9">
					<div class="clearfix">
						<input type="text" name="mobile" id="mobile" class="col-xs-12 col-sm-6" value="<?php echo ($info["mobile"]); ?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="groupid">所属用户组:</label>

				<div class="col-xs-12 col-sm-9">
					<div class="clearfix">
						<select class="input-medium" id="groupid" name="groupid">
							<option value="0">请选择...</option>
							<?php if(is_array($glist)): $i = 0; $__LIST__ = $glist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($v["id"]) == $info["group"]["id"]): ?>selected<?php endif; ?>
								><?php echo ($v["title"]); ?>
							</option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="status">是否启用:</label>

			<div class="col-xs-12 col-sm-9">
				<div class="clearfix">
					<label class="blue">
						<input name="status" value="1" type="radio" class="ace" checked <?php if(($info["status"]) == "1"): ?>checked<?php endif; ?>
					/>
					<span class="lbl">启用</span>
				</label>
				&nbsp;&nbsp;
				<label class="blue">
					<input name="status" value="0" type="radio" class="ace" <?php if(($info["status"]) == "0"): ?>checked<?php endif; ?>
				/>
				<span class="lbl">不启用</span>
			</label>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">&nbsp;</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<?php if($info): ?><input type="hidden" name="uid" value="<?php echo ($info["uid"]); ?>">
				<button type="submit" class="btn btn-success btn-next no-border">确认编辑</button><a href="<?php echo U('index');?>" class="btn btn-light btn-next no-border">取消</a>
				<?php else: ?>
				<button type="submit" class="btn btn-success btn-next no-border">确认添加</button><?php endif; ?>
		</div>
	</div>
</div>

</form>
</div>

<div class="col-sm-6">
<h3 class="header smaller lighter green">
<i class="icon-bullhorn"></i>
管理员列表
</h3>
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
<thead>
	<tr>
		<th class="center">ID</th>
		<th>登陆邮箱</th>
		<th>昵称</th>
		<th>手机</th>
		<th>用户组</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
</thead>

<tbody>
	<?php if(is_array($memlist)): $i = 0; $__LIST__ = $memlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
			<td class="center"><?php echo ($v["uid"]); ?></td>

			<td><?php echo ($v["username"]); ?></td>
			<td><?php echo ($v["nickname"]); ?></td>
			<td><?php echo ($v["mobile"]); ?></td>
			<td><?php echo (getgroupname($v["uid"])); ?></td>

			<td><?php echo (getstatus($v["status"])); ?></td>

			<td class="action-buttons">
				<a class="blue" href="<?php echo U('index','id='.$v['uid']);?>">
					<i class="icon-pencil bigger-130"></i>
				</a>

				<a class="red delmember" href="###" sid="<?php echo ($v["uid"]); ?>" onclick="return false;">
					<i class="icon-trash bigger-130"></i>
				</a>

			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
</div>
</div>

 <!--/page-content -->
					</div><!-- /.page-content -->
				</div><!-- /.main-content -->

				<div class="ace-settings-container" id="ace-settings-container">
					<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
						<i class="icon-cog bigger-150"></i>
					</div>

					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="default" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; 选择皮肤</span>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> 固定导航</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> 固定侧边栏</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> 固定状态栏</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> 反向 (rtl)</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
							<label class="lbl" for="ace-settings-add-container">
								窄屏
								<b>显示</b>
							</label>
						</div>
					</div>
				</div><!-- /#ace-settings-container -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script src="/Public/Admin/js/jquery-2.0.3.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='/Public/Admin/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/Public/Admin/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/Public/Admin/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="/Public/Admin/js/bootstrap.min.js"></script>
		<script src="/Public/Admin/js/typeahead-bs2.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="/Public/Admin/js/excanvas.min.js"></script>
		<![endif]-->

	<script src="/Public/Admin/js/bootbox.min.js"></script>
<script src="/Public/Admin/js/jquery.gritter.min.js"></script>
<script src="/Public/Admin/js/jquery.validate.min.js"></script>
<script src="/Public/Admin/js/jquery.dataTables.min.js"></script>
<script src="/Public/Admin/js/jquery.dataTables.bootstrap.js"></script>

		<!-- ace scripts -->

		<script src="/Public/Admin/js/ace-elements.min.js"></script>
		<script src="/Public/Admin/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		

<script type="text/javascript">

jQuery(function($){
	$('.delmember').click(function(){
					var id=$(this).attr('sid');
					bootbox.confirm("确定要删除么?", function(result){
						if(result) {
							$.post("<?php echo U('memberdel');?>",{'id':id,'ajax':1},function(data){
								if (data.status) {
									$.gritter.add({
										// (string | mandatory) the heading of the notification
										title: '提交成功',
										// (string | mandatory) the text inside the notification
										text: data.info,
										class_name: 'gritter-success gritter-light'
									});
									setTimeout(jumpu(data.url),1000);
								}else{
									$.gritter.add({
										title: '提交失败',
										text: data.info,
										class_name: 'gritter-error gritter-light'
									});
								};
							},'json');
						}
					})
					
				})
	})
		$("form#validation-form").submit(function(){

    		var self = $(this);
    		$.post(self.attr("action"), self.serialize(), success, "json");

    		return false;

    		function success(data){

    			if(data.status){
    				$.gritter.add({
										// (string | mandatory) the heading of the notification
										title: '提交成功',
										// (string | mandatory) the text inside the notification
										text: data.info,
										class_name: 'gritter-success gritter-light'
									});
									setTimeout(jumpu(data.url),1000);

    			} else {

    				$.gritter.add({
										// (string | mandatory) the heading of the notification
										title: '提交失败',
										// (string | mandatory) the text inside the notification
										text: data.info,
										class_name: 'gritter-success gritter-light'
									});

    			}

    		}

    	});
    	function jumpu(uri){
    		window.location.href=uri;
    	}
														</script>

	</body>
</html>