<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">店铺管理</a>
			</li>
			<li class="active">编辑店铺</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtstore">店铺描述</a>
							</li>
							{pigcms{/***[if >=3]***/}
							<li>
								<a data-toggle="tab" href="#discount">{pigcms{$config.cash_alias_name}</a>
							</li>
							{pigcms{/***[/if]***/}
							<if condition="isset($config['vip_discount_pay_for'])">
								<li>
									<a data-toggle="tab" href="#vipdiscount">会员优惠</a>
								</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<input type="hidden" name="store_id" value="{pigcms{$now_store.store_id}"/>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">店铺名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" value="{pigcms{$now_store.name}" type="text"/>
								</div>
								<div class="form-group"><label class="col-sm-1">是否设置成主店</label><label><input type="radio" name="ismain" value="1" <if condition="$now_store['ismain'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>
									&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="ismain" value="0" <if condition="$now_store['ismain'] neq 1">checked="checked"</if>>&nbsp;&nbsp;否</label>
								 &nbsp;&nbsp;&nbsp;<span class="form_tips">如果将此店铺设置成主店，系统将自动取消其他已设置的主店</span>
								</div>
								<if condition="$config.international_phone eq 1">
									<div class="form-group">
										<label class="col-sm-1"><label for="password">区号</label></label>
										
										<select name="phone_country_type" id="phone_country_type" style="height:34px;float:left;margin-right:5px;">
										<option value="86" <if condition="$now_store.phone_country_type eq 86">selected</if>>+86 中国 China</option>
										<option value="1" <if condition="$now_store.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
										</select>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">联系电话</label></label>
									
									<input class="col-sm-2" size="20" name="phone" id="phone" value="{pigcms{$now_store.phone}" type="text"/>
									<span class="form_tips">多个电话号码以空格分开</span>
								</div>
							   <div class="form-group">
									<label class="col-sm-1"><label for="weixin">联系微信</label></label>
									<input class="col-sm-2" size="20" name="weixin" id="weixin" type="text" value="{pigcms{$now_store.weixin}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="qq">联系Q Q</label></label>
									<input class="col-sm-2" size="20" name="qq" id="qq" type="text" value="{pigcms{$now_store.qq}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1">关键词：</label>
									<input class="col-sm-3" maxlength="30" name="keywords" type="text" value="{pigcms{$now_store.keywords}" id="keywords"/><span class="form_tips">选填。<font color="red">（用空格分隔不同的关键词，最多5个）</font>，用户在微信将按此值搜索！</span> <a href="javascript:;" id="get_key_btn">按店铺名称获取</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">店铺经纬度</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" value="{pigcms{$now_store.long},{pigcms{$now_store.lat}" type="text" readonly="readonly" data-lat ="{pigcms{$now_store.lat}" data-long = "{pigcms{$now_store.long}" />
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="permoney">人均消费</label></label>
									<input class="col-sm-2" size="20" name="permoney" id="permoney" type="text" value="{pigcms{$now_store.permoney}" onkeyup="value=value.replace(/[^1234567890]+/g,'')"/>
									<span class="form_tips"> 元（必填）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="feature">店铺特色</label></label>
									<input class="col-sm-2" style="width:600px" name="feature" id="feature" type="text" value="{pigcms{$now_store.feature}" />
									<span class="form_tips">简单描述本店特色之处80字以内（必填）</span>
								</div>
								<div class="form-group" id="choose_category_s">
									<label class="col-sm-1"><label>店铺所属分类</label></label>
									<fieldset id="choose_category" cat_fid="{pigcms{$now_store.cat_fid}" cat_id="{pigcms{$now_store.cat_id}"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>店铺所在地</label></label>
									<fieldset id="choose_cityarea" province_id="{pigcms{$now_store.province_id}" city_id="{pigcms{$now_store.city_id}" area_id="{pigcms{$now_store.area_id}" circle_id="{pigcms{$now_store.circle_id}" market_id="{pigcms{$now_store.market_id}"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">店铺地址</label></label>
									<input class="col-sm-2" size="20" name="adress" id="adress" value="{pigcms{$now_store.adress}" type="text"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="trafficroute">交通路线</label></label>
									<input class="col-sm-2" name="trafficroute" id="trafficroute" type="text" value="{pigcms{$now_store.trafficroute}" style="width:600px"/>
									<span class="form_tips">简单描述本店交通路线80字以内</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">店铺排序</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_store.sort}" />
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">WiFi名称</label>
									<input class="col-sm-1" name="wifi_account" type="text" value="{pigcms{$now_store.wifi_account}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1">WiFi密码</label>
									<input class="col-sm-1" name="wifi_password" type="text" value="{pigcms{$now_store.wifi_password}" />
								</div>
								<if condition="$config['store_open_meal']">
									<div class="form-group">
										<label class="col-sm-1" for="have_meal">{pigcms{$config.meal_alias_name}</label>
										<select name="have_meal" id="have_meal">
											<option value="0" <if condition="$now_store['have_meal'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_store['have_meal'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_meal'] && false">
									<div class="form-group">
										<label class="col-sm-1" for="store_type">{pigcms{$config.meal_alias_name}类型</label>
										<select name="store_type" id="store_type">
											<option value="0" <if condition="$now_store['store_type'] eq 0">selected="selected"</if>>订餐和外卖</option>
											<option value="1" <if condition="$now_store['store_type'] eq 1">selected="selected"</if>>订餐</option>
											<option value="2" <if condition="$now_store['store_type'] eq 2">selected="selected"</if>>其他</option>
										</select>
										<span class="form_tips">【其他】是指（外卖，超市，花店...）</span>
									</div>
								</if>
								<if condition="$config['store_open_meal'] && false">
									<div class="form-group">
										<label class="col-sm-1" for="store_type">{pigcms{$config.meal_alias_name}类型</label>
										<select name="store_type" id="store_type">
											<option value="0" <if condition="$now_store['store_type'] eq 0">selected="selected"</if> disabled>订餐和外卖</option>
											<option value="1" <if condition="$now_store['store_type'] eq 1">selected="selected"</if>>到店消费</option>
											<option value="2" <if condition="$now_store['store_type'] eq 2">selected="selected"</if> disabled>其他</option>
											<option value="3" <if condition="$now_store['store_type'] eq 3">selected="selected"</if>>到店消费和外送</option>
										</select>
										<span class="form_tips red">【注】订餐和外卖、其他，这两种类型不能再被选择了，但是不影响已经选择过这两项的类型</span>
									</div>
								</if>
								<if condition="$config['store_open_group']">
									<div class="form-group">
										<label class="col-sm-1" for="have_group">{pigcms{$config.group_alias_name}</label>
										<select name="have_group" id="have_group">
											<option value="0" <if condition="$now_store['have_group'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_store['have_group'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_shop']">
									<div class="form-group">
										<label class="col-sm-1" for="have_group">{pigcms{$config.shop_alias_name}</label>
										<select name="have_shop" id="have_shop">
											<option value="0" <if condition="$now_store['have_shop'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_store['have_shop'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_waimai'] && false">
									<div class="form-group">
										<label class="col-sm-1" for="have_waimai">{pigcms{$config.waimai_alias_name}</label>
										<select name="have_waimai" id="have_waimai">
											<option value="1" selected="selected">开启</option>
											<option value="0">关闭</option>
										</select>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">店铺LOGO</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectLogo">上传图片</a>
									<span class="form_tips">店铺LOGO暂时仅用于小程序{pigcms{$config.shop_alias_name}，建议上传680*480的图片</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">LOGO预览</label>
									<div id="upload_pic_box">
										<ul id="upload_logo_ul">
											<if condition="$now_store['logo']">
												<li class="upload_pic_li"><img src="{pigcms{$now_store.logo}"/><input type="hidden" name="logo" value="{pigcms{$now_store.logo}"/></li>
											</if>
										</ul>
									</div>
								</div>
								<div class="alert alert-info">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									假设您的营业时间为晚上20:00-凌晨02:00，请填写两个时间段，第一个为“20:00-23:59”，第二个为“00:00-02:00”
								</div>
								<div class="tabbable">
									<ul class="nav nav-tabs" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#shop_time_1">
												营业时间段1
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#shop_time_2">
												营业时间段2
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#shop_time_3">
												营业时间段3
											</a>
										</li>
									</ul>
									<div class="tab-content">
										<div id="shop_time_1" class="tab-pane in active">
											<div>
												<input id="Config_shop_start_time" type="text" value="{pigcms{$now_store.open_1|default='00:00'}" name="open_1" readonly/>	至
												<input id="Config_shop_stop_time" type="text" value="{pigcms{$now_store.close_1|default='00:00'}" name="close_1" readonly/>
												<div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>
												<div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div>
											</div>
										</div>
										<div id="shop_time_2" class="tab-pane">
											<div>
												<input id="Config_shop_start_time_2" type="text" value="{pigcms{$now_store.open_2|default='00:00'}" name="open_2" readonly/>	至
												<input id="Config_shop_stop_time_2" type="text" value="{pigcms{$now_store.close_2|default='00:00'}" name="close_2" readonly/>
												<div class="errorMessage" id="Config_shop_start_time_2_em_" style="display:none"></div>
												<div class="errorMessage" id="Config_shop_stop_time_2_em_" style="display:none"></div>
											</div>
										</div>
										<div id="shop_time_3" class="tab-pane">
											<div>
												<input id="Config_shop_start_time_3" type="text" value="{pigcms{$now_store.open_3|default='00:00'}" name="open_3" readonly/>	至
												<input id="Config_shop_stop_time_3" type="text" value="{pigcms{$now_store.close_3|default='00:00'}" name="close_3" readonly/>
												<div class="errorMessage" id="Config_shop_start_time_3_em_" style="display:none"></div>
												<div class="errorMessage" id="Config_shop_stop_time_3_em_" style="display:none"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="txtstore" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">商家描述</label>
									<textarea class="col-sm-5" rows="5" name="txt_info">{pigcms{$now_store.txt_info}</textarea>
								</div>
								<div class="form-group">
									<label class="col-sm-1">商家图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips">第一张将作为主图片！最多上传10个图片！图片宽度建议为700px，高度建议为420px。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_store['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<div id="discount" class="tab-pane">
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									<p>这里用于设置用户快速买单优惠的地方，不支持其他业务类型。如需不给予用户优惠，将填写项清空即可。</p>
								</div>
								<div class="form-group">
									<label class="col-sm-1">优惠类型</label>
									<label><input type="radio" name="discount_type" value="1" <if condition="$now_store['discount_txt']['discount_type'] eq 1">checked="checked"</if> <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>>&nbsp;&nbsp;折扣</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="discount_type" value="2" <if condition="$now_store['discount_txt']['discount_type'] eq 2">checked="checked"</if> <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>>&nbsp;&nbsp;满减</label>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">普通折扣率</label>
									<input class="col-sm-2" style="width:60px" name="discount_percent"  type="text" value="{pigcms{$now_store['discount_txt']['discount_percent']}" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>/><b style="color:red">折</b>
									<span class="form_tips">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折</span>
								</div>
								<if condition="$config.open_extra_price eq 1">
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">每天限单折扣单数</label>
									<input class="col-sm-2" style="width:60px" name="discount_limit"  type="text" value="{pigcms{$now_store['discount_txt']['discount_limit']}" /><b style="color:red">单</b>
									<span class="form_tips">请填大于0的数字，不带小数点，0代表无</span>
								</div>
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">每天限单折扣率</label>
									<input class="col-sm-2" style="width:60px" name="discount_limit_percent"  type="text" value="{pigcms{$now_store['discount_txt']['discount_limit_percent']}" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>	<php>}</php>/><b style="color:red">折</b>
									<span class="form_tips">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，每天限单数内的折扣，超过数量将使用普通折扣率</span>
								</div>
								</if>
								<div class="form-group" <if condition="$now_store['discount_txt']['discount_type'] neq 2">style="display:none"</if> id="condition">
									<label class="col-sm-1">每满</label>
									<input class="col-sm-2" style="width:60px" name="condition_price"  type="text" value="{pigcms{$now_store['discount_txt']['condition_price']}" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND ($config['discount_controler'] eq 1)" />disabled</if>								
									<php>}</php>/>
									<label class="col-sm-1" style="color:red;width: 60px;">元，减</label>
									<input class="col-sm-2" style="width:60px" name="minus_price"  type="text" value="{pigcms{$now_store['discount_txt']['minus_price']}" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>/>
									<label class="col-sm-1" style="color:red">元</label>
								</div>
								<php>if(1==$config['group_store_discount_more']){</php>
								<div class="form-group">
								<label class="col-sm-1"><label>优惠方式</label></label>
									<if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">
										<strong style="color:red">只能由商家自己设置</strong>
									<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />
									    <strong style="color:red">只能由系统设置</strong>
									</if>
							
									
									<span><label><input id='discount_type0' name="vip_discount_type" <if condition="$now_store['vip_discount_type'] eq 2 ">checked="checked"</if> value="2" type="radio" <if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>></label>&nbsp;<span>折上折</span>&nbsp;</span>
									<span><label><input id='discount_type1' name="vip_discount_type" <if condition="$now_store['vip_discount_type'] eq 1 ">checked="checked"</if> value="1" type="radio" <if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>></label>&nbsp;<span>折扣最优</span></span>
									<strong style="color:red">折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<br/>
									折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。
									</strong>

								</div>
								
									<php>}</php>
								<div class="form-group">
									<label class="col-sm-1">买单二维码</label>
									<a href="{pigcms{$config.site_url}/wap.php?c=My&a=pay&store_id={pigcms{$now_store.store_id}&spread=1" class="see_qrcode">查看二维码</a>
									<span class="form_tips">您可以将此二维码下载打印成物料放至店铺中</span>
								</div>
								<div style="height:40px;"></div>
								<if condition="$config['store_ticket_have'] && $config['pay_in_store']">
									<div class="form-group">
										<label class="col-sm-1">绑定插件：</label>
										<select name="bind_store_trade" id="bind_store_trade" class="col-sm-2">
											<option value="" <if condition="$now_store['bind_store_trade'] eq ''">selected="selected"</if>>不绑定</option>
											<option value="ticket" <if condition="$now_store['bind_store_trade'] eq 'ticket'">selected="selected"</if>>票务插件</option>
										</select>
									</div>
								</if>
								<div class="store_trade_div" id="store_trade_ticket" style="display:none;">
									<div class="form-group">
										<span class="form_tips" style="margin-left:12px;">票务插件作为优惠买单针对线下票务市场（可用于景点售票、公交车票、长途客车票、电影票等）的补充。开启后 {pigcms{$config.cash_alias_name} 页面将使用此功能，无法使用以上优惠。</span>
									</div>
									<div class="form-group">
											<label class="col-sm-1">使用场景</label>
											<select name="store_trade_ticket[use_scene]">
												<option value="1" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '1'">selected="selected"</if>>景点售票</option>
												<option value="2" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '2'">selected="selected"</if>>公交车票</option>
												<option value="3" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '3'">selected="selected"</if>>长途客车票</option>
												<option value="4" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '4'">selected="selected"</if>>电影/演出</option>
												<option value="5" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '5'">selected="selected"</if>>话剧/歌剧</option>
												<option value="6" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '6'">selected="selected"</if>>游乐园</option>
												<option value="7" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '7'">selected="selected"</if>>运动健身</option>
												<option value="8" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '8'">selected="selected"</if>>美发/美业</option>
												<option value="0" <if condition="$now_store['store_trade_ticket']['use_scene'] eq '0'">selected="selected"</if>>其他场景</option>
											</select>
											<span class="form_tips">选择使用场景，页面显示相应图标。其他场景显示通用的店铺图标</span>
										</div>
									<div class="form-group">
										<label class="col-sm-1">单张票默认金额</label>
										<input class="col-sm-1" name="store_trade_ticket[default_money]" type="text" value="{pigcms{$now_store['store_trade_ticket']['default_money']|floatval=###}" /><span class="form_tips">元，不填写或填写0则不默认</span>
									</div>
									<div class="form-group">
										<label class="col-sm-1">最低售票张数</label>
										<input class="col-sm-1" name="store_trade_ticket[limit_num]" type="text" value="{pigcms{$now_store['store_trade_ticket']['limit_num']}" /><span class="form_tips">张，不填写或填写0都默认为1</span>
									</div>
									<if condition="$config['store_ticket_have_insure']">
										<div class="form-group">
											<label class="col-sm-1">开启销售保险</label>
											<select name="store_trade_ticket[have_insure]">
												<option value="1" <if condition="$now_store['store_trade_ticket']['have_insure'] eq '1'">selected="selected"</if>>开启</option>
												<option value="0" <if condition="$now_store['store_trade_ticket']['have_insure'] eq '0'">selected="selected"</if>>关闭</option>
											</select>
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险必须购买</label>
											<select name="store_trade_ticket[insure_mustby]">
												<option value="0" <if condition="$now_store['store_trade_ticket']['insure_mustby'] eq '0'">selected="selected"</if>>可选</option>
												<option value="1" <if condition="$now_store['store_trade_ticket']['insure_mustby'] eq '1'">selected="selected"</if>>必须</option>
											</select>
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险名称别名</label>
											<input class="col-sm-1" name="store_trade_ticket[insure_name]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_name']}" /><span class="form_tips">保险名称可变更为该行业内用户认可的名称，例如车票类保险可称为 乘意险（乘客意外险）</span>
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险（第一档）</label>
											单张票金额满&nbsp;&nbsp;<input name="store_trade_ticket[insure_tikcet_1]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_tikcet_1']|floatval=###}" />&nbsp;&nbsp;元收取此档保费，<input name="store_trade_ticket[insure_1]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_1']|floatval=###}" />&nbsp;&nbsp;元保 <input name="store_trade_ticket[insure_money_1]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_money_1']|floatval=###}" />&nbsp;&nbsp;元
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险（第二档）</label>
											单张票金额满&nbsp;&nbsp;<input name="store_trade_ticket[insure_tikcet_2]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_tikcet_2']|floatval=###}" />&nbsp;&nbsp;元收取此档保费，<input name="store_trade_ticket[insure_2]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_2']|floatval=###}" />&nbsp;&nbsp;元保 <input name="store_trade_ticket[insure_money_2]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_money_2']|floatval=###}" />&nbsp;&nbsp;元
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险（第三档）</label>
											单张票金额满&nbsp;&nbsp;<input name="store_trade_ticket[insure_tikcet_3]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_tikcet_3']|floatval=###}" />&nbsp;&nbsp;元收取此档保费，<input name="store_trade_ticket[insure_3]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_3']|floatval=###}" />&nbsp;&nbsp;元保 <input name="store_trade_ticket[insure_money_3]" type="text" value="{pigcms{$now_store['store_trade_ticket']['insure_money_3']|floatval=###}" />&nbsp;&nbsp;元
										</div>
										<div class="form-group">
											<label class="col-sm-1">保险购买说明</label>
											<textarea class="col-sm-5" rows="5" name="store_trade_ticket[insure_info]">{pigcms{$now_store['store_trade_ticket']['insure_info']}</textarea>
										</div>
									</if>
								</div>
							</div>
							<if condition="!empty($levelarr) AND isset($config['vip_discount_pay_for'])">
							<div id="vipdiscount" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</label>
								</div>
							    <volist name="levelarr" id="vv">
								  <div class="form-group">
								    <input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
								    <input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
									<label class="col-sm-1">{pigcms{$vv['lname']}：</label>
									优惠类型：&nbsp;
									<select name="leveloff[{pigcms{$vv['level']}][type]" <if condition="empty($_SESSION['system']) AND $config['discount_sync'] eq 1">disabled</if>>
										<option value="0">无优惠</option>
										<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
										<option value="2" <if condition="$vv['type'] eq 2">selected="selected"</if>>立减</option>
									</select>
									<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" <if condition="empty($_SESSION['system']) AND $config['discount_sync'] eq 1">disabled</if>/>
								</div>
								</volist>
							</div>
							</if>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
	<div class="modal-dialog" style="width:80%;">
		<div class="modal-content" style="width:100%;">
			<div class="modal-header no-padding" style="width:100%;">
				<div class="table-header">
					<button id="close_button" type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					选择经纬度
				</div>
			</div>
			<div class="modal-body no-padding" style="width:100%;">
				<form id="map-search" style="margin:10px;">
					<input id="map-keyword" type="textbox" style="width:500px;" placeholder="尽量填写城市、区域、街道名"/>
					<input type="submit" value="搜索"/>
				</form>
				<div style="margin-left:10px;margin-bottom:10px;" id="map_tips">(用鼠标滚轮可以缩放地图)    拖动红色图标，经纬度框内将自动填充经纬度。</div>
				<div style="width:100%;height:560px;min-height:560px;" id="cmmap"></div>
			</div>

			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-success pull-right" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i>
					关闭
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- PAGE CONTENT ENDS -->

<script type="text/javascript">
var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}",choose_market="{pigcms{:U('Area/ajax_market')}",choose_cat_fid="{pigcms{:U('Merchant_category/ajax_cat_fid')}",choose_cat_id="{pigcms{:U('Merchant_category/ajax_cat_id')}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script  src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/google_map.js"></script>

        <else />
    <script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
</if>
<script type="text/javascript" src="{pigcms{$static_path}js/merchant_category.js"></script>
<script type="text/javascript">
$(function($){
	$('#Config_shop_start_time').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_start_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_start_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('input:radio[name=discount_type]').click(function(){
		if (1 == $(this).val()) {
			$('.percent').show();
			$('#condition').hide();
		} else if (2 == $(this).val()) {
			$('.percent').hide();
			$('#condition').show();
		}
	});
});
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
	$('.see_qrcode').live('click',function(){
		var url = '{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode_html&down=true&qrCon=';
		var qrcode_url = url+encodeURIComponent($(this).attr('href'));
		art.dialog.open(qrcode_url,{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'查看页面二维码',
			padding: 0,
			width: 312,
			height: 312,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
</script>
<style>
.BMap_cpyCtrl{display:none;}
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;border:1px solid #ccc;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 10){
			alert('最多上传10个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Config/store_ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#J_selectLogo').click(function(){
		editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_logo_ul').html('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="logo" value="'+url+'"/></li>');
					editor.hideDialog();
				}
			});
		});
	});
	
	$('#edit_form').submit(function(){
		$.post("{pigcms{:U('Config/store_edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Config/store')}";
			}else{
				alert(result.info);
			}
		})
		return false;
	});

	$('#get_key_btn').click(function(){
		var s_name = $('input[name="name"]');
		s_name.val($.trim(s_name.val()));
		$('#keywords').val($.trim($('#keywords').val()));
		if(s_name.val().length == 0){
			alert('请先填写店铺名称！');
			s_name.focus();
		}else if($('#keywords').val().length != 0){
			alert('请先删除您填写的关键词！');
			$('#keywords').focus();
		}else{
			$.get("{pigcms{:U('Index/Scws/ajax_getKeywords')}",{title:s_name.val()},function(result){
				result = $.parseJSON(result);
				if(result.num == 0){
					alert('您的店铺名称没有提取到关键词，请手动填写关键词！');
					$('#keywords').focus();
				}else{
					$('#keywords').val(result.list.join(' ')).focus();
				}
			});
		}
	});
	
	if($('#bind_store_trade').val() == 'ticket'){
		$('#store_trade_ticket').show();
	}
	$('#bind_store_trade').change(function(){
		$('.store_trade_div').hide();
		if($('#bind_store_trade').val() == 'ticket'){
			$('#store_trade_ticket').show();
		}
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Config/store_ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>

<include file="Public:footer"/>
