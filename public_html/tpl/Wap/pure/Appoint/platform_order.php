<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0" />
	<meta name="format-detection"content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>预约</title>
	<link href="{pigcms{$static_path}css/appoint_form.css?07" rel="stylesheet"/>
    <script type="text/javascript">
    	var ajaxWorkUrl="{pigcms{:U('ajaxWorker')}";
		var ajaxWorkerTimeUrl="{pigcms{:U('ajaxWorkerTime')}";
		var ajaxAppointTimeUrl="{pigcms{:U('ajaxAppointTime')}";
		var appoint_id={pigcms{$_GET['appoint_id']}+0;
		var merchant_workers_id={pigcms{$_GET['merchant_workers_id']}+0;
		var is_store = "{pigcms{$now_group['is_store']}";
		var jqueryUrl="{pigcms{:C('JQUERY_FILE')}";
		var layUrl="{pigcms{$static_path}layer/layer.m.js";
		var appointFormLoadUrl="{pigcms{$static_path}js/appoint_form_load.js";
    </script>
</head>
<body>
	<section id="main">
		<div class="yxc-body-bg index-section">
			<form action="__SELF__" method="post" id="main_form">
				<if condition="count($appoint_product) gt 0">
					<div class="yxc-space"></div>
					<div class="tit-select-service">选择服务</div>
					<input type="hidden" name="service_type" id="service_type" value="{pigcms{$defaultAppointProduct['id']}"/>
					<div class="comm-service">
						<span><em>¥</em><span>{pigcms{$defaultAppointProduct['price']}</span></span>
						<div class="con-service">
							<div class="con-service-inner" data-role="packageDescription">{pigcms{$defaultAppointProduct['name']}：{pigcms{$defaultAppointProduct['content']}&nbsp;&nbsp;<em style="color:red">【用时&nbsp;:&nbsp;{pigcms{$defaultAppointProduct['use_time']}分钟】</em></div>
						</div>
					</div>
				</if>
				<div class="yxc-space space-six border-t-no"></div>
                
                <if condition='$now_group["is_store"]'>
				<ul class="yxc-attr-list">
					<li data-role="chooseStore">
						<i class="icon-store"></i>
						<p class="cover select">
							<select name="store_id" id="store_id" class="ipt-attr">
								<option value="">选择预约店铺</option>
								<volist name="now_group['store_list']" id="vo">
									<option value="{pigcms{$vo.store_id}" <if condition="$vo['store_id'] eq $chk_worker_info['merchant_store_id']">selected</if>>{pigcms{$vo.name} <if condition='$vo["range_txt"]'>[距您约{pigcms{$vo.range_txt}]</if></option>
								</volist>
							</select>
						</p>
					</li>
				</ul>
				<div class="yxc-space"></div>
                </if>
                
                <if condition="$chk_worker_info">
                <ul class="yxc-attr-list worker-list"><li data-role="chooseStore"><i class="icon-store"></i><p class="cover select"><select onChange="getWorkerTime($(this))" name="merchant_workers_id" id="merchant_workers_id" class="ipt-attr" style="color: black;"><option value="">选择技师</option>
                <volist name="chk_worker_info['merchant_workers_list']" id="vo">
                	<option value="{pigcms{$key}" <if condition="$_GET['merchant_workers_id'] eq $key">selected="selected"</if>>{pigcms{$vo}</option>
                </volist>
                </select></p></li></ul>
                </if>
				<if condition="$now_group['payment_status'] eq 1">
					<div class="yxc-paymentMoney"><img src="{pigcms{$static_path}images/icon_deposit.png" style="width:15px;margin-right:5px;"/><span style="">预约定金</span><img src="{pigcms{$static_path}images/icon_rmb.png" style="width:8px;margin-left:15px;"/>&nbsp;<span style="font-size:20px;color:#ff8a00;margin-bottom:0;">{pigcms{$now_group.payment_money}</span></div>
					<div class="yxc-space"></div>
				</if>
				<if condition="$formData || $now_group['appoint_type']">
					<ul class="yxc-attr-list">	
						<if condition="$now_group['appoint_type']">
							<li>
								<i class="icon-position"></i>
								<input type="hidden" name="custom_field[0][name]" value="服务位置"/>
								<input type="hidden" name="custom_field[0][type]" value="2"/>
								<input type="hidden" name="custom_field[0][long]" data-type="long"/>
								<input type="hidden" name="custom_field[0][lat]" data-type="lat"/>
								<input type="hidden" name="custom_field[0][address]" data-type="address"/>
								<p class="cover">
									<input data-role="position" class="ipt-attr" type="text" name="custom_field[0][value]" placeholder="请选择服务位置" readonly data-required="required"/>
								</p>
								<p class="cover">
									<input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[0][value-desc]" placeholder="请标注地图后填写详细地址" data-required="required"/>
								</p>
							</li>
						</if>
						<volist name="formData" id="vo" key="key">
							<li>
								<switch name="vo['type']">
									<case value="0">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="text" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="1">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><textarea class="ipt-attr" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="textarea" <if condition="$vo['iswrite']">data-required="required"</if>></textarea></p>
									</case>
									<case value="2">
										<i class="icon-position"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][long]" data-type="long"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][lat]" data-type="lat"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][address]" data-type="address"/>
										<p class="cover">
											<input data-role="position" class="ipt-attr" type="text" name="custom_field[{pigcms{$key}][value]" placeholder="请标注{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" readonly="readonly" <if condition="$vo['iswrite']">data-required="required"</if>/>
										</p>
										<p class="cover">
											<input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[0][value-desc]" placeholder="请标注地图后填写详细地址" data-required="required"/>
										</p>
									</case>
									<case value="3">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover select">
											<select name="custom_field[{pigcms{$key}][value]" class="ipt-attr" data-role="select"  placeholder="请选择{pigcms{$vo.name}" <if condition="$vo['iswrite']">data-required="required"</if>>
												<option value="">请选择{pigcms{$vo.name}</option>
												<volist name="vo['use_field']" id="voo">
													<option value="{pigcms{$voo}">{pigcms{$voo}</option>
												</volist>
											</select>
										</p>
									</case>
									<case value="4">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="number" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="5">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="email" name="custom_field[{pigcms{$key}][value]" placeholder="请输入正确的{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="email" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="6">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="date" name="custom_field[{pigcms{$key}][value]" id="input_6" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="date" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="7">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="time" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="time" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="8">
										<i class="icon-phone"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="phone" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
									<case value="9">
										<i class="icon-txt"></i>
										<input type="hidden" name="custom_field[{pigcms{$key}][name]" value="{pigcms{$vo.name}"/>
										<input type="hidden" name="custom_field[{pigcms{$key}][type]" value="{pigcms{$vo.type}"/>
										<p class="cover"><input class="ipt-attr" type="datetime" name="custom_field[{pigcms{$key}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="datetime" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
									</case>
								</switch>
							</li>
						</volist>
					</ul>
					<div class="yxc-space space-six border-t-no"></div>
				</if>
				<em class="tip-add-money">
					<div class="foot-index">
						<a class="bt-sub-order" data-role="submit">
							立即下单
						</a>
					</div>
				</em>
			</form>
		</div>
	</section>
	<section id="service-type" style="display:none;">
		<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
			<header class="yxc-brand">
				<a class="arrow-wrapper" data-role="cancel">
					<i class="bt-brand-back"></i>
				</a>
				<span>选择服务</span>
			</header>
			<ul class="yxc-service-list yxc-package boder-top service-list">
				<volist name="appoint_product" id="vo">
					<li <if condition="$vo['id'] eq $defaultAppointProduct['id']">class="active"</if> data-id="{pigcms{$vo['id']}">
						<label class="pay-type" for="pay-type-{pigcms{$vo['id']}">
							<span class="service-price"><em>¥</em><span data-role="payAmount">{pigcms{$vo['price']}</span></span>
							<div class="service-intro">
							  <h3 data-role="title">{pigcms{$vo['name']}</h3>
							  <span data-role="content">{pigcms{$vo['content']}&nbsp;&nbsp;<em style="color:red">【用时&nbsp;:&nbsp;{pigcms{$vo['use_time']}分钟】</em></span>
							</div>
							<input name="pay-type" id="pay-type-{pigcms{$vo['id']}" type="radio" value="" style="opacity:0;position:absolute;top:0;" <if condition="$vo['id'] eq $defaultAppointProduct['id']">checked="checked"</if>/>
							<span class="bt-interior"></span>
						</label>
					</li>
				</volist>
			</ul>
		</div>
	</section>
	<section id="service-position" style="display:none;">
		<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
			<header class="yxc-brand">
				<a class="arrow-wrapper" data-role="cancel">
					<i class="bt-brand-back"></i>
				</a>
				<span>选择位置</span>
			</header>
			<div class="selectInput">
				<input type="text" placeholder="直接输入定位您的地址" id="se-input-wd" autocomplete="off"/>
			</div>
			<div class="mapBox">
				<div id="allmap"></div>
				<div class="dot"></div>
			</div>
			<div class="mapaddress">
				<ul id="addressShow"></ul>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script>
	<script src="{pigcms{$static_path}/js/common_wap.js"></script>
    <link href="{pigcms{$static_path}scenic/css/mobiscroll.2.13.2.css" rel="stylesheet"/>
    <script src="{pigcms{$static_path}scenic/js/mobiscroll.2.13.2.js"></script>
	<script type="text/javascript">
	var user_long = '0',user_lat  = '0',user_city='{pigcms{$city_name}';
	</script>
	<script>
	
// if(user_long == '0' || user_lat == '0'){
	//检查浏览器是否支持地理位置获取 
	// if (navigator.geolocation){ 
		//若支持地理位置获取,成功调用showPosition(),失败调用showError 
		// var config = {enableHighAccuracy:true}; 
		// navigator.geolocation.getCurrentPosition(showPosition,showError,config);
	// }else{
		// alert("定位失败,用户浏览器不支持或已禁用位置获取权限"); 
	// }
// }

/** 
* 获取地址位置成功 
*/ 
function showPosition(position){
	//获得经度纬度 
	user_lat  = position.coords.latitude;
	user_long = position.coords.longitude;

	$.getJSON('http://api.map.baidu.com/geoconv/v1/?coords='+user_long+','+user_lat+'&ak=4c1bb2055e24296bbaef36574877b4e2&from=1&to=5&callback=funName&jsoncallback=?');
}



function funName(result){
				user_lat  = result.result[0].y;
				user_long = result.result[0].x;
        }

/** 
* 获取地址位置失败[暂不处理] 

function showError(error){
	$('#near_dom').remove();
	switch (error.code){
		case error.PERMISSION_DENIED: 
			$('.mapBox').remove();
			alert("定位失败,用户拒绝请求地理定位"); 
			break; 
		case error.POSITION_UNAVAILABLE: 
			$('.mapBox').remove();
			alert("定位失败,位置信息不可用"); 
			break; 
		case error.TIMEOUT: 
			$('.mapBox').remove();
			alert("定位失败,请求获取用户位置超时"); 
			break; 
		case error.UNKNOWN_ERROR:
			$('.mapBox').remove();
			alert("定位失败,定位系统失效");
			break; 
	} 
}*/ 
	</script>
    
    <!--script type="text/javascript">
		<if condition="$long_lat">
			var user_long={pigcms{$long_lat.long},user_lat={pigcms{$long_lat.lat},user_city='{pigcms{$city_name}';
		<else/>
			var user_long=0,user_city='{pigcms{$city_name}';
		</if>
	</script-->
	<script type="text/javascript" src="{pigcms{$static_path}js/appoint_form.js?09"></script>
</body>
</html>