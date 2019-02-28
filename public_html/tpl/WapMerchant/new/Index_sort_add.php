<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_item.css">
<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_staff.css">
<style>
	.pigcms-container{background: none;padding: 0;}
	.form_tips{color:red;}
	.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
	.pigcms-textarea{margin-bottom:10px;}
	.submittips{height: 60px;
	line-height: 70px;
	font-size: 22px;
	}
</style>
<body>
		<header class="pigcms-header mm-slideout">
			<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
			<p id="pigcms-header-title">添加菜品分类</p>
		</header>
	<div class="container container-fill" style='padding-top:50px'>

		<form class="pigcms-form" method="post" enctype="multipart/form-data" class="form-horizontal" method="post"  action="{pigcms{:U('Index/sort_add',array('store_id'=>$now_store['store_id'],'stid'=>$stid))}">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>分类名称：</p>
				<input class="pigcms-input-block" size="20" name="sort_name" id="sort_name" type="text" value="{pigcms{$now_sort.sort_name}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>店铺排序：&nbsp;&nbsp;&nbsp;<span class="form_tips">默认添加顺序排序！数值越大，排序越前</span></p>
				<input class="pigcms-input-block" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_sort.sort|default='0'}"/>
				
			</div>
		   <div class="pigcms-container">
				<p class='pigcms-form-title'>是否开启只星期几显示：</p>
				<select name="is_weekshow" id="is_weekshow" class="pigcms-input-block">
					<option value="0" <if condition="$now_sort['is_weekshow'] eq 0">selected="selected"</if>>关闭</option>
					<option value="1" <if condition="$now_sort['is_weekshow'] eq 1">selected="selected"</if>>开启</option>
				</select>
			</div>
			
			<div class="pigcms-container">
			<p class="pigcms-form-title">星期几显示</p>
			<div class="radio" style="margin-top:5px;">
				
					<label><input type="checkbox" value="1" name="week[]" <if condition="in_array('1',$now_sort['week'])">checked="checked"</if>/>星期一</label>&nbsp;&nbsp;
				
			
					<label><input type="checkbox" value="2" name="week[]" <if condition="in_array('2',$now_sort['week'])">checked="checked"</if>/>星期二</label>&nbsp;&nbsp;
			
				
					<label><input type="checkbox" value="3" name="week[]" <if condition="in_array('3',$now_sort['week'])">checked="checked"</if>/>星期三</label>&nbsp;&nbsp;

					<label><input type="checkbox" value="4" name="week[]" <if condition="in_array('4',$now_sort['week'])">checked="checked"</if>/>星期四</label>&nbsp;&nbsp;

					<label><input type="checkbox" value="5" name="week[]" <if condition="in_array('5',$now_sort['week'])">checked="checked"</if>/>星期五</label>&nbsp;&nbsp;

					<label><input type="checkbox" value="6" name="week[]" <if condition="in_array('6',$now_sort['week'])">checked="checked"</if>/>星期六</label>&nbsp;&nbsp;

					<label><input type="checkbox" value="0" name="week[]" <if condition="in_array('0',$now_sort['week'])">checked="checked"</if>/>星期日</label>&nbsp;&nbsp;
				
			</div>
		</div>
		   <if condition="$ok_tips">
				<div class="pigcms-container">
					<p class='pigcms-form-title submittips'><span style="color:blue;">{pigcms{$ok_tips}</span></p>
				</div>
			</if>
			<if condition="$error_tips">
				<div class="pigcms-container">
					<p class='pigcms-form-title submittips'><span style="color:red;">{pigcms{$error_tips}</span></p>
				</div>
			</if>

			<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit" value="添加">添加</button>
			<input  name="store_id"  type="hidden" value="{pigcms{$now_store['store_id']}"/>
			<input  name="stid"  type="hidden" value="{pigcms{$stid}"/>			
		</form>
		</div>

</body>
	<include file="Public:footer"/>
</html>