<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
<style type="text/css">#managelist a{color: #029884;}</style>
<header class="pigcms-header mm-slideout">
	<a href="#slide_menu" id="pigcms-header-left">
		<i class="iconfont icon-menu "></i>
	</a>
		<p id="pigcms-header-title">店铺管理</p>
		<a  href="{pigcms{:U('Index/store')}" id="pigcms-header-right">添加店铺</a>
</header>
	<div class="container container-fill" style='padding-top:50px'>
		<!--左侧菜单-->
		<include file="Public:leftMenu"/>
		<!--左侧菜单结束-->
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_staff.css">
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
	<div class="staff-list-wrap">
		<div class="pigcms-container">
			<div class="staff-status-container">
				<p class="staff-status staff-status-cur">
					全部<span>({pigcms{$count['all']})</span>
				</p>
			</div>
			<div class="staff-status-container">
				<p class="staff-status">
					正常<span>({pigcms{$count['status1']})</span>
				</p>
			</div>
			<div class="staff-status-container">
				<p class="staff-status">
					待审核<span>({pigcms{$count['status2']})</span>
				</p>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
		<div id="staff-list-wrapper" class='pigcms-main'>
			<div id="staff-list-scroller">
				<ul id="staff-list-ul" >
				<volist name='store' id='vv'>
						<li class='staff-list-li'>
							<div class='staff-list-info'>
							   <div>
								<!--<span class='staff-name'></span>-->
								<span class='staff-phone'>{pigcms{$vv.name}</span>
								<span class='staff-type' style='color:#f26f27'><if condition="$vv['status'] eq 1"> 正常<elseif  condition="$vv['status'] eq 4"/>禁用<else/>审核中</if></span></div>
								<div style="padding:5px;" id="managelist">
								<!--div><a href="{pigcms{:U('Index/tablelist',array('store_id'=>$vv['store_id']))}" style="margin:5px 0;">桌台管理</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="See_ErWM({pigcms{$vv['store_id']})">店铺二维码</a>
								</div-->
								<div style="margin-top:7px;"><a href="{pigcms{:U('Index/sortlist',array('store_id'=>$vv['store_id']))}">分类管理</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{pigcms{:U('Index/storemeal',array('store_id'=>$vv['store_id']))}">{pigcms{$config.meal_alias_name}信息管理</a></div>
								</div>
							</div>
							 <div class='staff-list-operation'>
								<a href="{pigcms{:U('Index/store',array('store_id'=>$vv['store_id']))}" class='staff-operation-remove' >
									<i class='iconfont icon-edit'></i>
									<span>编辑店铺</span>
								</a>
								<br/><br/>
								
							   <!--a href="{pigcms{:U('Index/table_add',array('store_id'=>$vv['store_id']))}" class='staff-operation-remove' >
									<i class='iconfont icon-add'></i>
									<span>添加桌台</span>
								</a>
								<br/><br/-->
								
							   <a href="{pigcms{:U('Index/sort_add',array('store_id'=>$vv['store_id']))}" class='staff-operation-remove' >
									<i class='iconfont icon-add'></i>
									<span>添加分类</span>
								</a>
							</div>
							<div class='clearfix'></div>
						</li>
				</volist>
				</ul>
			</div>
		</div>
	
	</div>
	
</body>
<script src="./tpl/Wap/default/static/layer/layer.m.js"></script>
<script type="text/javascript">
var ewmUrl="{pigcms{:U('Index/erwm')}";
function See_ErWM(id){
var w=$('body').width();
imgw=w-80;
$.post(ewmUrl,{sid:id,type:'meal'},function(ret){
	  if(!ret.error_code && ret.qrcode){
		 layer.open({title:['店铺二维码：','background-color:#02B099;color:#fff;'],content:'<div ><img src="'+ret.qrcode+'" style="width:'+imgw+'px"></div>',btn: ['确定'],end:function(){}});
		 w=w-50;
		 $('.layermchild').css('max-width',w);
	  }else{
		 layer.open({title:['错误提示：','background-color:#02B099;color:#fff;'],content:'二维码获取失败！',btn: ['确定'],end:function(){}});
	  }
   },'JSON');
 }
</script>
	<include file="Public:footer"/>
</html>
