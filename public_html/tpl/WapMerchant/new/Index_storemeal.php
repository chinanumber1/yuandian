<include file="Public:top"/>
<body>
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop.css">
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_info.css">
	<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=Y7IBZ-W6WWJ-PP6FF-FPFGD-ES3JF-YNFPN"></script>
	<style>
		.top-img-container{
			padding-bottom: 0;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		.top-img-container .pigcms-form-title{
			float: left;
			width: auto;
			line-height: 60px;
		}
		.up-load-img{
			float:right;
			width: 60px;
			height: 60px;
			margin:0 4% 0 0;
			text-align: center;
		}
		.up-load-img img{
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#big-pic{
			width: 110px;
			height: 50px;
			border-radius: 0!important;
		}
		#big-pic img{border-radius: 0!important;}
		.form_tips{color:red;}
		.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
		#choose_area{margin-right: 30px;width: 35%;padding-left: 20px;}
		#choose_circle{width: 45%;padding-left: 20px;}
		#perioddeliveryfeebox{margin:10px;height:auto;}
		.perioddeliveryfeeitem{margin:10px 0px;}
		.pigcms-input-block{display:inline-block;width: 75%;}
		.pigcms-container{margin-bottom: 10px;}
		.pigcms-textarea{width: 80%;margin-bottom: 20px;}
	</style>
	<header class="pigcms-header mm-slideout">
	   <a  href="{pigcms{:U('Index/store_list')}" id="pigcms-header-left">返 回</a>
	   <p id="pigcms-header-title">{pigcms{$config.meal_alias_name}信息管理</p>
	</header>
	<div class="container container-fill" >
			<!--左侧菜单-->
			<include file="Public:leftMenu"/>
			<!--左侧菜单结束-->
	<form class="pigcms-form" method="post" action="{pigcms{:U('Index/storemeal',array('store_id'=>$now_store['store_id']))}">
		<div class="pigcms-container">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺公告</p>
			<textarea class="pigcms-textarea" rows="4" name="store_notice" id="Config_notice">{pigcms{$store_meal.store_notice}</textarea>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>预订金</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="deposit" id="Config_deposit" type="text" value="{pigcms{$store_meal.deposit}" />元
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>人均消费</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="mean_money" id="Config_mean_money" type="text" value="{pigcms{$store_meal.mean_money}" />元<span class="required">*</span>
		</div>
		<if condition="$now_store['store_type'] neq 1">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>起送价格</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="basic_price" id="Config_basicprice" type="text" value="{pigcms{$store_meal.basic_price}" />元<span class="required">*</span>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>外送费</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="delivery_fee" id="Config_delivery_fee" type="text" value="{pigcms{$store_meal.delivery_fee}"/>元<span class="required">*</span>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>送达时间</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="send_time" id="Config_send_time" type="text" value="{pigcms{$store_meal.send_time}"/>分钟
		</div>
		<div class="pigcms-container">
			<div class="radio">
			 <label>
				&nbsp;<input  name="delivery_fee_valid" id="Config_delivery_fee_valid" value="1" type="checkbox" <if condition="$store_meal['delivery_fee_valid']">checked="checked"</if>/>
				<span class="lbl" style="z-index: 1"> 不足起送价格收取外送费照样送</span>
			 </label>
			</div>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>达到起送价格</p>
			<div class="radio">
				<label>
					<input name="reach_delivery_fee_type" value="0" type="radio" class="" <if condition="$store_meal['reach_delivery_fee_type'] eq 0">checked="checked"</if>/>
					<span class="lbl" style="z-index: 1">免外送费</span>
				</label>
			</div>
			<div class="radio">
				<label>
					<input name="reach_delivery_fee_type" value="1" type="radio" class="" <if condition="$store_meal['reach_delivery_fee_type'] eq 1">checked="checked"</if>/>
					<span class="lbl" style="z-index: 1">照样收取外送费</span>
				</label>
			</div>
			<div class="radio">
				<label>
					<input name="reach_delivery_fee_type" value="2" type="radio" class="" <if condition="$store_meal['reach_delivery_fee_type'] eq 2">checked="checked"</if>/>
					<span class="lbl" style="z-index: 1">达到</span>
					&nbsp;&nbsp;&nbsp;<input size="10" maxlength="10" name="no_delivery_fee_value" id="Config_no_delivery_fee_value" type="text" value="{pigcms{$store_meal.no_delivery_fee_value}" style="border: 1px solid #eee;padding-left:10px;"/><span class="lbl" style="z-index: 1"> 元免外送费</span>
				</label>
			</div>											
		</div>
		
		<div class="pigcms-container">
			<p class='pigcms-form-title'>服务距离</p>
			<input class="pigcms-input-block" size="10" maxlength="10" name="delivery_radius" id="Config_delivery_radius" type="text" value="{pigcms{$store_meal.delivery_radius}"/>公里
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>配送区域</p>
			<textarea class="pigcms-textarea" rows="4" name="delivery_area" id="Config_area">{pigcms{$store_meal.delivery_area}</textarea>
		</div>
		</if>

		<p class='pigcms-form-title'>选择分类</p>
		<volist name="category_list" id="vo">
		<div class="pigcms-container">
			<div class="radio">
				<label>
					<span class="lbl" style="color: red">{pigcms{$vo.cat_name}：</span>
				</label>
				<volist name="vo['list']" id="child">
					<label>
						<input  type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
						<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
					</label>
				</volist>
			</div>
		</div>
	</volist>

        <p class='pigcms-form-title'>促销活动</p>
		<div class="pigcms-container">
			<p class='pigcms-form-title' style="color:red;">赠和送都是商家和消费者的线下互动，如商家赠送一些小礼品呀，购物券之类的。满、减(消费超过多少元立减多少元)，如果商家没有填写就没有这个优惠！</p>
		</div>
		  
	<div class="pigcms-container">
		<p class='pigcms-form-title'>赠品</p>
		<textarea class="pigcms-textarea" rows="4" name="zeng" id="Config_zeng">{pigcms{$store_meal.zeng}</textarea>
	</div>
		<div class="pigcms-container">
			<label class='pigcms-form-title'>满（金额）</label>
			<input  size="10" maxlength="10" name="full_money" id="Config_mean_full_money" type="text" value="{pigcms{$store_meal.full_money}" style="padding-left:10px;"/> &nbsp;元
			<br/><br/>
			<label class='pigcms-form-title'>减（金额）</label>
			<input size="10" maxlength="10" name="minus_money" id="Config_mean_minus_money" type="text" value="{pigcms{$store_meal.minus_money}" style="padding-left:10px;"/> &nbsp;元
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>送品</p>
			<textarea class="pigcms-textarea" rows="4" name="song" id="Config_song">{pigcms{$store_meal.song}</textarea>
		</div>
		<div style="clear:both;"></div>
		<if condition="!empty($levelarr)">
		<div id="levelcoupon" style="border:1px solid #c5d0dc;padding:0px 0px 10px 10px;margin-bottom:10px;">
		<p class='pigcms-form-title'>会员优惠</p>
			<div class="pigcms-container">
				<p class="pigcms-form-title" style="color:red;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</p>
			</div>
			<volist name="levelarr" id="vv">
			  <div class="pigcms-container">
				<input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
				<input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
				<p class="pigcms-form-title">{pigcms{$vv['lname']}：优惠类型：&nbsp;</p>
				<select name="leveloff[{pigcms{$vv['level']}][type]" style="margin-left: 15px;width: 40%;">
					<option value="0">无优惠</option>
					<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
					<!--<option value="2">立减</option>-->
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" style="width: 30%;padding-left:7px;"/>
			</div>
			</volist>
		   </div>
		</if>
		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit">保存</button>

	</form>
 </div>
	
</body>
	<include file="Public:footer"/>
</html>
