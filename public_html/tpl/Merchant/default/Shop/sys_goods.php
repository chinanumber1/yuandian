<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信公众平台源码,微信机器人源码,微信自动回复源码 PigCms多用户微信营销系统</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_2_common.css" />
<link href="{pigcms{$static_path}css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cymain.css" />
<script src="{pigcms{$static_path}js/common.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<style>
body {
	line-height: 180%;
}

ul.modules li {
	padding: 4px 10px;
	margin: 4px;
	background: #efefef;
	float: left;
	width: 27%;
}

ul.modules li div.mleft {
	float: left;
	width: 40%
}

ul.modules li div.mright {
	float: right;
	width: 55%;
	text-align: right;
}



.button {
    margin-left: 15px;
    padding: 6px 8px;
    cursor: pointer;
    display: inline-block;
    text-align: center;
    line-height: 1;
    letter-spacing: 2px;
    font-family: Tahoma, Arial/9!important;
    width: auto;
    overflow: visible;
    color: #333;
    border: solid 1px #999;
    border-radius: 5px;
    background: #DDD;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
    background: linear-gradient(top, #FFF, #DDD);
    background: -moz-linear-gradient(top, #FFF, #DDD);
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
    /* text-shadow: 0px 1px 1px rgba(255, 255, 255, 1); */
    box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
    -moz-transition: -moz-box-shadow linear .2s;
    -webkit-transition: -webkit-box-shadow linear .2s;
    transition: box-shadow linear .2s;
}
.input-text {
    border: 1px solid #A7A6AA;
    line-height: 18px;
    height: 18px;
    margin: 0 5px 0 0;
    padding: 2px 0 2px 5px;
    border: 1px solid #d0d0d0;
    font-family: Verdana, Geneva, sans-serif,"微软雅黑";
    font-size: 12px;
    width: 250px;
}
input, select, textarea, .textarea_style {
    border: 1px solid #dcdcdc;
    vertical-align: middle;
}
</style>
</head>
<body style="background: #fff; padding: 20px 20px;">
		<table class="search_table" width="100%">
			<tr>
				<td>
					<form action="{pigcms{:U('Shop/sys_goods')}" method="get">
						<input type="hidden" name="c" value="Shop"/>
						<input type="hidden" name="a" value="sys_goods"/>
						搜索商品名称: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" placeholder="为空时查到对应分类的所有商品"/>
						<select name="sort_id">
							<option value="0" <if condition="$_GET['sort_id'] eq 0">selected="selected"</if>>全部分类</option>
							<volist name="sort_list" id="sort">
							<option value="{pigcms{$sort['sort_id']}" <if condition="$_GET['sort_id'] eq $sort['sort_id']">selected="selected"</if>>{pigcms{$sort['name']}</option>
							</volist>
						</select>
						<input type="submit" value="查询" class="button"/>　
					</form>
				</td>
			</tr>
		</table>
	<table class="ListProduct" width="100%">
		<thead>
			<tr>
				<th>商品编号</th>
				<th>商品条形码</th>
				<th>商品图片</th>
				<th>商品名称</th>
				<th>商品单位</th>
				<th>商品进价(元)</th>
				<th>零售价(元)</th>
				<th>所属分类</th>
				<th>操作 </th>
			</tr>
		</thead>
		<tbody>
            <if condition="$goods_list">
			<volist name="goods_list" id="vo">
				<tr>
					<td>{pigcms{$vo.goods_id}</td>
					<td>{pigcms{$vo.number}</td>
					<td><img src="{pigcms{$vo.image}" width="50" height="50" class="view_msg"></td>
					<td>{pigcms{$vo.name}</td>
					<td>{pigcms{$vo.unit}</td>
					<td>{pigcms{$vo.cost_price|floatval}</td>
					<td>{pigcms{$vo.price|floatval}</td>
					<td>{pigcms{$vo.sort_name}</td>
					<td class="textcenter">
					<a href="javascript:void(0);" onclick="returnHomepage('{pigcms{$vo.goods_id}')">选中</a>
					</td>
				</tr>
			</volist>
            <else />
            <tr><td colspan="9" style="text-align: center">暂未搜索到您要的商品</td></tr>
            </if>
		</tbody>
	</table>
	<div class="footactions" style="padding-left: 10px">
		<div class="pages">{pigcms{$page}</div>
	</div>
<script>
var fun = art.dialog.data('fun');
// 返回数据到主页面
function returnHomepage(val){
	fun(val);
	setTimeout("art.dialog.close()", 100);
}

//预览大图
$('.view_msg').click(function(){
	window.top.art.dialog({
		padding: 0,
		title: '大图',
		content: '<img src="'+$(this).attr('src')+'" style="width:600px;height:400px;" />',
		lock: true
	});
});
</script>
</body>
</html>