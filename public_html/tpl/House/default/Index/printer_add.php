<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cloud"></i>
				<a href="{pigcms{:U('Index/printer')}">打印机管理</a>
			</li>
			<li class="active">增加打印机</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="alert alert-info" style="margin:10px 0;">
			<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>注意：如果您的打印机是WiFi打印机类型，就不要填写【绑定手机号】和【绑定账号】这两项，否则用不了！！！	
		</div>
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<input type="hidden" name="pigcms_id" value="{pigcms{$village_printer['pigcms_id']}"/>
								<div class="form-group">
									<label class="col-sm-1"><label>打印机名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$village_printer['name']}"/>
									<span class="form_tips red">给打印机取个名称方便菜品在选择打印机更直观</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>绑定手机号</label></label>
									<input class="col-sm-2" size="20" name="mp" id="mp" type="text" value="{pigcms{$village_printer['mp']}"/>
									<span class="form_tips red">绑定手机号和绑定账号只能填写一个（【底部有终端号】需填写手机号，【底部无终端号】需填写绑定账号）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>绑定账号</label></label>
									<input class="col-sm-2" size="20" name="username" id="username" type="text" value="{pigcms{$village_printer['username']}"/>
									<span class="form_tips red">绑定手机号和绑定账号只能填写一个（【底部有终端号】需填写手机号，【底部无终端号】需填写绑定账号）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">终端号</label></label>
									<input class="col-sm-2" size="20" name="mcode" id="mcode" type="text" value="{pigcms{$village_printer['mcode']}"/>
									<span class="form_tips">【底部无终端号】的打印机点击打印机下面的黑色小按钮查看, 【底部有终端号】的打印机在打印机底部查看</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">密钥</label></label>
									<input class="col-sm-2" size="20" name="mkey" id="mkey" type="text" value="{pigcms{$village_printer['mkey']}"/>
									<span class="form_tips">【底部无终端号】的打印机在注册页面查看, 【底部有终端号】的打印机在打印机底部查看</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="sort">默认打印的二维码</label></label>
									<input class="col-sm-2" size="20" name="qrcode" id="qrcode" type="text" value="{pigcms{$village_printer['qrcode']}"/>
									<span class="form_tips">默认打印的二维码(只能是微信二维码或是支付宝二维码的链接)【注：老的打印机不支持打印二维码，新的机器支持二维码打印】</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">打印份数</label></label>
									<input class="col-sm-1" maxlength="2" name="count" id="count" type="text" value="{pigcms{$village_printer['count']|default='1'}"/>
									<span class="form_tips">每个订单打印几份（最多100）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="FoodType_week">纸张类型</label>
									<div class="col-sm-10" style="margin-top:5px;padding-left:0;">
										<div style="float:left;font-size:16px;margin-right:30px;">
											<label><input type="radio" value="0" name="paper" <if condition="$village_printer['paper'] eq 0">checked="checked"</if>/>58mm</label>
										</div>
										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="radio" value="1" name="paper" <if condition="$village_printer['paper'] eq 1">checked="checked"</if>/>80mm</label>
										</div>
									</div>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1" for="FoodType_week">字体大小</label>
                                    <div class="col-sm-10" style="margin-top:5px;padding-left:0;">
                                        <div style="float:left;font-size:16px;margin-right:30px;">
                                            <label><input type="radio" value="0" name="is_big" <if condition="$village_printer['is_big'] eq 0">checked="checked"</if>/>默认</label>
                                        </div>
                                        <div style="width:80px;float:left;font-size:16px;">
                                            <label><input type="radio" value="1" name="is_big" <if condition="$village_printer['is_big'] eq 1">checked="checked"</if>/>中等</label>
                                        </div>
                                        <div style="width:180px;float:left;font-size:16px;">
                                            <label><input type="radio" value="2" name="is_big" <if condition="$village_printer['is_big'] eq 2">checked="checked"</if>/>最大</label>
                                        </div>
                                    </div>
                                </div>
								<!-- <div class="form-group">
									<label class="col-sm-1" for="FoodType_week">支持图片</label>
									<div class="col-sm-10" style="margin-top:5px;padding-left:0;">
										<div style="float:left;font-size:16px;margin-right:30px;">
											<label><input type="radio" value="1" name="image" <if condition="$village_printer['image'] eq 1">checked="checked"</if>/>支持</label>
										</div>
										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="radio" value="0" name="image" <if condition="$village_printer['image'] eq 0">checked="checked"</if>/>不支持</label>
										</div>
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-1" for="FoodType_week">打印类型</label>
									<div class="col-sm-10" style="margin-top:5px;padding-left:0;">
										<!-- <div style="width:180px;float:left;font-size:16px;">
											<label><input type="checkbox" value="0" name="paid[]" <if condition="in_array(0, $village_printer['paid'])">checked="checked"</if>/>现场缴费 </label>&nbsp;&nbsp;
										</div> -->

										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="checkbox" value="0" name="paid[]" onclick="return false;" checked="checked"/>现场缴费 </label>&nbsp;&nbsp;
										</div>

										<!-- <div style="width:180px;float:left;font-size:16px;">
											<label><input type="checkbox" value="1" name="paid[]" <if condition="in_array(1, $village_printer['paid'])">checked="checked"</if>/>支付成功后打印</label>&nbsp;&nbsp;
										</div>
										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="checkbox" value="2" name="paid[]" <if condition="in_array(2, $village_printer['paid'])">checked="checked"</if>/>验证成功后打印</label>&nbsp;&nbsp;
										</div>
										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="checkbox" value="3" name="paid[]" <if condition="in_array(3, $village_printer['paid'])">checked="checked"</if>/>退款成功后打印</label>&nbsp;&nbsp;
										</div>
                                        <div style="width:180px;float:left;font-size:16px;">
                                            <label><input type="checkbox" value="4" name="paid[]" <if condition="in_array(4, $village_printer['paid'])">checked="checked"</if>/>打印总单</label>&nbsp;&nbsp;
                                        </div> -->
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-1" for="FoodType_week">主打印机</label>
									<div class="col-sm-10" style="margin-top:5px;padding-left:0;">
										<div style="float:left;font-size:16px;margin-right:30px;">
											<label><input type="radio" value="1" name="is_main" <if condition="$village_printer['is_main']">checked="checked"</if>/>是</label>
										</div>
										<div style="width:180px;float:left;font-size:16px;">
											<label><input type="radio" value="0" name="is_main" <if condition="$village_printer['is_main'] eq 0">checked="checked"</if>/>否</label>
										</div>
									</div>
								</div> -->
								<if condition="$ok_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:blue;">{pigcms{$ok_tips}</span>				
									</div>
								</if>
								<if condition="$error_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:red;">{pigcms{$error_tips}</span>				
									</div>
								</if>
								<div class="clearfix form-actions">
									<if condition="in_array(13,$house_session['menus'])">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
									<else/>
									<button class="btn btn-info" type="submit" disabled="disabled">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									</if>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<include file="Public:footer"/>
