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
			<p id="pigcms-header-title"><if condition="$tid gt 0">编辑桌台<else/>添加桌台</if></p>
		</header>
	<div class="container container-fill" style='padding-top:50px'>

		<form class="pigcms-form" method="post" class="form-horizontal" method="post"  action="{pigcms{:U('Index/table_add',array('store_id'=>$now_store['store_id']))}">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>桌台名称：</p>
				<input class="pigcms-input-block" size="20" name="name" id="name" type="text" value="{pigcms{$now_table.name}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>容纳人数：</p>
				<input class="pigcms-input-block" size="20" name="num" id="num" type="text" value="{pigcms{$now_table.num}"/>
			</div>
								
			<div class="pigcms-container">
				<p class='pigcms-form-title'>桌台使用状态</p>
				<div class="radio">
					<label>
						<input name="status" value="1" type="radio" <if condition="$now_table['status'] eq 1" >checked="checked"</if>/>
						<span class="lbl" style="z-index: 1">使用中</span>
					</label>
					&nbsp;&nbsp;&nbsp;
					<label>
						<input name="status" value="0" type="radio" <if condition="$now_table['status'] eq 0">checked="checked"</if>/>
						<span class="lbl" style="z-index: 1">空闲</span>
					</label>
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

			<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit"><if condition="$tid gt 0">修改<else/>添加</if></button>
			<input  name="store_id"  type="hidden" value="{pigcms{$now_store['store_id']}"/>
			<input  name="tid"  type="hidden" value="{pigcms{$tid}"/>
		</form>
		</div>

</body>
	<include file="Public:footer"/>
</html>