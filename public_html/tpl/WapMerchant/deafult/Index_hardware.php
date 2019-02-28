<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		</a>
		<p id="pigcms-header-title">设备管理</p>
		<a  href="{pigcms{:U('Index/hardware_add')}" id="pigcms-header-right">添加设备</a>
	</header>
	<div class="container container-fill">
		<!--左侧菜单-->
		<include file="Public:leftMenu"/>
		<!--左侧菜单结束-->
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_staff.css">
	<script type="text/javascript" src="{pigcms{$static_path}/js/iscroll.js"></script>
	<div id="staff-list-wrapper" class='pigcms-main'>
		<div id="staff-list-scroller">
			<ul id="staff-list-ul" >
			<volist id='list' name='list'>
				<li class="staff-list-li">
					<p class="staff-list-info">
						<span class="staff-name" style='width:2６px;'><span style='color:red'>设备({pigcms{$i})</span></span>
						<span class="staff-type">绑定账号：{pigcms{$list.username}</span><br/><br/>
						<a href="tel:{pigcms{$list.mp}" class="staff-phone">手机号：{pigcms{$list.mp}</a>&nbsp;&nbsp;<span class="staff-type">终端号：{pigcms{$list.mcode}</span>
					</p>
					<p class="staff-list-operation">
					  <a href="{pigcms{:U('Index/hardware_add',array('id'=>$list['pigcms_id']))}" class="staff-operation-remove">
							<i class="iconfont icon-edit"></i>
							<span> 编辑</span>
						</a>
						<br/>
						<br/>
						<a href="{pigcms{:U('Index/hardware_dell',array('id'=>$list['pigcms_id']))}" class="staff-operation-remove" onclick="delcfm()">
							<i class="iconfont icon-shanchu"></i>
							<span> 删除</span>
						</a>
					</p>
					<div class="clearfix"></div>
				</li>
			</volist>
			</ul>
		</div>
	</div>
	<script language="javascript">
    function delcfm() {
        if (!confirm("确认要删除？")) {
            window.event.returnValue = false;
        }
    }
</script>
	</div>
</body>
	<include file="Public:footer"/>
</html>
