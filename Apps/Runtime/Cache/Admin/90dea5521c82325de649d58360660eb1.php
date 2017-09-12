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
							<li class="active">代购管理</li>
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
			代购管理
			<small> <i class="icon-double-angle-right"></i>
				代购列表
			</small>
		</h1>
	</div>
	<!-- /.page-header -->	
	<div class="row">
		<div class="col-xs-12">
			<div>
				<ul class="nav nav-tabs" id="myTab">
					<li <?php if(($data['status']) == ""): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('Daigou/lists');?>"> <i class="green icon-home bigger-110"></i>
							全部
						</a>
					</li>
					<li <?php if(($data['status']) == "1"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('Daigou/lists','status=1');?>">
							未付款
						</a>
					</li>

					<li <?php if(($data["status"]) == "2"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=2');?>">
							已付款
							<!-- <span class="badge badge-danger">4</span> -->
						</a>
					</li>

					<li <?php if(($data["status"]) == "3"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=3');?>">
							已确认
						</a>
					</li>
					<li <?php if(($data["status"]) == "4"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=4');?>">
							已采购
						</a>
					</li>
					<li <?php if(($data["status"]) == "5"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=5');?>">
							已入库
						</a>
					</li>
					<li <?php if(($data["status"]) == "6"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=6');?>">
							已出库
						</a>
					</li>
					<!-- <li <?php if(($data["status"]) == "6"): ?>class="active"<?php endif; ?>>
						<a  href="<?php echo U('Daigou/lists','status=7');?>">
							已评价
						</a>
					</li> -->
				<!-- <?php if(($data["status"]) == "2"): ?><a href="<?php echo U('exportexcel','status='.$data['status'].'&keyword='.$_GET['keyword']);?>" style="display:block;float: right;margin-left: 10px;" class="btn btn-danger btn-minier">全部导出Excel</a>
					<a href="<?php echo U('dayinlist','status='.$data['status'].'&keyword='.$_GET['keyword']);?>" target="_blank" style="display:block;float: right;" class="btn btn-success btn-minier">打印全部</a><?php endif; ?> -->
				</ul>
			</div>
			<!-- PAGE CONTENT BEGINS -->		
			<table id="sample-table-1" class="table table-striped table-bordered table-hover" style="font-size: 12px;">
				<thead>
					<tr>
						<?php if(($data["status"]) == "2"): ?><th class="center" style="width:50px;"><label><input type="checkbox" class="ace" id="checkall" ><span class="lbl"></span></label></th><?php endif; ?>
						<th class="center" style="width:80px;">代购单号</th>
						<th>用户</th>
						<th>收货信息</th>
						<th>运送方式</th>
						<th>所属订单</th>
						<th>代购金额RMB</th>
						<th>备注</th>
						<th>状态</th>
						<th>添加时间</th>
						<th>操作</th>
					</tr>
				</thead>

				<tbody>
					<?php if($list): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
							<?php if(($data["status"]) == "2"): ?><td class="center"><input type="checkbox" name="ydids[]" class="ace" value="<?php echo ($v["id"]); ?>"><span class="lbl"></span></td><?php endif; ?>
							<td class="center"><a href="<?php echo U('Daigou/view','id='.$v['id']);?>"><?php echo ($v["dgsn"]); ?></a></td>
							<td><?php echo ($v["uname"]); ?></td>
							<td>收货人：<?php echo ($v["consignee"]); ?> 电话：<?php echo ($v["tel"]); ?> 
								<br>国家：<?php echo ($v["areaname"]); ?> 地址：<?php echo ($v["address"]); ?></td>
							<td><?php echo ($v["dname"]); ?></td>
							<td><?php echo ($v["ydsn"]); ?></td>
							<td>￥:<?php echo ($v["totalprice"]); ?></td>
							<td><?php echo ($v["remark"]); ?></td>
							<td><?php echo (getdaigoustatus($v["status"])); if(($v["status"]) == "1"): ?><br><?php echo ($v["daoqi"]); endif; ?></td>
							<td><?php echo (date("Y-m-d",$v["addtime"])); ?></td>
							<td class="action-buttons">
							<?php if($v["status"] == 2): ?><a class="blue queren" href="javascript:;" uid="<?php echo ($v["id"]); ?>" title="确认订单"> 确认
								</a><?php endif; ?>
							<?php if($v["status"] <= 2): ?><a class="blue edityundan" href="#modal-table" data-toggle="modal" uid="<?php echo ($v["id"]); ?>"> <i class="icon-pencil bigger-130"></i>
								</a><?php endif; ?>
							<?php if($v["shenhe"] == 0): ?><a class="blue queren2" href="javascript:;" uid="<?php echo ($v["id"]); ?>" title="审核订单"> 待审核
								</a>
							<?php elseif($v["status"] > 1): ?>
							<?php else: ?><a class="blue queren2" title="审核订单">     已审核
								</a><?php endif; ?>
								<!-- <?php if($v["status"] < 2): ?><a class="red delete" href="###" sid="<?php echo ($v["id"]); ?>" onclick="return false;"> <i class="icon-trash bigger-130"></i>
									</a><?php endif; ?> -->
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php else: ?>
					<tr><td colspan="12">没有您要的信息</td></tr><?php endif; ?>
				</tbody>
			</table>
			<?php if(($data["status"]) == "2"): ?><div style="float: left;">
					<a href="javascript:;" style="display:block;float: left;margin-right: 10px;" class="btn btn-danger btn-minier" id="querencheck">确认选中</a>
					<!-- <a href="javascript:;" style="display:block;float: left;margin-right: 10px;" class="btn btn-danger btn-minier" id="daochucheck">导出选中</a>

					<a href="javascript:;" target="_blank" style="display:block;float: right;" class="btn btn-success btn-minier" id="dayincheck">打印选中</a> -->
				</div><?php endif; ?>
			<div class="page"><?php echo ($_page); ?></div>
			<div id="modal-table" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">&times;</span>
								</button>
								编辑代购单
							</div>
						</div>
						<form class="form-horizontal" id="edit" style="padding:10px;" method="post" action="<?php echo U('edit');?>">
							<div class="modal-body no-padding">

								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="uname">所属用户:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="uname" id="uname" class="col-xs-12 col-sm-6" value="" disabled />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="dgsn">代购单号:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="dgsn" id="dgsn" class="col-xs-12 col-sm-6" value="" disabled />
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="deliveryid">运送方式:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<select name="deliveryid" id="deliveryid">
												
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="totalgoodsprice">物品金额:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="totalgoodsprice" id="totalgoodsprice" class="col-xs-12 col-sm-6" value="<?php echo ($info["totalgoodsprice"]); ?>" disabled="disabled"/>			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="totalgoodsfreight">运费:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="totalgoodsfreight" id="totalgoodsfreight" class="col-xs-12 col-sm-6" value="<?php echo ($info["totalgoodsfreight"]); ?>" />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="consignee">收货人:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="consignee" id="consignee" class="col-xs-12 col-sm-6" value="<?php echo ($info["consignee"]); ?>" />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="tel">电话:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="tel" id="tel" class="col-xs-12 col-sm-6" value="<?php echo ($info["tel"]); ?>" />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="areaid">国家:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<select name="areaid" id="areaid">
												<?php if(is_array($area)): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
											</select>			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="address">收货地址:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="address" id="address" class="col-xs-12 col-sm-6" value="<?php echo ($info["address"]); ?>" />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="zipcode">邮政编码:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="zipcode" id="zipcode" class="col-xs-12 col-sm-6" value="<?php echo ($info["zipcode"]); ?>" />			
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3 no-padding-right" for="remark">备注:</label>

									<div class="col-sm-9">
										<div class="clearfix">
											<input type="text" name="remark" id="remark" class="col-xs-12 col-sm-6" value="<?php echo ($info["remark"]); ?>" />			
										</div>
									</div>
								</div>

								<div style="clear:both;"></div>
							</div>
							<div class="modal-footer no-margin-top">
								<input type="hidden" name="id" id="dgid" value="">			
								<button type="submit" class="btn btn-sm btn-success"> <i class="icon-ok"></i>
									提交
								</button>
								<button class="btn btn-sm btn-danger" data-dismiss="modal"> <i class="icon-remove"></i>
									取消
								</button>
							</div>
						</form>
					</div>

					<!-- /.modal-content -->			
				</div>
				<!-- /.modal-dialog -->			
			</div>
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
	<script src="/Public/Admin/js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="/Public/Common/js/thinkbox/jquery.thinkbox.js"></script>

		<!-- ace scripts -->

		<script src="/Public/Admin/js/ace-elements.min.js"></script>
		<script src="/Public/Admin/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		

		<script type="text/javascript">
		jQuery(function($){
			$('.chexiaodd').click(function(){
				var id=$(this).attr('uid');
				$.thinkbox.confirm('确认要撤销订单么？订单撤销后，原预报单号会显示在途状态，需要重新入库。',{
				 	'dataEle' : this,
                    'ok':'确认撤销',
                    'cancel':'关闭',
				 	'afterHide':function(data){
				 		if (data==true) {
				 			$.post('<?php echo U("chexiaodd");?>',{'id':id},function(data){
					if (data.status) {
						$.thinkbox.success(data.info);
						setTimeout('location.reload()',1500);
					}else{
						$.thinkbox.error(data.info);
					};
				})
				 		};
                        
				 	},
				 });
			})
			$('#daochucheck,#dayincheck').click(function(){
				var checkstr="";
				$('input[name="ydids[]"]:checked').each(function(){
					checkstr+=$(this).val()+',';
				})
				if (checkstr=="") {
					$.thinkbox.error('至少选择一条数据');
					return false;
				};
			})
			$('#querencheck').click(function(){
				var checkstr="";
				$('input[name="ydids[]"]:checked').each(function(){
					checkstr+=$(this).val()+',';
				})
				if (checkstr=="") {
					$.thinkbox.error('至少选择一条数据');
					return false;
				};
				bootbox.confirm("确定更改为已确认状态么?确认后，用户将不能取消修改订单。", function(result){
				if (result) {
					$.post("<?php echo U('querendaigou');?>",{'id':checkstr},function(data){
						if (data.status) {
							$.gritter.add({
									text: data.info,
									class_name: 'gritter-success gritter-light'
								});
							setTimeout(jumpu(data.url),1000);
						};
					})
				};
			})
			})
			$('input[name="ydids[]"]').change(function(){
				var checkstr="";
				$('input[name="ydids[]"]:checked').each(function(){
					checkstr+=$(this).val()+',';
				})
				$('#daochucheck').attr('href','<?php echo U("exportexcel","status=".$data["status"]."&keyword=".$_GET["keyword"]);?>?ydids='+checkstr);
				$('#dayincheck').attr('href','<?php echo U("dayinlist","status=".$data["status"]."&keyword=".$_GET["keyword"]);?>?ydids='+checkstr);
			})
			$('#checkall').change(function(){
				var selected=$(this).is(':checked');
					$('input[name="ydids[]"]').each(function(index,item){
                		$(item).prop('checked',selected);
            		});
				var checkstr="";
				$('input[name="ydids[]"]:checked').each(function(){
					checkstr+=$(this).val()+',';
				})
				$('#daochucheck').attr('href','<?php echo U("exportexcel","status=".$data["status"]."&keyword=".$_GET["keyword"]);?>?ydids='+checkstr);
				$('#dayincheck').attr('href','<?php echo U("dayinlist","status=".$data["status"]."&keyword=".$_GET["keyword"]);?>?ydids='+checkstr);
			})
	$('.delete').click(function(){
					var id=$(this).attr('sid');
					bootbox.confirm("确定要删除么?", function(result){
						if(result) {
							$.post("<?php echo U('delete');?>",{'id':id,'ajax':1},success,'json');
							function success(data){
								if (data.status) {
									$.gritter.add({
										// (string | mandatory) the heading of the notification
										title: '提交成功',
										// (string | mandatory) the text inside the notification
										text: data.info,
										class_name: 'gritter-success gritter-light'
									});
									setTimeout('location.reload()',1000);
								}else{
									$.gritter.add({
										title: '提交失败',
										text: data.info,
										class_name: 'gritter-error gritter-light'
									});
								};
							}
						}
					})
					
				})
	$('.edityundan').click(function(){
		var id=$(this).attr('uid');
		$.post("<?php echo U('getdelivery');?>",{'id':id},function(result){
			$('#deliveryid option').remove();
			$.each(result,function(i,lx){
                var value=lx.id;
                var text=lx.dname;
                $('#deliveryid').append("<option value='"+value+"'>"+text+"</option>");
            })
		})
		$.post("<?php echo U('getdaigou');?>",{'id':id},function(data){
			$('#uname').val(data.uname);
			$('#dgsn').val(data.dgsn);
			$('#deliveryid').val(data.deliveryid);
			$('#totalgoodsprice').val(data.totalgoodsprice);
			$('#totalgoodsfreight').val(data.totalgoodsfreight);
			$('#remark').val(data.remark);
			// $('#bgleixing').val(data.bgleixing);
			$('#dgid').val(data.id);
			// $('#chaibao').val(data.chaibao);
			$('#consignee').val(data.consignee);
			$('#tel').val(data.tel);
			$('#address').val(data.address);
			$('#zipcode').val(data.zipcode);
			$('#areaid').val(data.areaid);
		})
	})
	$('.ruku').click(function(){
		var id=$(this).attr('uid');
		$.post("<?php echo U('getyubao');?>",{'id':id},function(data){
			$('#rukuuname').val(data.uname);
			$('#rukukdsn').val(data.kdsn);
			$('#rukukdname').val(data.kdname);
			$('#rukubgname').val(data.bgname);
			$('#rukuybid').val(data.id);
			
		})
	})
	$('.queren').click(function(){
		var id=$(this).attr('uid');
		bootbox.confirm("确定要确认订单么?", function(result){
			if (result) {
				$.post("<?php echo U('querendaigou');?>",{'id':id},function(data){
			if (data.status) {
				$.thinkbox.success(data.info);
				setTimeout('location.reload()',1500);
			}else{
				$.thinkbox.error(data.info);
			};
		})
			};
		
	})
	})
	$('.queren2').click(function(){
		var id=$(this).attr('uid');
		bootbox.confirm("确定要确认订单么?", function(result){
			if (result) {
				$.post("<?php echo U('shenhedaigou');?>",{'id':id},function(data){
			if (data.status) {
				$.thinkbox.success(data.info);
				setTimeout('location.reload()',1500);
			}else{
				$.thinkbox.error(data.info);
			};
		})
			};
		
	})
	})
	$('.zhifu').click(function(){
		var id=$(this).attr('uid');
		bootbox.confirm("确定要支付订单么？",function(result){
			if (result) {
				$.post("<?php echo U('paydingdan');?>",{'ydid':id},function(data){
					if (data.status) {
						$.thinkbox.success(data.info);
						setTimeout('location.reload()',1500);
					}else{
						$.thinkbox.error(data.info);
					};
				})
			};
		})
	})
	$('.qufahuo').click(function(){
		var id=$(this).attr('uid');
		bootbox.confirm("确定更改为已发货状态么?", function(result){
			if (result) {
				$.post("<?php echo U('qufahuo');?>",{'id':id},function(data){
			if (data.status) {
				$.gritter.add({
						text: data.info,
						class_name: 'gritter-success gritter-light'
					});
				setTimeout('location.reload()',1000);
			};
		})
			};
		
	})
	})
	$(".chosen-select").chosen(); 
				$('#ruku-table').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
				})
	})
		$("form#edit").submit(function(){
    		var self = $(this);
    		$.post(self.attr("action"), self.serialize(), success, "json");
    		return false;
    		function success(data){
    			if(data.status){
    				$.gritter.add({
						title: '更新成功',
						text: data.info,
						class_name: 'gritter-success gritter-light'
					});
					setTimeout('location.reload()',1000);
    			} else {
    				$.gritter.add({
						title: '更新失败',
						text: data.info,
						class_name: 'gritter-success gritter-light'
					});
    			}
    		}
    	});
    	$("form#ruku").submit(function(){
    		var self = $(this);
    		alert(self.serialize());
    		$.post(self.attr("action"), self.serialize(), success, "json");
    		return false;
    		function success(data){
    			if(data.status){
    				$.gritter.add({
						title: '更新成功',
						text: data.info,
						class_name: 'gritter-success gritter-light'
					});
					setTimeout('location.reload()',1000);
    			} else {
    				$.gritter.add({
						title: '更新失败',
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