<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_item.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_staff.css">
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
<style>
	.pigcms-container{background: none;padding: 0;}
	.form_tips{color:red;}
	.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
	.pigcms-textarea{margin-bottom:10px;}
</style>
<!--<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>-->
<body>
		<header class="pigcms-header mm-slideout">
			<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
			<p id="pigcms-header-title">添加{pigcms{$config.meal_alias_name}商品</p>
		</header>
	<div class="container container-fill" style='padding-top:50px'>

		<form class="pigcms-form" method="post" action="{pigcms{:U('Index/meal_add')}">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品名称：</p>
				<input class="pigcms-input-block"  name="name" id="name" type="text" value="{pigcms{$now_meal.name}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品单位：&nbsp;&nbsp;&nbsp;<span class="form_tips">必填。如个、斤、份</span></p>
				<input class="pigcms-input-block"  name="unit" id="unit" type="text" value="{pigcms{$now_meal.unit}"/>
			</div>

			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品标签：&nbsp;&nbsp;&nbsp;<span class="form_tips">选填。如特价、促销、招牌！多个以空格分隔，包括空格最长10位</span></p>
				<input class="pigcms-input-block" name="label" id="label" type="text" value="{pigcms{$now_meal.label}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品价格：&nbsp;&nbsp;&nbsp;<span class="form_tips">必填。单位为元，最多支持两位小数，下同</span></p>
				<input class="pigcms-input-block" size="20" name="price" id="price" type="text" value="{pigcms{$now_meal.price}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品原价：</p>
				<input class="pigcms-input-block" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$now_meal.old_price}"/>
			</div>

			<div class="pigcms-container">
				<p class='pigcms-form-title'>会员特定价：&nbsp;&nbsp;&nbsp;<span class="form_tips">如果设定此值，则所有等级的会员都按此价执行</span></p>
				<input class="pigcms-input-block" size="20" name="vip_price" id="vip_price" type="text" value="{pigcms{$now_meal.vip_price}"/>
			</div>

			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品排序：&nbsp;&nbsp;&nbsp;<span class="form_tips">默认添加顺序排序。数值越大，排序越前</span></p>
				<input class="pigcms-input-block" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_meal.sort|default='0'}"/>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品状态：</p>
					<select name="status" id="Food_status" class="pigcms-input-block">
						<option value="1" <if condition="!empty($now_meal) AND $now_meal['status'] eq 1" > selected="selected" </if>>正常</option>
						<option value="0" <if condition="!empty($now_meal) AND $now_meal['status'] eq 0" > selected="selected" </if>>停售</option>
					</select>
			</div>
			 <if condition="!empty($stores) AND empty($now_meal) AND !($mealid gt 0)">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>选择添加到的店铺：</p>
				  <select name="store_id" class="pigcms-input-block" onchange="GetMealSort(this.value)">
				     <volist name="stores" id="vo">
				     <option value="{pigcms{$vo['store_id']}">{pigcms{$vo['name']}</option>
					 </volist>
				  </select>
			</div>
			<elseif condition="!empty($now_meal) AND $mealid gt 0" />
			 
			<else />
			  <p class="pigcms-form-title" style="color:red;">您还没有可选店铺，<a href="{pigcms{:U('Index/store_list')}" style="color: green;">请点击这里去添加店铺吧</a></p>
			</if>
			<if condition="!empty($meal_sortJSON)">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>选择添加到的分类：</p>
				 <div id="meal_sort_p">
				  <select name="sort_id" class="pigcms-input-block">

				     <option value=""></option>

				  </select>
				  </div>
			</div>
			<else />
				<p class="pigcms-form-title" style="color:red;">您还没有可选分类，<a href="{pigcms{:U('Index/store_list')}" style="color: green;">请点击这里去添加分类吧</a></p>
			</if>
			<div class="pigcms-container">
				<div class="top-img-container">
					<div class="up-load-img" onclick="upLoadImg(this)">
						<i class="iconfont icon-upload"></i>
							<p>商品图片</p>
							<div class="clearfix"></div>
							<input type="hidden" name="pic_url" value="{pigcms{$now_meal['image']}">
							<if condition="isset($now_meal['piclist']) AND !empty($now_meal['piclist'])">
							<img src="{pigcms{$now_meal['piclist']}">
							</if>
					</div>
				</div>			
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品描述：</p>
				<textarea class="pigcms-textarea" name='des' id="des" rows=5 placeholder="商品的简短介绍，建议为300字以下">{pigcms{$now_meal.des}</textarea>
			</div>

			<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit" value="添加">添加</button>
			<input type="hidden" name="action" value="edit_item" />
			<input type="hidden" name="mealid" value="{pigcms{$mealid}" />
			<if condition="$mealid gt 0">
			<input type="hidden" name="store_id" value="{pigcms{$store_id}" />
			</if>
		</form>
		
	
		</div>

	
	
</body>
		<script type="text/javascript">
		var picarr = [];
			var attachurl = "{pigcms{$site_URl}",
			    localIds,
				pic_detail = [],
				Img_Classify = 'meal',
				upload_url = "{pigcms{:U('Index/img_uplode')}";
			//$("input[name='pic_detail']").val(pic_detail);
	var sortid=0;
	<php>if(!empty($now_meal) && ($now_meal['sort_id']>0)){
	  echo 'sortid='.$now_meal['sort_id'].';';
	}</php>
	sortid=parseInt(sortid);
    var meal_sortArr={pigcms{$meal_sortJSON};
	function GetMealSort(store_id){
		var	sortHTML='<select name="sort_id" class="pigcms-input-block">';
		if(typeof(meal_sortArr[store_id]) != 'undefined'){
		   $.each(meal_sortArr[store_id],function(idx,vv){
			   if(sortid>0 && sortid==vv.sort_id){
			      sortHTML +='<option value="'+vv.sort_id+'" selected="selected">'+vv.sort_name+'</option>';
			   }else{
			      sortHTML +='<option value="'+vv.sort_id+'">'+vv.sort_name+'</option>';
			   }
		   });
		   sortHTML +='</select>';
		}else{
			sortHTML=sortHTML+'<option value="">无分类</option></select>';
		}
		$('#meal_sort_p').html(sortHTML);
	}

	GetMealSort({pigcms{$stores['0']['store_id']});

	$('#add_form').submit(function(){
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('Index/meal_add')}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Index/mpro')}";
			}else{
				alert(result.info);
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});
	
		</script>
<script type="text/javascript" src="{pigcms{$static_path}js/shop_item_edit.js?ver=<php>echo time();</php>"></script>
	<include file="Public:footer"/>
</html>