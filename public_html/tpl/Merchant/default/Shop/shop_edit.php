<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active">编辑{pigcms{$config.shop_alias_name}信息</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#category">选择分类</a>
							</li>
							<!--li>
								<a data-toggle="tab" href="#label">选择标签</a>
							</li>
							<li>
								<a data-toggle="tab" href="#pay">支付方式</a>
							</li>
							<li>
								<a data-toggle="tab" href="#delivertime">配送时间</a>
							</li-->
							<li>
								<a data-toggle="tab" href="#promotion">店铺折扣</a>
							</li>
							<li>
								<a data-toggle="tab" href="#stock">库存类型选择</a>
							</li>
						  	<if condition="!empty($levelarr)">
							<li>
								<a data-toggle="tab" href="#levelcoupon">会员优惠</a>
							</li>
							</if>
							<if condition="$config.open_store_live eq 1">
							<li>
								<a data-toggle="tab" href="#store_live">视频直播</a>
							</li>
							</if>
							<li>
								<a data-toggle="tab" href="#offlineShop">线下零售</a>
							</li>
							<li>
								<a data-toggle="tab" href="#store_fitment">页面装修</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">				
						
							<div id="basicinfo" class="tab-pane active">

								<div class="form-group">
									<label class="col-sm-1"><label>是否开启商城</label></label>
									<select name="store_theme">
									<option value="0" <if condition="$store_shop['store_theme'] eq 0 ">selected</if>>关闭</option>
									<option value="1" <if condition="$store_shop['store_theme'] eq 1 ">selected</if>>开启</option>
									</select>
									<span class="form_tips"> 开启：{pigcms{$config.shop_alias_name}中的商品显示为侧重图片模板，店铺中的商品设置了商城属性后，商品就会进入商城中；关闭：{pigcms{$config.shop_alias_name}中的商品显示为侧重文字模板，商品在商城中不显示。</span>
								</div>
								<div class="form-group background" <if condition="$store_shop['store_theme'] eq 0">style="display:none"</if>>
									<label class="col-sm-1">是否显示{pigcms{$config.shop_alias_name}店铺</label>
									<label><input name="is_close_shop" <if condition="$store_shop['is_close_shop'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>显示</span>&nbsp;</label>
									<label><input name="is_close_shop" value="1" <if condition="$store_shop['is_close_shop'] eq 1 ">checked="checked"</if> type="radio" >&nbsp;<span>不显示</span></label>
									<span class="form_tips red">开启了商城时，{pigcms{$config.shop_alias_name}是否还可用</span>
								</div>
                                <div class="form-group background" <if condition="$store_shop['store_theme'] eq 0">style="display:none"</if>>
                                    <label class="col-sm-1">商城店铺背景</label>
                                    <div style="display:inline-block;" id="J_selectImage">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
                                    </div>
                                    <span class="form_tips red"> 商城店铺背景建议上传尺寸：640*420。</span>
                                </div>
								<div class="form-group background" <if condition="$store_shop['store_theme'] eq 0">style="display:none"</if>>
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<if condition="$now_store['background']">
											<li class="upload_pic_li"><img src="{pigcms{$now_store['background_image']}"/><input type="hidden" name="background" value="{pigcms{$now_store['background']}"/><br/><a href="#" onclick="deleteImage('{pigcms{$now_store['background']}',this);return false;">[ 删除 ]</a></li>
											</if>
										</ul>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>配送自提点</label></label>
									<label><span><label><input name="is_open_pick" <if condition="$store_shop['is_open_pick'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
									<label><span><label><input name="is_open_pick" <if condition="$store_shop['is_open_pick'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>开启</span></span></label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="Config_notice">店铺公告</label></label>
									<textarea class="col-sm-3" rows="4" name="store_notice" id="Config_notice">{pigcms{$store_shop.store_notice}</textarea>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>多级分类</label></label>
									<label><span><label><input name="is_mult_class" <if condition="$store_shop['is_mult_class'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
									<label><span><label><input name="is_mult_class" <if condition="$store_shop['is_mult_class'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>开启</span></span></label>
								    <span class="form_tips red"> 开启多级分类后，{pigcms{$config.shop_alias_name}商品将分三级分类展示，便于您更好的管理多类型商品，注意：开启多级分类后，前台只展示侧重文字模板，侧重图片模板将关闭。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>自动接单</label></label>
									<label><span><label><input name="is_auto_order" <if condition="$store_shop['is_auto_order'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
									<label><span><label><input name="is_auto_order" <if condition="$store_shop['is_auto_order'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>开启</span></span></label>
								</div>

									<php>if($config['international_phone']== 1){</php>
								<div class="form-group">
									<label class="col-sm-1"><label>区号</label></label>
										
											<select name="phone_country_type" id="phone_country_type" style="height:31px;float:left;margin-right:5px;">
											<option value="86" <if condition="$store_shop.phone_country_type eq 86">selected</if>>+86 中国 China</option>
											<option value="1" <if condition="$store_shop.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
											</select>
								</div>
									<php>}</php>


								<if condition="$config['shop_order_voice_notice']">
									<div class="form-group">
										<label class="col-sm-1">超时未接单电话通知</label>
										
									
										<input class="col-sm-2" name="voice_phone" id="Config_voice_phone" type="text" value="{pigcms{$store_shop.voice_phone}" />
										<label class="form_tips">手机号码，多个以空格分开。超过 {pigcms{$config.shop_order_voice_notice} 分钟未接单会进行电话通知接单，防止未及时处理导致取消订单（{pigcms{$config.shop_order_cancel_time}分钟）。</label>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label>开发票</label></label>
									<label><span><label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>不支持</span>&nbsp;</span></label>
									<label><span><label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>支持</span></span></label>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1">线下零售打包费</label>
                                    <label><span><label><input name="is_open_retail_pack" <if condition="$store_shop['is_open_retail_pack'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
                                    <label><span><label><input name="is_open_retail_pack" <if condition="$store_shop['is_open_retail_pack'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>开启</span></span></label>
                                    <span class="form_tips red"> 开启时线下零售售出的商品按照这个商品设置的打包费收取相应的打包费，关闭时，线下零售售出的商品不收打包费。</span>
                                </div>
								<div class="form-group">
									<label class="col-sm-1">满足</label>
									<input class="col-sm-1" size="10" maxlength="10" name="invoice_price" id="Config_invoice_price" type="text" value="{pigcms{$store_shop.invoice_price|floatval}" />
									<label class="form_tips">元，可开发票</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">可提前</label>
									<input class="col-sm-1" size="10" maxlength="10" name="advance_day" id="Config_advance_day" type="text" value="{pigcms{$store_shop.advance_day}" />
									<label class="form_tips">天，进行预订下单。</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">人均消费</label>
									<input class="col-sm-1" size="10" maxlength="10" name="mean_money" id="Config_mean_money" type="text" value="{pigcms{$store_shop.mean_money|floatval}" />
									<span class="form_tips">元<span class="required red">*</span></span>
								</div>
                                <!--div class="form-group">
                                    <label class="col-sm-1">虚拟销量</label>
                                    <input class="col-sm-1" size="10" maxlength="10" name="virtual_sale_count" id="Config_virtual_sale_count" type="text" value="{pigcms{$store_shop.virtual_sale_count|intval}" />
                                    <span class="form_tips">显示给用户看的销量=虚拟销量+实际销量</span>
                                </div-->
								<div class="form-group">
									<label class="col-sm-1">包装费别名</label>
									<input class="col-sm-1" size="10" maxlength="10" name="pack_alias" type="text" value="{pigcms{$store_shop.pack_alias|default='打包费'}" />
									<span class="form_tips"> 给商品进行包装时所要的耗材产生费用的名称（如：餐盒费,打包费...）。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">运费别名</label>
									<input class="col-sm-1" size="10" maxlength="10" name="freight_alias" type="text" value="{pigcms{$store_shop.freight_alias|default='配送费用'}" />
									<span class="form_tips"> 把商品从商家送到用户手上所产生的运费的费用名称（如：配送费用,运费...）。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Config_send_time">开启紧急关店</label>
                                    <select name="is_close" style="float: left;margin-right:5px;">
                                    <option value="0" <if condition="$store_shop['is_close'] eq 0 ">selected</if>>关闭</option>
                                    <option value="1" <if condition="$store_shop['is_close'] eq 1 ">selected</if>>开启</option>
                                    </select>
                                    <input class="col-sm-2" size="10" placeholder="关店原因，选填，建议12个字以内" name="close_reason" id="Config_work_time" type="text" value="{pigcms{$store_shop.close_reason}"/>
                                    <span class="form_tips red"> 注：状态是“关闭”时店铺是正常状态，“开启”是店铺已关闭。【紧急关店用于临时有事需要暂停营业的情况下，开启后，用户前台不可下单，请谨慎使用】</span>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1" for="Config_send_time"> 出单时长</label>
                                    <input class="col-sm-1" size="10" maxlength="10" name="work_time" id="Config_work_time" type="text" value="{pigcms{$store_shop.work_time}"/>　
                                    <select name="send_time_type">
                                    <option value="0" <if condition="$store_shop['send_time_type'] eq 0 ">selected</if>>分钟</option>
                                    <option value="1" <if condition="$store_shop['send_time_type'] eq 1 ">selected</if>>小时</option>
                                    <option value="2" <if condition="$store_shop['send_time_type'] eq 2 ">selected</if>>天</option>
                                    <option value="3" <if condition="$store_shop['send_time_type'] eq 3 ">selected</if>>周</option>
                                    <option value="4" <if condition="$store_shop['send_time_type'] eq 4 ">selected</if>>月</option>
                                    </select>
                                    <span class="form_tips red"> 注：从店员接单起到店员配好订单所有商品所需要的时间。</span>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>配送方式</label></label>
									<select name="deliver_type">
									<volist name="deliver_types" id="deliver">
									<option value="{pigcms{$deliver['id']}" <if condition="$store_shop['deliver_type'] eq $deliver['id']">selected</if>>{pigcms{$deliver['name']}</option>
									</volist>
									</select>
									<span class="form_tips red" id="showDeliverTips"> </span>
								</div>
                                
                                <div class="form-group deliver" <if condition="in_array($store_shop['deliver_type'], array(0,2,3,5))">style="display:none"</if>>
                                    <label class="col-sm-1" for="Config_send_time"> 配送时长</label>
                                    <input class="col-sm-1" size="10" maxlength="10" name="send_time" id="Config_send_time" type="text" value="{pigcms{$store_shop.send_time}"/>
                                    <span class="form_tips">分钟</span>
                                </div>
								<div class="form-group deliver" <if condition="in_array($store_shop['deliver_type'], array(0,2,3,5))">style="display:none"</if>>
									<label class="col-sm-1">起送价格</label>
									<input class="col-sm-1" size="10" maxlength="10" name="basic_price" id="Config_basicprice" type="text" value="{pigcms{$store_shop.basic_price|floatval}" />
									<span class="form_tips">元</span>
								</div>
								<div class="form-group deliver" <if condition="in_array($store_shop['deliver_type'], array(0,2,3,5))">style="display:none"</if>>
									<label class="col-sm-1">加价送</label>
									<input class="col-sm-1" size="10" maxlength="10" name="extra_price" type="text" value="{pigcms{$store_shop.extra_price|floatval}" />
									<span class="form_tips red">元 【当订单的价格没有达到起送价加的时候加价也可以配送】</span>
								</div>
								<div class="form-group deliver" <if condition="in_array($store_shop['deliver_type'], array(0,2,3,5))">style="display:none"</if>>
									<label class="col-sm-1" for="Config_delivery_radius">服务距离</label>
									<div class="col-sm-10">
    									<div class="form-group">
        									<label class="col-sm-1"><input name="delivery_range_type" value="0" type="radio" <if condition="$store_shop['delivery_range_type'] eq 0">checked</if>>半径范围</label>
        									<input class="col-sm-1" size="10" maxlength="10" name="delivery_radius" type="text" value="{pigcms{$store_shop.delivery_radius|floatval}" <if condition="$store_shop['delivery_range_type'] eq 1">disabled</if>/>
        									<span class="form_tips">公里</span>
    									</div>
    									<div class="form-group">
        									<div class="col-sm-1">
            									<div class="form-group">
            									   <label class="col-sm-12"><input name="delivery_range_type" value="1" type="radio" <if condition="$store_shop['delivery_range_type'] eq 1">checked</if>>自定义范围</label>
            									   <input type="hidden" name="delivery_range_polygon" id="delivery_range_polygon" />
            									</div>
            									<div class="form-group map" <if condition="$store_shop['delivery_range_type'] eq 0">style="display:none"</if>>
            									   <label class="col-sm-12"><input type="button" value="绘制配送范围" id="baiduMap"/></label>
            									</div>
        									</div>
        									<div id="allmap" class="col-sm-9 map" style="height:350px;<if condition="$store_shop['delivery_range_type'] eq 0">display:none</if>" ></div>
    									</div>
									</div>
								</div>
								<div class="tabbable deliver deliver_time" <if condition="in_array($store_shop['deliver_type'], array(0, 2, 3))">style="display:none"</if>>
									<ul class="nav nav-tabs" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#shop_time_1">
												配送时间段一
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#shop_time_2">
												配送时间段二
											</a>
										</li>
                                        <li>
                                            <a data-toggle="tab" href="#shop_time_3">配送时间段三</a>
                                        </li>
									</ul>
									<div class="tab-content" >
										<div id="shop_time_1" class="tab-pane in active">
											<div class="form-group deliver deliver_time" >
												<label class="col-sm-1"><label>配送时间</label></label>
												<div>
													<input id="delivertime_start" type="text" value="{pigcms{$store_shop.delivertime_start|default='00:00'}" name="delivertime_start" readonly style="width:70px"/>	至
													<input id="delivertime_stop" type="text" value="{pigcms{$store_shop.delivertime_stop|default='00:00'}" name="delivertime_stop" readonly style="width:70px"/>
													<span class="form_tips red">如果两个都不填写或两个值都‘00:00’的话，就代表24小时都可以配送的</span>
												</div>
											</div>
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1" for="Config_delivery_fee">时段一起送价</label>
                                                <div>
                                                    <input id="basic_price1" type="text" value="{pigcms{$store_shop.basic_price1|floatval}" name="basic_price1" style="width:50px"/>
                                                    <span class="form_tips red">每单达到这个价格才给予配送</b></span>
                                                </div>
                                            </div>
											<div class="form-group deliver deliver_time">
												<label class="col-sm-1" for="Config_delivery_fee">配送费</label>
												<div>
													<input id="basic_distance" type="text" value="{pigcms{$store_shop.basic_distance|floatval}" name="basic_distance" style="width:50px"/>	公里以内
													<input id="delivery_fee" type="text" value="{pigcms{$store_shop.delivery_fee|floatval}" name="delivery_fee" style="width:50px"/>元，超出范围每公里加
													<input id="per_km_price" type="text" value="{pigcms{$store_shop.per_km_price|floatval}" name="per_km_price" style="width:50px"/>元
												</div>
												<!--input class="col-sm-1" size="10" maxlength="10" name="delivery_fee" id="Config_delivery_fee" type="text" value="{pigcms{$store_shop.delivery_fee|floatval}"/>元<span class="required">*</span-->
											</div>
											
											<div class="form-group deliver deliver_time">
												<label class="col-sm-1" for="Config_delivery_fee">达到起送价格</label>
												<label><input name="reach_delivery_fee_type" <if condition="$store_shop['reach_delivery_fee_type'] eq 0 ">checked="checked"</if> value="0" type="radio">免外送费</label>　　
												<label><input name="reach_delivery_fee_type" <if condition="$store_shop['reach_delivery_fee_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >照样收取外送费</label>　　
												<label><input name="reach_delivery_fee_type" <if condition="$store_shop['reach_delivery_fee_type'] eq 2 ">checked="checked"</if> value="2" type="radio" >达到</label>
												<input size="10" maxlength="10" name="no_delivery_fee_value" id="Config_no_delivery_fee_value" type="text" value="{pigcms{$store_shop.no_delivery_fee_value|floatval}"/>元免外送费
											</div>
										</div>
										<div id="shop_time_2" class="tab-pane">
											<div class="form-group deliver deliver_time">
												<label class="col-sm-1"><label>配送时间</label></label>
												<div>
													<input id="delivertime_start2" type="text" value="{pigcms{$store_shop.delivertime_start2|default='00:00'}" name="delivertime_start2" readonly style="width:70px"/>	至
													<input id="delivertime_stop2" type="text" value="{pigcms{$store_shop.delivertime_stop2|default='00:00'}" name="delivertime_stop2" readonly style="width:70px"/>
													<span class="form_tips red">如果两个都不填写或两个值都‘00:00’的话，<b>就表示没有第二配送时间段</b></span>
												</div>
											</div>
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1" for="Config_delivery_fee">时段二起送价</label>
                                                <div>
                                                    <input id="basic_price2" type="text" value="{pigcms{$store_shop.basic_price2|floatval}" name="basic_price2" style="width:50px"/>
                                                    <span class="form_tips red">每单达到这个价格才给予配送</b></span>
                                                </div>
                                            </div>
											<div class="form-group deliver deliver_time">
												<label class="col-sm-1" for="Config_delivery_fee">配送费</label>
												<div>
													<input id="basic_distance2" type="text" value="{pigcms{$store_shop.basic_distance2|floatval}" name="basic_distance2" style="width:50px"/>	公里以内
													<input id="delivery_fee2" type="text" value="{pigcms{$store_shop.delivery_fee2|floatval}" name="delivery_fee2" style="width:50px"/>元，超出范围每公里加
													<input id="per_km_price2" type="text" value="{pigcms{$store_shop.per_km_price2|floatval}" name="per_km_price2" style="width:50px"/>元
												</div>
											</div>
											<div class="form-group deliver deliver_time">
												<label class="col-sm-1" for="Config_delivery_fee">达到起送价格</label>
												
												<label><input name="reach_delivery_fee_type2" <if condition="$store_shop['reach_delivery_fee_type2'] eq 0 ">checked="checked"</if> value="0" type="radio">免外送费</label>　　
												<label><input name="reach_delivery_fee_type2" <if condition="$store_shop['reach_delivery_fee_type2'] eq 1 ">checked="checked"</if> value="1" type="radio" >照样收取外送费</label>　　
												<label><input name="reach_delivery_fee_type2" <if condition="$store_shop['reach_delivery_fee_type2'] eq 2 ">checked="checked"</if> value="2" type="radio" >达到</label>
												<input size="10" maxlength="10" name="no_delivery_fee_value2" id="Config_no_delivery_fee_value" type="text" value="{pigcms{$store_shop.no_delivery_fee_value2|floatval}"/>元免外送费
											</div>
										</div>
                                        <div id="shop_time_3" class="tab-pane">
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1"><label>配送时间</label></label>
                                                <div>
                                                    <input id="delivertime_start3" type="text" value="{pigcms{$store_shop.delivertime_start3|default='00:00'}" name="delivertime_start3" readonly style="width:70px"/>  至
                                                    <input id="delivertime_stop3" type="text" value="{pigcms{$store_shop.delivertime_stop3|default='00:00'}" name="delivertime_stop3" readonly style="width:70px"/>
                                                    <span class="form_tips red">如果两个都不填写或两个值都‘00:00’的话，<b>就表示没有第三配送时间段</b></span>
                                                </div>
                                            </div>
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1" for="Config_delivery_fee">时段三起送价</label>
                                                <div>
                                                    <input id="basic_price3" type="text" value="{pigcms{$store_shop.basic_price3|floatval}" name="basic_price3" style="width:50px"/>
                                                    <span class="form_tips red">每单达到这个价格才给予配送</b></span>
                                                </div>
                                            </div>
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1" for="Config_delivery_fee">配送费</label>
                                                <div>
                                                    <input id="basic_distance3" type="text" value="{pigcms{$store_shop.basic_distance3|floatval}" name="basic_distance3" style="width:50px"/>   公里以内
                                                    <input id="delivery_fee3" type="text" value="{pigcms{$store_shop.delivery_fee3|floatval}" name="delivery_fee3" style="width:50px"/>元，超出范围每公里加
                                                    <input id="per_km_price3" type="text" value="{pigcms{$store_shop.per_km_price3|floatval}" name="per_km_price3" style="width:50px"/>元
                                                </div>
                                            </div>
                                            <div class="form-group deliver deliver_time">
                                                <label class="col-sm-1" for="Config_delivery_fee">达到起送价格</label>
                                                <label><input name="reach_delivery_fee_type3" <if condition="$store_shop['reach_delivery_fee_type3'] eq 0 ">checked="checked"</if> value="0" type="radio">免外送费</label>　　
                                                <label><input name="reach_delivery_fee_type3" <if condition="$store_shop['reach_delivery_fee_type3'] eq 1 ">checked="checked"</if> value="1" type="radio" >照样收取外送费</label>　　
                                                <label><input name="reach_delivery_fee_type3" <if condition="$store_shop['reach_delivery_fee_type3'] eq 2 ">checked="checked"</if> value="2" type="radio" >达到</label>
                                                <input size="10" maxlength="10" name="no_delivery_fee_value3" id="Config_no_delivery_fee_value" type="text" value="{pigcms{$store_shop.no_delivery_fee_value3|floatval}"/>元免外送费
                                            </div>
                                        </div>
									</div>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="Config_area">配送区域</label></label>
									<textarea class="col-sm-3" rows="4" name="delivery_area" id="Config_area">{pigcms{$store_shop.delivery_area}</textarea>
								</div-->
							</div>
							<div id="category" class="tab-pane">
								<volist name="category_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<span class="lbl"><label style="color: red">{pigcms{$vo.cat_name}：</label></span>
											</label>
											<volist name="vo['son_list']" id="child">
												<label>
													<input class="cat_class" type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
													<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
												</label>
											</volist>
										</div>
									</div>
								</volist>
							</div>
							<div id="label" class="tab-pane">
								<volist name="label_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<input class="cat_class" type="checkbox" name="store_labels[]" value="{pigcms{$vo.id}" id="Config_store_label_{pigcms{$vo.id}" <if condition="in_array($vo['id'], $store_shop['store_labels'])">checked="checked"</if>/>
												<span class="lbl"><label for="Config_store_label_{pigcms{$vo.id}">{pigcms{$vo.name}</label></span>
											</label>
										</div>
									</div>
								</volist>
							</div>
							<div id="pay" class="tab-pane">
								<if condition="$config['store_open_payone']">
									<div class="form-group">
										<div class="radio">
											<label>
												<input class="paycheck " type="checkbox" name="openpayone" value="1" id="Config_openpayone" onclick="check(this);" <if condition="$store_shop['openpayone'] eq 1">checked="checked"</if>/>
												<span class="lbl"><label for="Config_openpayone">货到付款</label></span>
											</label>
										</div>
									</div>
								</if>
								<div class="form-group">
									<div class="radio">
										<label>
											<input class="paycheck " type="checkbox" name="openpaytwo" value="1" id="Config_openpaytwo" onclick="check(this);" <if condition="$store_shop['openpaytwo'] eq 1">checked="checked"</if>/>
											<span class="lbl"><label for="Config_openpaytwo">余额支付</label></span>
										</label>
									</div>
								</div>
								<if condition="$config['store_open_paythree']">
									<div class="form-group">
										<div class="radio">
											<label>
												<input class="paycheck " type="checkbox" name="openpaythree" value="1" id="Config_openpaythree" onclick="check(this);" <if condition="$store_shop['openpaythree'] eq 1">checked="checked"</if>/>
												<span class="lbl"><label for="Config_openpaythree">在线支付</label></span>
											</label>
										</div>
									</div>
								</if>
							</div>
							<div id="delivertime" class="tab-pane">
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									<p>外卖有个特点，就是顾客消费的时间段比较集中，例如中午11点至12点半，晚上5点至6点半，都是点外卖的高峰期。对于某些订单量较大的商家或者多店铺运营者来说，如果顾客都临时下单，很难保证订单的及时配送，可能会导致顾客投诉与抱怨，引起顾客流失。为此，我们提供了店铺配送时间的功能设置。<br/><br/>每个店铺最多可以配置20个配送时间段，顾客在下单的时候，必须选择其中一个时间段。并且可以设置一个最少提前多少分钟下单，例如设置了最少提前30分钟下单，那么选择11:30-12:00时间段配送的顾客，至少要在11点之前下单，否则无法进入订单支付结算页面。
									</p>
								</div>
								<div class="form-group">
									<label class="col-sm-2" for="Config_opendelivertime">是否开启配送时间限制</label>
									<select name="open_deliver_time" id="Config_opendelivertime">
										<option value="0" <if condition="$store_shop['open_deliver_time'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$store_shop['open_deliver_time'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-2" for="Config_delivertimerange">最少提前多少分钟下单</label>
									<input class="col-sm-1" size="10" maxlength="3" name="deliver_time_range" id="Config_delivertimerange" type="text" value="{pigcms{$store_shop.deliver_time_range}" />分钟	
								</div>
								<div class="widget-box">
									<div class="widget-header">
										<h5>配送时间段</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main">
											<volist name="store_shop['deliver_time']" id="vo">
												<div style="margin:10px;width:400px;float:left;">({pigcms{$i})
													<input id="delivertime_{pigcms{$i}_start" type="text" value="{pigcms{$vo.start}" name="deliver_time[{pigcms{$i}][start]"/> 至 <input id="delivertime_{pigcms{$i}_stop" type="text" value="{pigcms{$vo.stop}" name="deliver_time[{pigcms{$i}][stop]"/>
												</div>
											</volist>
											<div style="clear:both;"></div>
										</div>
									</div>
								</div>
								<div style="clear:both;"></div>
							</div>
							
							<div id="promotion" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">店铺折扣</label>
									<if condition="!empty($_SESSION['system']) AND isset($config['discount_controler']) AND  $config['discount_controler'] eq 0">
										<strong style="color:red">只能由商家自己设置</strong>
										<input class="col-sm-1" size="10" maxlength="10" name="store_discount" id="Config_mean_full_money" type="text" value="{pigcms{$store_shop.store_discount}" disabled /><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />
									    <strong style="color:red">只能由系统设置</strong>
										<input class="col-sm-1" size="10" maxlength="10" name="store_discount" id="Config_mean_full_money" type="text" value="{pigcms{$store_shop.store_discount}" disabled /><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									<else />
									<input class="col-sm-1" size="10" maxlength="10" name="store_discount" id="Config_mean_full_money" type="text" value="{pigcms{$store_shop.store_discount}" /><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									</if>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>优惠方式</label></label>
									<php>if(1==$config['group_store_discount_more']){</php>
									<if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">
										<strong style="color:red">只能由商家自己设置</strong>
									<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />
									    <strong style="color:red">只能由系统设置</strong>
									<else />
									
									</if>
									<php>}</php>
									<span><label><input id='discount_type0' name="discount_type" <if condition="$store_shop['discount_type'] eq 0 ">checked="checked"</if> value="0" type="radio"  <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>></label>&nbsp;<span>折上折</span>&nbsp;</span>
									<span><label><input id='discount_type1' name="discount_type" <if condition="$store_shop['discount_type'] eq 1 ">checked="checked"</if> value="1" type="radio" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>></label>&nbsp;<span>折扣最优</span></span>
									<strong style="color:red">折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<br/>
									折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。
									</strong>
								</div>
								<div style="clear:both;"></div>
							</div>
							
							<div id="stock" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">库存类型：</label>
									<label><input type="radio" name="stock_type" value="0" <if condition="$store_shop['stock_type'] eq 0">checked="checked"</if>>&nbsp;&nbsp;每天自动更新固定量的库存</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="stock_type" value="1" <if condition="$store_shop['stock_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;固定的库存，不会每天自动更新</label>&nbsp;&nbsp;&nbsp;
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">减库存类型：</label>
									<label><input type="radio" name="reduce_stock_type" value="0" <if condition="$store_shop['reduce_stock_type'] eq 0">checked="checked"</if>>&nbsp;&nbsp;支付成功后减库存</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="reduce_stock_type" value="1" <if condition="$store_shop['reduce_stock_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;下单成功后减库存</label>
									<span class="form_tips red">1.支付成功后减库存：可能会出现售出的数量大于商品总数；2.下单成功后减库存：可能会出现大量下单但是没有支付时库存就已经没有了，但是如果在下面设置的买单时长的时间内还是没有买单的话系统自动回滚库存</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">买单时长：</label>
									<input class="col-sm-1" size="10" maxlength="3" name="rollback_time" id="rollback_time" type="text" value="{pigcms{$store_shop.rollback_time|default='20'}" />
									<span class="form_tips">分钟,至少是10分钟</span>
								</div>
								<div style="clear:both;"></div>
							</div>

							<if condition="!empty($levelarr)">
							<div id="levelcoupon" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</label>
								</div>
							    <volist name="levelarr" id="vv">
								  <div class="form-group">
								    <input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
								    <input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
									<label class="col-sm-1">{pigcms{$vv['lname']}：</label>
									优惠类型：&nbsp;
									<select name="leveloff[{pigcms{$vv['level']}][type]" <php>if(1==$config['group_store_discount_more']){</php><if condition="empty($_SESSION['system']) AND $config['discount_sync'] eq 1">disabled</if> <php>}</php>>
										<option value="0">无优惠</option>
										<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
										<!--<option value="2">立减</option>-->
									</select>
									<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" <php>if(1==$config['group_store_discount_more']){</php><if condition="empty($_SESSION['system']) AND $config['discount_sync'] eq 1">disabled</if><php>}</php> placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')"/>
								</div>
								</volist>
							</div>
							</if>
							<if condition="$config.open_store_live eq 1">
							<div id="store_live" class="tab-pane">
								
							    <div class="form-group">
									<label class="col-sm-1" for="store_live_url">视频直播地址</label>
									<input class="col-sm-3" size="30" name="store_live_url" id="store_live_url" type="text" value="{pigcms{$store_shop.store_live_url}" />	
								</div>
							
							</div>
							</if>
							<div id="offlineShop" class="tab-pane">
							    <div class="form-group">
									<label class="col-sm-1"><label>开启线下零售</label></label>
									<label><span><label><input name="open_offline" <if condition="$store_shop['open_offline'] eq 1">checked="checked"</if> value="1" type="radio"></label>&nbsp;<span>开启</span>&nbsp;</span></label>
									<label><span><label><input name="open_offline" <if condition="$store_shop['open_offline'] eq 0 ">checked="checked"</if> value="0" type="radio" ></label>&nbsp;<span>关闭</span></span></label>
								    <span class="form_tips red">是否开启线下零售</span>
								</div>
							</div>
							<div id="store_fitment" class="tab-pane">
								<div class="store_fitment_div">
									<div class="form-group">
										<div class="group-title">门店主题色
											<button type="button" class="btn fitment" data-type="shop_fitment_color" data-title="门店主题色">装修</button>
										</div>
										<div class="group-content">有效提升您店铺的品质感，让您的品牌深入人心，从而获得更多顾客的信赖和喜爱。</div>
									</div>
									<div class="form-group">
										<div class="group-title">门店专题
											<button type="button" class="btn fitment subject" data-type="shop_fitment_subject" data-title="门店专题">装修</button>
										</div>
										<div class="group-content">通过运营位推荐新品、打造爆款商品，更可设置多个不同时间生效的海报。</div>
									</div>
									<div class="form-group">
										<div class="group-title">爆款橱窗
											<button type="button" class="btn fitment showcase" data-type="shop_fitment_showcase" data-title="爆款橱窗">装修</button>
										</div>
										<div class="group-content">将您的商品以大图橱窗展现，充分利用店内流量，从而提升下单率。</div>
									</div>
									<div class="form-group cat-group">
										<div class="group-title">特色分类</div>
										<div class="group-content cat">
											<div class="cat-row clearfix">
												<div class="cat-name">热销</div>
												<div class="cat-desc">
													<div class="cat-item">状态：<span id="fitment_cat_hot_status"><if condition="$subject_info['cat_hot_status']">展示<else/>隐藏</if></span></div>
													<div class="cat-item">排序方式：<span id="fitment_cat_hot_sort">
														<if condition="$subject_info['cat_hot_sort'] eq 1">
															价格从高到低排序
														<elseif condition="$subject_info['cat_hot_sort'] eq 2"/>
															价格从低到高排序
														<elseif condition="$subject_info['cat_hot_sort'] eq 3"/>
															销量从高到低排序
														<elseif condition="$subject_info['cat_hot_sort'] eq 4"/>
															销量从低到高排序
														<else/>默认排序</if></span></div>
													<div class="cat-item">描述：<span id="fitment_cat_hot_desc">{pigcms{$subject_info.cat_hot_desc|default='大家喜欢吃，才叫真好吃'}</span></div>
													<div class="cat-edit">
														<a title="修改" class="green fitment" style="padding-right:8px;"  data-type="shop_fitment_category_hot" data-title="热销分类">
															<i class="ace-icon fa fa-pencil bigger-130"></i>编辑
														</a>
													</div>
												</div>
											</div>
											<div class="cat-row clearfix">
												<div class="cat-name">优惠</div>
												<div class="cat-desc">
													<div class="cat-item">状态：<span id="fitment_cat_discount_status"><if condition="$subject_info['cat_discount_status']">展示<else/>隐藏</if></span></div>
													<div class="cat-item">排序方式：<span id="fitment_cat_discount_sort">
														<if condition="$subject_info['cat_discount_sort'] eq 1">
															价格从高到低排序
														<elseif condition="$subject_info['cat_discount_sort'] eq 2"/>
															价格从低到高排序
														<elseif condition="$subject_info['cat_discount_sort'] eq 3"/>
															销量从高到低排序
														<elseif condition="$subject_info['cat_discount_sort'] eq 4"/>
															销量从低到高排序
														<else/>默认排序</if></span></div>
													<div class="cat-item">描述：<span id="fitment_cat_discount_desc">{pigcms{$subject_info.cat_discount_desc|default='美味又实惠，大家快来抢'}</span></div>
													<div class="cat-edit">
														<a title="修改" class="green fitment" style="padding-right:8px;"  data-type="shop_fitment_category_discount" data-title="优惠分类">
															<i class="ace-icon fa fa-pencil bigger-130"></i>编辑
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="group-title">品牌故事
											<button type="button" class="btn fitment" data-type="shop_fitment_history" data-title="品牌故事">装修</button>
										</div>
										<div class="group-content">创建个性页面，展现您的品牌故事，让您的品牌深入人心，从而获得更多顾客的信赖和喜爱。</div>
									</div>
								</div>
								<div id="fitment_preview">
									<div class="fitment_header" style="background-color:#{pigcms{$now_store.shop_fitment_color|default='06C1AE'};">
										<img src="{pigcms{$static_public}images/shop_fitment/header.png"/>
									</div>
									<div class="fitment_menu" style="">
										<img src="{pigcms{$static_public}images/shop_fitment/menu.png"/>
									</div>
									<div class="fitment_subject" style="padding:6px;<if condition="$now_store['shop_subject_show'] eq '0' && $subject_info['subject_pic']">display:none;</if>">
										<img src="<if condition="$subject_info['subject_pic']">{pigcms{$subject_info.subject_pic}?t={pigcms{$_SERVER.REQUEST_TIME}<else/>{pigcms{$static_public}images/shop_fitment/subject.png</if>"/>
									</div>
									<div class="fitment_showcase" style="padding:6px;<if condition="$now_store['shop_showcase_show'] eq '0' && $subject_info['showcase_name']">display:none;</if>">
										<div id="fitment_showcase_name" style="font-size:14px;font-weight:bold;margin-bottom:5px;">{pigcms{$subject_info.showcase_name|default='商家推荐'}</div>
										<img src="{pigcms{$static_public}images/shop_fitment/showcase.png"/>
									</div>
									<div class="fitment_good" style="">
										<img src="{pigcms{$static_public}images/shop_fitment/good.png"/>
									</div>
								</div>
							</div>
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
<style>
.webuploader-container{
	position:relative;
}
.webuploader-container div{
	width: 78px!important;
    height: 34px!important;
}
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
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
	background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<link rel="stylesheet" href="{pigcms{$static_path}css/activity.css">
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing&key={pigcms{$config.google_map_ak}"></script>
    <script>
        var polygon = '{pigcms{$store_shop['delivery_range_polygon']}';
        if(polygon){
            polygon = $.parseJSON(polygon);
            var nowlat = '{pigcms{$now_store["lat"]}';
            var nowlng = '{pigcms{$now_store["long"]}';
        }else{
            nowlat = 31.817797156213604;
            nowlng = 117.22220727680337;
        }
        var bermudaTriangle;
        var map;
        var drawingManager;
        var selectedShape;
        $(document).ready(function() {
            $('input[type="radio"][name="delivery_range_type"]').click(function () {
                if ($(this).val() == 1) {
                    $('input[name="delivery_radius"]').attr('disabled', true);
                    $('.map').show();
                } else {
                    $('input[name="delivery_radius"]').attr('disabled', false);
                    $('.map').hide();
                }
            });
        });
        initMap();
        function initMap() {
            map = new google.maps.Map(document.getElementById('allmap'), {
                zoom: 14,
                center: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)}
            });
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)},
                map: map,
                title: '我的位置'
            });
            google.maps.event.addDomListener(document.getElementById('baiduMap'), 'click', deleteSelectedShape);
            // Define the LatLng coordinates for the polygon's path.
            //绘画工具 设置
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ]
                },
                //设置图形显示样式
                circleOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 1,
                    strokeWeight: 5,
                    clickable: false,
                    editable: true,
                    zIndex: 1
                },
                polygonOptions: {
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    editable: true,
                }
            });
            drawingManager.setMap(map);
            //注册 多边形 绘制完成事件
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                var array = polygon.getPath().getArray();
                showLonLat(array);
            });
            //将写入的加入数组
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                var newShape = e.overlay;

                newShape.type = e.type;

                if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                    // Switch back to non-drawing mode after drawing a shape.
                    drawingManager.setDrawingMode(null);

                    // Add an event listener that selects the newly-drawn shape when the user
                    // mouses down on it.
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        if (e.vertex !== undefined) {
                            if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                                var path = newShape.getPaths().getAt(e.path);
                                path.removeAt(e.vertex);
                                if (path.length < 3) {
                                    newShape.setMap(null);
                                }
                            }
                            if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                                var path = newShape.getPath();
                                path.removeAt(e.vertex);
                                if (path.length < 2) {
                                    newShape.setMap(null);
                                }
                            }
                        }
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
                else {
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
            });
            //循环显示 经纬度
            var latlng = [];
            function showLonLat(arr) {
                for (var i = 0; i < arr.length; i++) {

                    latlng.push(arr[i].lat() + ',' + arr[i].lng());
                    $('#delivery_range_polygon').val(latlng.join('|'));
                }
                ;
            }

            var polyCoords = [];
            var lat_lng = [];
            for (i = 0; i < polygon.length; i++) {
                for (k = 0; k < polygon[i].length; k++) {
                    polyCoords.push({lat: +parseFloat(polygon[i][k].lat), lng: +parseFloat(polygon[i][k].lng)});
                    lat_lng.push(polygon[i][k].lat + ',' + polygon[i][k].lng);
                }
            }
            $('#delivery_range_polygon').val(lat_lng.join('|'));
            // Construct the polygon.
            bermudaTriangle = new google.maps.Polygon({
                paths: polyCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            addPolygon();
            function setSelection (shape) {
                selectedShape = shape;
            }
            function deleteSelectedShape(){
                bermudaTriangle.setMap(null);
                if (selectedShape) {
                    selectedShape.setMap(null);
                }
            }

            function addPolygon() {
                bermudaTriangle.setMap(map);
            }
        }

    </script>
    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<link rel="stylesheet" href="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
    <script>
        var polygon = '{pigcms{$store_shop['delivery_range_polygon']}';
        polygon = $.parseJSON(polygon);
        var oldOverlay = [];
        $(document).ready(function(){

            $('input[type="radio"][name="delivery_range_type"]').click(function(){
                if ($(this).val() == 1) {
                    $('input[name="delivery_radius"]').attr('disabled', true);
                    $('.map').show();
                } else {
                    $('input[name="delivery_radius"]').attr('disabled', false);
                    $('.map').hide();
                }
            });
            var map = new BMap.Map("allmap",{"enableMapClick":false}), point = new BMap.Point('{pigcms{$now_store["long"]}', '{pigcms{$now_store["lat"]}');
            map.centerAndZoom(point, 15);
            map.enableScrollWheelZoom();
            var marker = new BMap.Marker(point);// 创建标注
            map.addOverlay(marker);
            marker.enableDragging();
            if (polygon != null) {
                for (var i in polygon) {
                    var polygonArr = [];
                    var lat_lng = [];
                    for (var ii in polygon[i]) {
                        polygonArr.push(new BMap.Point(polygon[i][ii].lng, polygon[i][ii].lat));
                        lat_lng.push(polygon[i][ii].lat + ',' + polygon[i][ii].lng);
                    }
                    $('#delivery_range_polygon').val(lat_lng.join('|'));

                    var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});

                    map.addOverlay(poly);  //创建多边形
                    poly.enableEditing();
					oldOverlay.push(poly);
					poly.addEventListener("lineupdate", function (e) {
						lat_lng = [];
						delivery_area = $.map(e.target.getPath(), function (item) {
							  lat_lng.push(item.lat + ',' + item.lng);
							  return {'lng': item.lng, 'lat': item.lat};
						});
						$('#delivery_range_polygon').val(lat_lng.join('|'));
					
					});
                }
            }

            var overlays = [];
            var overlaycomplete = function(e){
                overlays.push(e.overlay);
                var latLng = e.overlay.getPath();
                var lat_lng = [];
				var polygonArr = [];
				for (var i in latLng) {
					lat_lng.push(latLng[i].lat + ',' + latLng[i].lng);
					console.log( lat_lng)
					 polygonArr.push(new BMap.Point(latLng[i].lng, latLng[i].lat));
					   
				}
				
				 var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
					
				map.addOverlay(poly);  //创建多边形
				poly.enableEditing();
				poly.addEventListener("lineupdate", function (e) {
					lat_lng = [];
					delivery_area = $.map(e.target.getPath(), function (item) {
						  lat_lng.push(item.lat + ',' + item.lng);
						  return {'lng': item.lng, 'lat': item.lat};
					});
					$('#delivery_range_polygon').val(lat_lng.join('|'));
					
				});
				oldOverlay.push(poly)
				map.removeOverlay(e.overlay);
                $('#delivery_range_polygon').val(lat_lng.join('|'));
            };
            var styleOptions = {
                strokeColor:"rgb(51, 136, 255)",    //边线颜色。
				fillColor:"rgb(51, 136, 255)",      //填充颜色。当参数为空时，圆形将没有填充效果。
				strokeWeight: 3,       //边线的宽度，以像素为单位。
				strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
				fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
				strokeStyle: 'dashed' //边线的样式，solid或dashed。
            }
            //实例化鼠标绘制工具
            var drawingManager = new BMapLib.DrawingManager(map, {
                isOpen: false, //是否开启绘制模式
                enableDrawingTool: false, //是否显示工具栏
                drawingMode:BMAP_DRAWING_POLYGON,
                drawingToolOptions: {
                    anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
                    offset: new BMap.Size(5, 5), //偏离值
                },
                circleOptions: styleOptions, //圆的样式
                polylineOptions: styleOptions, //线的样式
                polygonOptions: styleOptions, //多边形的样式
                rectangleOptions: styleOptions //矩形的样式
            });


            $('#baiduMap').click(function(){
                drawingManager._open();
                for(var i = 0; i < overlays.length; i++){
                    map.removeOverlay(overlays[i]);
                }
                if (oldOverlay.length > 0) {
                    console.log(oldOverlay);
                    for(var i = 0; i < oldOverlay.length; i++){
                        map.removeOverlay(oldOverlay[i]);
                    }
                }
                overlays = [];
                oldOverlay = [];
                 polygonArr= [];
            });

            //添加鼠标绘制工具监听事件，用于获取绘制结果
            drawingManager.addEventListener('overlaycomplete', overlaycomplete);
        });
    </script>
</if>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>


var uploader = WebUploader.create({
	auto: true,
	swf: '{pigcms{$static_public}js/Uploader.swf',
	server: "{pigcms{:U('Shop/ajax_upload_shoppic', array('store_id' => $now_store['store_id']))}",
	pick: '#J_selectImage',
	accept: {
		title: 'Images',
		extensions: 'gif,jpg,jpeg,png',
		mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
	}
});
uploader.on('fileQueued',function(file){
	if($('.upload_pic_li').size() >= 1){
		uploader.cancelFile(file);
		alert('最多上传一张图片！');
		return false;
	}
});
uploader.on('uploadSuccess',function(file,response){
	if(response.error == 0){
		$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+response.url+'"/><input type="hidden" name="background" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ 删除 ]</a></li>');
	}else{
		alert(response.info);
	}
});

uploader.on('uploadError', function(file,reason){
	$('.loading'+file.id).remove();
	alert('上传失败！请重试。');
});


function deleteImage(path,obj){
	$.post("{pigcms{:U('Shop/ajax_del_shoppic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}

function check(obj){
	var length = $('.paycheck:checked').length;
	if(length == 0){
		$(obj).attr('checked','checked');
		bootbox.alert('最少要选择一种支付方式');
	}			
}

var fitment_header_bgcolor = "{pigcms{$now_store.shop_fitment_color|default='06C1AE'}";
$(function($){
    if ($('select[name=deliver_type]').val() == 0) {
        $('#showDeliverTips').html('注：如果开启了自有支付，那么{pigcms{$config['deliver_name']}是无效的！{pigcms{$config['deliver_name']}时服务距离由平台来设置！');
    } else if ($('select[name=deliver_type]').val() == 2 || $('select[name=deliver_type]').val() == 4) {
        $('#showDeliverTips').html('注：如果使用自提功能请及时  <a href="{pigcms{:U('Config/pick_address_add')}" target="_black">添加自提点地址</a>; 如果没有自提点时，则系统默认将该店铺的地址作为自提点并且店员只可直接确认自提不可分配自提');
    } else if ($('select[name=deliver_type]').val() == 3) {
        $('#showDeliverTips').html('注：如果使用自提功能请及时  <a href="{pigcms{:U('Config/pick_address_add')}" target="_black">添加自提点地址</a>; 如果没有自提点时，则系统默认将该店铺的地址作为自提点并且店员只可直接确认自提不可分配自提，如果开启了自有支付，那么{pigcms{$config['deliver_name']}是无效的！{pigcms{$config['deliver_name']}时服务距离由平台来设置！');
    } else if ($(this).val() == 1) {
        $('#showDeliverTips').html('');
    } else if ($(this).val() == 5) {
        $('#showDeliverTips').html('快递配送：没有服务距离的限制，按配送时间段一的设置来计算配送费;');
    }
	$('select[name=deliver_type]').change(function(){
        if ($(this).val() == 0) {
            $('#showDeliverTips').html('注：如果开启了自有支付，那么{pigcms{$config['deliver_name']}是无效的！{pigcms{$config['deliver_name']}时服务距离由平台来设置！');
        } else if ($(this).val() == 2 || $(this).val() == 4) {
            $('#showDeliverTips').html('注：如果使用自提功能请及时  <a href="{pigcms{:U('Config/pick_address_add')}" target="_black">添加自提点地址</a>; 如果没有自提点时，则系统默认将该店铺的地址作为自提点并且店员只可直接确认自提不可分配自提');
        } else if ($(this).val() == 3) {
            $('#showDeliverTips').html('注：如果使用自提功能请及时  <a href="{pigcms{:U('Config/pick_address_add')}" target="_black">添加自提点地址</a>; 如果没有自提点时，则系统默认将该店铺的地址作为自提点并且店员只可直接确认自提不可分配自提，如果开启了自有支付，那么{pigcms{$config['deliver_name']}是无效的！{pigcms{$config['deliver_name']}时服务距离由平台来设置！');
        } else if ($(this).val() == 1) {
            $('#showDeliverTips').html('');
        } else if ($(this).val() == 5) {
            $('#showDeliverTips').html('快递配送：没有服务距离的限制，按配送时间段一的设置来计算配送费;');
        }
		if ($(this).val() == 1 || $(this).val() == 4) {
			$('.deliver').css('display', 'block');
		} else {
			$('.deliver').css('display', 'none');
		}
		if ($(this).val() == 5) {
            $('.deliver_time').css('display', 'block');
        }
		if ($(this).val() == 2) {
			$('.basic_price').css('display', 'none');
		} else {
			$('.basic_price').css('display', 'block');
		}
	});
    
	$('select[name=store_theme]').change(function(){
		if ($(this).val() == 1) {
			$('.background').show();
		} else {
			$('.background').hide();
		}
	});
	$('#delivertime_start').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'00','minute':'00'}));
	$('#delivertime_stop').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'23','minute':'59'}));
	$('#delivertime_start2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'00','minute':'00'}));
    $('#delivertime_stop2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'23','minute':'59'}));
    $('#delivertime_start3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'00','minute':'00'}));
    $('#delivertime_stop3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'23','minute':'59'}));

	$('#edit_form').submit(function(){
		$.post("{pigcms{:U('Shop/shop_edit',array('store_id'=>$_GET['store_id']))}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Shop/index')}";
			}else{
				alert(result.info);
			}
		})
		return false;
	});
	
	$('.fitment').click(function(){
		var _this = $(this);
		layer.open({
			title:'页面装修 -> ' + _this.data('title'),
			type:2,
			fixed:false,
			content:'{pigcms{$this->config['site_url']}/merchant.php?c=Shop&a='+_this.data('type')+'&store_id={pigcms{$_GET.store_id}',
			area:['600px',($(window).height()-$('#store_fitment').offset().top-100)+'px'],
			offset:[$('#store_fitment').offset().top+'px',$('#store_fitment').offset().left-30+'px'],
			shade: 0.01,
			cancel:function(){
				if(_this.data('type') == 'shop_fitment_color'){
					$('#fitment_preview .fitment_header').css('background-color','#' + fitment_header_bgcolor);
				}
			},
			move:false
			// ,shadeClose: false
		});
	});
	
	$('.fitment_subject img').click(function(){
		$('.fitment.subject').trigger('click');
	});
	$('.fitment_showcase img').click(function(){
		$('.fitment.showcase').trigger('click');
	});
	
	console.log($('.page-content').width());
	$('#fitment_preview').css('left',(500 + ($('.page-content').width()-500 - 350)/2)+'px');
});
</script>
<style type="text/css">
	#store_fitment{
		margin-left:30px;
		position:relative;
	}
	.store_fitment_div{
		width:500px;
	}
	#store_fitment .form-group{
		border-bottom:1px solid #EEE;
		padding:15px 0;
	}
	#store_fitment .form-group.cat-group{
		border-bottom:0;
	}
	#fitment_preview{
		position: absolute;
		left: 650px;
		top: 0;
		width:315px;
		-webkit-user-select: none;
		-webkit-touch-callout: none;
	}
	#fitment_preview img{
		width:100%;
	}
	.fitment_subject img:hover,.fitment_showcase img:hover{
		cursor:pointer;
		border:2px solid blue;
	}
	
	.group-title{
		position:relative;
		font-size:18px;
		color:black;
		margin-bottom:10px;
	}
	.group-content{
		width:320px;
		line-height:1.6;
		font-size:14px;
		color:#999;
	}
	.group-title .btn.fitment{
		background-color: #438eb9!important;
		border-radius: 3px;
		border: none;
		position:absolute;
		right:0px;
		top:0px;
	}
	.group-content.cat{
		width:100%;
	}
	.group-content .cat-row{
		margin-top:20px;
		position:relative;
	}
	.group-content .cat-name{
		font-size:16px;
		color:black;
		float:left;
		width:30%;
	}
	.group-content .cat-desc{
		font-size:14px;
		float:left;
		width:70%;
		border-bottom:1px solid #EEE;
		padding-bottom: 20px;
	}
	.group-content .cat-edit{
		position:absolute;
		right:0px;
		top:0px;
	}
</style>
<include file="Public:footer"/>
