<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cutlery"></i>
                <a href="{pigcms{:U('Waimai/product_category')}">{pigcms{$config.waimai_alias_name}管理</a>
            </li>
            <li>
                <a href="{pigcms{:U('Waimai/index')}">店铺管理</a>
            </li>
            <li class="active">商品列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        <button onclick="CreateCategory()" class="btn btn-success">管理商品</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>编号</th>
									<th>商品名称（悬浮查看商品标题、图片）</th>
									<th>所属店铺</th>
									<th>所属分类</th>
									<th>排序</th>
									<th>外卖状态</th>
									<th>单位</th>
									<th>销售量</th>
									<th>被推荐</th>
									<th>每日限量</th>
									<th>价格</th>
									<th>描述</th>
									<th>创建时间</th>
									<th>最后修改时间</th>
									<th class="textcenter">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$goodsList">
                                    <volist name="goodsList['goods_list']" id="vo">
                                        <tr class="<if condition="$key%2 eq 0">odd<else/>even</if>">
                                           	<td>{pigcms{$vo.goods_id}</td>
											<td><a class="group_name" data-pic="{pigcms{$vo.list_pic}" data-title="{pigcms{$vo.goods_name}" target="_blank" href="{pigcms{$config.site_url}/index.php?g=Waimai&c=Detail&goods_id={pigcms{$vo['goods_id']}">{pigcms{$vo.goods_name}</a></td>
											<td>{pigcms{$vo.store_name}</td>
											<td>{pigcms{$vo.gcat_name}</td>
											<td>{pigcms{$vo.sort}</td>
											<td>
												<if condition="$vo['status'] eq 0"><span style="color:red">关闭</span>
												<elseif condition="$vo['status'] eq 1" /><span style="color:green">开启</span>
												</if>
											</td>
											<td>{pigcms{$vo.unit}</td>
											<td>{pigcms{$vo.sell_count}</td>
											<td>{pigcms{$vo.digg_count}</td>
											<td><if condition="$vo['limit'] == '0'">不限量<else />{pigcms{$vo.limit}</if></td>
											<td>外卖价：{pigcms{$vo.price}元<br>原价：{pigcms{$vo.old_price}元<br>vip:{pigcms{$vo.vip_price}元<br>餐合费：{pigcms{$vo.tools_price}元</td>
											 
											<td>{pigcms{$vo.desc}</td>
											<td>{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>
											<td>{pigcms{$vo.last_time|date="Y-m-d H:i:s",###}</td>
											<td class="textcenter">
											  	<a href="{pigcms{:U('Waimai/goods_manage',array('goods_id'=>$vo['goods_id'],'store_id'=>$vo['store_id']))}" >编辑</a> |
											  	<a id='js-del' href="{pigcms{:U('Waimai/goods_del',array('goods_id'=>$vo['goods_id'],'store_id'=>$vo['store_id']))}" class="delete_row" >删除</a>
											 </td>
                                        </tr>
                                    </volist>
                                    <tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >列表为空！</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	function CreateCategory(){
		window.location.href = "{pigcms{:U('Waimai/goods_manage',array('store_id'=>$store_id))}";
	}
</script>
<script type="text/javascript">
$(function(){
    
    jQuery(document).on('click','#js-del',function(){
        if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
    });
    $('.group_name').hover(function(){
		var top = $(this).offset().top;
		var left = $(this).offset().left+$(this).width()+10;
		$('body').append('<div id="group_name_div" style="position:absolute;z-index:5555;background:white;top:'+top+'px;left:'+left+'px;border:1px solid #ccc;padding:10px;"><div style="margin-bottom:10px;"><b>商品标题：</b>'+$(this).data('title')+'</div><div><b>商品图片：</b><img src="'+$(this).data('pic')+'" style="width:180px;"/></div></div>');
	},function(){
		$('#group_name_div').remove();
	});	
});

</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>

<include file="Public:footer"/>
