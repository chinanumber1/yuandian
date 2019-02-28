<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_item.css">
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
<style>
.pigcms-container {
  padding: 12px 0 0;
   margin-bottom: 10px;
}
.search-container {padding: 3px;}
</style>
<body>
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		  </a>
	      <p id="pigcms-header-title">桌台管理</p>
		  <a  href="{pigcms{:U('Index/store_list')}" id="pigcms-header-right">返回店铺列表</a>
		</header>
	<div class="container container-fill">
		<!--左侧菜单-->
		<include file="Public:leftMenu"/>
		<!--左侧菜单结束-->

	<div class="item-list-wrap">
		<div id="item-list-wrapper" class="pigcms-main" style="padding-top: 10px;">
			<div id="item-list-scroller">
			<ul id="item-list-ul" style="margin-bottom: 70px;">
					<div id="item-list-div">
					<if  condition="!empty($list)">
					<volist name="list" id="tbv">
						<li class="item-list-container">
						<div class="item-detail" style="width: 95%;">
						<p class="item-name">餐台名称：{pigcms{$tbv['name']}</p>
						<p class="item-price-sell">
						<span class="item-price">容纳人数：<strong>{pigcms{$tbv['num']}</strong></span>
						&nbsp;&nbsp;&nbsp;<span class="statusstr">状态：<strong><if  condition="$tbv['status'] gt 0">使用中<else />空闲</if></strong></span></p>
						
						<div class="item-operation-container">
						<!--<a class="item-operation" data-itemid="1" data-storeid="2" ><i class="iconfont icon-iconfontdown2" style="color:#fc9a79"></i><span>桌台预定详情</span></a>--->
						<a class="item-operation" data-itemid="{pigcms{$tbv['pigcms_id']}" onclick="item_delete_Tb(this)"><i class="iconfont icon-shanchu" style="color:#cd0009"></i><span>删除</span></a>
						<a href="{pigcms{:U('Index/table_add',array('store_id'=>$tbv['store_id'],'tid'=>$tbv['pigcms_id']))}" class="item-operation"><i class="iconfont icon-edit" style="color:#449fc6"></i><span>编辑</span></a></div>
						<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
						</li>
					 </volist>
					</if>
					</div>
				</ul>
			</div>
		</div>

		<div class="item-list-footer">
			<a href="{pigcms{:U('Index/table_add',array('store_id'=>$now_store['store_id']))}" class="footer-operation">
				<i class="iconfont icon-add"></i><span>添加桌台</span>
			</a>
		</div>
	</div>
</body>

<script type="text/javascript">
	$(function(){
		$(".pigcms-main").css('height', $(window).height()-50);
	})
var store_id="{pigcms{$now_store['store_id']}";

function item_delete_Tb(obj){
   	if(confirm("确定删除吗？")){
		$this = $(obj);
		var params = {
			'item_id'	: $this.attr('data-itemid'),
			'storeid'	: store_id
		};
		$.post('/index.php?g=WapMerchant&c=Index&a=mtbdel', params, function(data) {
			if(!data.error){
			  $this.parents('.item-list-container').remove();
			}else{
			  alert('删除失败！');
			}
		},'JSON');
	}
}
</script>
	
		<include file="Public:footer"/>
</html>

