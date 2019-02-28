<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
<style style="text/css">
 .staff-list-operation a{display:block;margin: 0px 10px 5px 10px;}
 .staff-list-operation{margin:5px;}
 p.staff-list-info{line-height: 25px;}
</style>
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		</a>
		<p id="pigcms-header-title">店员管理</p>
		<a  href="{pigcms{:U('Index/staff_add')}" id="pigcms-header-right">添加店员</a>
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
						<span class="staff-name" style='width:22px;'><span style='color:red'>({pigcms{$i})</span></span>
						&nbsp;&nbsp;电话：<a href="tel:{pigcms{$list.tel}" class="staff-phone">{pigcms{$list.tel}</a>
						<br/>
						&nbsp;店员账号：<span class="staff-type">{pigcms{$list.username}</span>
						<br/>
						&nbsp;店员姓名：<span class="staff-type">{pigcms{$list.name}</span>
						<br/>
						&nbsp;所属店铺：<span class="staff-type">{pigcms{$list.storename}</span>
					</p>
					<div class="staff-list-operation">
						<a href="{pigcms{:U('Index/staff_edit',array('staff_id'=>$list['id']))}" class="staff-operation-remove" onclick="checkDel(this)">
							<i class="iconfont icon-edit"></i>
							<span> 编辑</span>
						</a><br/>
						<a href="{pigcms{:U('Index/loginStaff',array('id'=>$list['id'],'store_id'=>$list['store_id']))}" class="staff-operation-remove">
							<i class="iconfont icon-loading"></i>
							<span> 登陆</span>
						</a><br/>
						<a href="{pigcms{:U('Index/staff_dell',array('staff_id'=>$list['id']))}" class="staff-operation-remove" onclick="delcfm()">
							<i class="iconfont icon-shanchu"></i>
							<span> 删除</span>
						</a>
					</div>
					<!--<p class="staff-list-operation">
						<a href="{pigcms{:U('Index/staff_edit',array('staff_id'=>$list['id']))}" class="staff-operation-remove" onclick="checkDel(this)">
							<i class="iconfont icon-edit"></i>
							<span>编辑</span>
						</a>
					</p>-->
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
