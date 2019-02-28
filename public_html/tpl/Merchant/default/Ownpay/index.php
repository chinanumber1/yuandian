<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Ownpay/index')}">自有支付配置</a>
			</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="alert alert-info" style="margin:10px 0;">
			<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
			开启自有支付，则用户会优先使用自有支付！例如配置了微信支付，则支付时显示的“微信支付”会调用自有支付。<br/>
			【注】开启了自有支付后店铺就不能使用{pigcms{$config['deliver_name']}了！！！
		</div>
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">	
							<if condition="$config['pay_weixin_open']">
								<li class="active">
									<a data-toggle="tab" href="#weixin">微信支付</a>
								</li>
							</if>
							<li <if condition="!$hasBind && !$config['pay_weixin_open']"> class="active"</if>>
								<a data-toggle="tab" href="#tenpay">财付通支付</a>
							</li>
							<li>
								<a data-toggle="tab" href="#yeepay">银行卡支付(易宝支付)</a>
							</li>
							<li>
								<a data-toggle="tab" href="#allinpay">银行卡支付(通联支付)</a>
							</li>
							<li>
								<a data-toggle="tab" href="#chinabank">银行卡支付(网银在线)</a>
							</li>
							<li>
								<a data-toggle="tab" href="#alipayh5">支付宝</a>
							</li>
							<li>
								<a data-toggle="tab" href="#alipay_app">支付宝APP</a>
							</li>
							<if condition="$config['pay_weifutong_open']">
								<li>
									<a data-toggle="tab" href="#weifutong">{pigcms{$config.pay_weifutong_alias_name}</a>
								</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_form">
						<div class="tab-content">
							<php>if($config['pay_weixin_open']){</php>
								<div id="weixin" class="tab-pane active"  >
										<php>if($config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && !empty($now_merchant['sub_mch_id']) ){</php>
										<div class="form-group">
											<span class="form_tips">您已经已经是平台微信服务商的子商户，微信支付将直接支付到您的子商户账号，不需要配置自有支付，如果需要配置自有支付，请向平台申请关闭子商户支付功能</span>
										</div>
										<php>}else{</php>
										<if condition="!$hasBind">
											<div id="weixin" class="tab-pane active" >
												<div class="form-group"    style=" margin-left: 40px;<if condition="!empty($now_merchant['sub_mch_id'])">display:none;</if>">
													<a class="btn btn-success cert-setting-btn js-wxauth-btn" href="{pigcms{$url}"><i class="ace-icon fa fa-wechat"></i>请先绑定微信公众号，立即设置</a>
												</div>
											</div>
										<else />
											<div class="form-group" <if condition="!empty($now_merchant['sub_mch_id'])">style="display:none;"</if>>
												<label class="col-sm-1" for="wxpay_open">是否开启</label>
												<select name="weixin[open]" id="wxpay_open">
													<option value="0" <if condition="$ownpay['weixin']['open'] eq 0">selected="selected"</if>>关闭</option>
													<option value="1" <if condition="$ownpay['weixin']['open'] eq 1">selected="selected"</if>>开启</option>
												</select>
											</div>
											<div class="form-group">
												<label class="col-sm-1"><label for="pay_weixin_appid">Appid</label></label>
												<input class="col-sm-2" size="20" name="weixin[pay_weixin_appid]" id="pay_weixin_appid" type="text" value="{pigcms{$ownpay.weixin.pay_weixin_appid}"/>
											</div>
											<div class="form-group">
												<label class="col-sm-1"><label for="pay_weixin_mchid">Mchid</label></label>
												<input class="col-sm-2" size="20" name="weixin[pay_weixin_mchid]" id="pay_weixin_mchid" type="text" value="{pigcms{$ownpay.weixin.pay_weixin_mchid}"/>
											</div>
											<div class="form-group">
												<label class="col-sm-1"><label for="pay_weixin_key">Key</label></label>
												<input class="col-sm-2" size="20" name="weixin[pay_weixin_key]" id="pay_weixin_key" type="text" value="{pigcms{$ownpay.weixin.pay_weixin_key}"/>
											</div>
											<div class="form-group" style="display:none;">
												<label class="col-sm-1"><label for="pay_weixin_appsecret">Key</label></label>
												<input class="col-sm-2" size="20" name="weixin[pay_weixin_appsecret]" id="pay_weixin_appsecret" type="text" value="123456"/>
											</div>
											<div class="form-group">
												<label class="col-sm-1"><label for="pay_weixin_client_cert">微信支付证书</label></label>
												<input type="text"  style="width:200px;" name="pay_weixin_client_cert" id="pay_weixin_client_cert" class="input input-file" value="{pigcms{$ownpay.weixin.pay_weixin_client_cert}" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传文件</a><span class="form_tips">微信支付证书，在微信商家平台中可以下载！文件名一般为apiclient_cert.pem</span>
											</div>
											<div class="form-group">
												<label class="col-sm-1"><label for="pay_weixin_client_key">微信支付证书密钥</label></label>
												<input type="text"  style="width:200px;" name="pay_weixin_client_key" id="pay_weixin_client_key" class="input input-file" value="{pigcms{$ownpay.weixin.pay_weixin_client_key}" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传文件</a><span class="form_tips">微信支付证书密钥，在微信商家平台中可以下载！文件名一般为apiclient_key.pem</span>
											</div>
										</if>
										<php>}</php>
								</div>
							<php>}</php>
							<div id="tenpay" class="tab-pane <if condition="!$config['pay_weixin_open']">active</if>"  >
								<div class="form-group">
									<label class="col-sm-1" for="tenpay_open">是否开启</label>
									<select name="tenpay[open]" id="tenpay_open">
										<option value="0" <if condition="$ownpay['tenpay']['open'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$ownpay['tenpay']['open'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_tenpay_partnerid">商户号</label></label>
									<input class="col-sm-2" size="20" name="tenpay[pay_tenpay_partnerid]" id="pay_tenpay_partnerid" type="text" value="{pigcms{$ownpay.tenpay.pay_tenpay_partnerid}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_tenpay_partnerkey">密钥</label></label>
									<input class="col-sm-2" size="20" name="tenpay[pay_tenpay_partnerkey]" id="pay_tenpay_partnerkey" type="text" value="{pigcms{$ownpay.tenpay.pay_tenpay_partnerkey}"/>
								</div>
							</div>
							<div id="yeepay" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" for="yeepay_open">是否开启</label>
									<select name="yeepay[open]" id="yeepay_open">
										<option value="0" <if condition="$ownpay['yeepay']['open'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$ownpay['yeepay']['open'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_yeepay_merchantaccount">商户编号</label></label>
									<input class="col-sm-2" size="20" name="yeepay[pay_yeepay_merchantaccount]" id="pay_yeepay_merchantaccount" type="text" value="{pigcms{$ownpay.yeepay.pay_yeepay_merchantaccount}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_yeepay_merchantprivatekey">商户私钥</label></label>
									<input class="col-sm-2" size="20" name="yeepay[pay_yeepay_merchantprivatekey]" id="pay_yeepay_merchantprivatekey" type="text" value="{pigcms{$ownpay.yeepay.pay_yeepay_merchantprivatekey}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_yeepay_merchantpublickey">商户公钥</label></label>
									<input class="col-sm-2" size="20" name="yeepay[pay_yeepay_merchantpublickey]" id="pay_yeepay_merchantpublickey" type="text" value="{pigcms{$ownpay.yeepay.pay_yeepay_merchantpublickey}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_yeepay_yeepaypublickey">易宝公钥</label></label>
									<input class="col-sm-2" size="20" name="yeepay[pay_yeepay_yeepaypublickey]" id="pay_yeepay_yeepaypublickey" type="text" value="{pigcms{$ownpay.yeepay.pay_yeepay_yeepaypublickey}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_yeepay_productcatalog">商品类别码</label></label>
									<input class="col-sm-2" size="20" name="yeepay[pay_yeepay_productcatalog]" id="pay_yeepay_productcatalog" type="text" value="{pigcms{$ownpay.yeepay.pay_yeepay_productcatalog}"/>
								</div>
							</div>
							<div id="allinpay" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" for="allinpay_open">是否开启</label>
									<select name="allinpay[open]" id="allinpay_open">
										<option value="0" <if condition="$ownpay['allinpay']['open'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$ownpay['allinpay']['open'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_allinpay_merchantid">商户号</label></label>
									<input class="col-sm-2" size="20" name="allinpay[pay_allinpay_merchantid]" id="pay_allinpay_merchantid" type="text" value="{pigcms{$ownpay.allinpay.pay_allinpay_merchantid}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_allinpay_merchantkey">MD5 KEY</label></label>
									<input class="col-sm-2" size="20" name="allinpay[pay_allinpay_merchantkey]" id="pay_allinpay_merchantkey" type="text" value="{pigcms{$ownpay.allinpay.pay_allinpay_merchantkey}"/>
								</div>
							</div>
							<div id="chinabank" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" for="chinabank_open">是否开启</label>
									<select name="chinabank[open]" id="chinabank_open">
										<option value="0" <if condition="$ownpay['chinabank']['open'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$ownpay['chinabank']['open'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_chinabank_account">商户号</label></label>
									<input class="col-sm-2" size="20" name="chinabank[pay_chinabank_account]" id="pay_chinabank_account" type="text" value="{pigcms{$ownpay.chinabank.pay_chinabank_account}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_chinabank_key">MD5 KEY</label></label>
									<input class="col-sm-2" size="20" name="chinabank[pay_chinabank_key]" id="pay_chinabank_key" type="text" value="{pigcms{$ownpay.chinabank.pay_chinabank_key}"/>
								</div>
							</div>
							<if condition="$config['pay_weifutong_open']">
								<div id="weifutong" class="tab-pane">
									<div class="form-group">
										<label class="col-sm-1" for="weifutong_open">是否开启</label>
										<select name="weifutong[open]" id="weifutong_open">
											<option value="0" <if condition="$ownpay['weifutong']['open'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$ownpay['weifutong']['open'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_weifutong_mchid">商户号</label></label>
										<input class="col-sm-2" size="20" name="weifutong[pay_weifutong_mchid]" id="pay_weifutong_mchid" type="text" value="{pigcms{$ownpay.weifutong.pay_weifutong_mchid}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_weifutong_key">支付密钥</label></label>
										<input class="col-sm-2" size="20" name="weifutong[pay_weifutong_key]" id="pay_weifutong_key" type="text" value="{pigcms{$ownpay.weifutong.pay_weifutong_key}"/>
									</div>
								</div>
							</if>
							<div id="alipayh5" class="tab-pane">
									<div class="form-group">
										<label class="col-sm-1" for="alipay_open">是否开启</label>
										<select name="alipayh5[open]" id="alipay_open">
											<option value="0" <if condition="$ownpay['alipayh5']['open'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$ownpay['alipayh5']['open'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipayh5_appid">PID</label></label>
										<input class="col-sm-2" size="20" name="alipayh5[pay_alipayh5_appid]" id="pay_alipayh5_appid" type="text" value="{pigcms{$ownpay.alipayh5.pay_alipayh5_appid}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipayh5_merchant_private_key">商户私钥</label></label>
										<input class="col-sm-2" size="20" name="alipayh5[pay_alipayh5_merchant_private_key]" id="pay_alipayh5_merchant_private_key" type="text" value="{pigcms{$ownpay.alipayh5.pay_alipayh5_merchant_private_key}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipayh5_public_key">支付宝公钥</label></label>
										<input class="col-sm-2" size="20" name="alipayh5[pay_alipayh5_public_key]" id="pay_alipayh5_public_key" type="text" value="{pigcms{$ownpay.alipayh5.pay_alipayh5_public_key}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1" for="pay_alipayh5_sign_type">加密方式</label>
										<select name="alipayh5[pay_alipayh5_sign_type]" id="pay_alipayh5_sign_type">
											<option value="0" <if condition="$ownpay['alipayh5']['pay_alipayh5_sign_type'] eq 0">selected="selected"</if>>RSA</option>
											<option value="1" <if condition="$ownpay['alipayh5']['pay_alipayh5_sign_type'] eq 1">selected="selected"</if>>RSA2</option>
										</select>
									</div>
								</div>
								
								<div id="alipay_app" class="tab-pane">
									<div class="form-group">
										<label class="col-sm-1" for="alipay_app_open">是否开启</label>
										<select name="alipay_app[open]" id="alipay_app_open">
											<option value="0" <if condition="$ownpay['alipay_app']['open'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$ownpay['alipay_app']['open'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipay_app_count">商户帐号</label></label>
										<input class="col-sm-2" size="20" name="alipay_app[pay_alipay_app_count]" id="pay_alipay_app_count" type="text" value="{pigcms{$ownpay.alipay_app.pay_alipay_app_count}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipay_app_pid">PID</label></label>
										<input class="col-sm-2" size="20" name="alipay_app[pay_alipay_app_pid]" id="pay_alipay_app_pid" type="text" value="{pigcms{$ownpay.alipay_app.pay_alipay_app_pid}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipay_app_private_key_android">商户私钥android</label></label>
										<input class="col-sm-2" size="20" name="alipay_app[pay_alipay_app_private_key_android]" id="pay_alipay_app_private_key_android" type="text" value="{pigcms{$ownpay.alipay_app.pay_alipay_app_private_key_android}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="pay_alipay_app_private_key_ios">商户私钥ios</label></label>
										<input class="col-sm-2" size="20" name="alipay_app[pay_alipay_app_private_key_ios]" id="pay_alipay_app_private_key_ios" type="text" value="{pigcms{$ownpay.alipay_app.pay_alipay_app_private_key_ios}"/>
									</div>
									<div class="form-group">
										<label class="col-sm-1"><label for="alipay_app_public_key">支付宝公钥</label></label>
										<input class="col-sm-2" size="20" name="alipay_app[alipay_app_public_key]" id="alipay_app_public_key" type="text" value="{pigcms{$ownpay.alipay_app.alipay_app_public_key}"/>
									</div>
									
								</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" id="save_btn">
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
#upload_pic_box img{width:100px;height:70px;}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.J_selectImage').click(function(){
				
					var upload_file_btn = $(this);
					editor.uploadJson = "/index.php?g=Index&c=Upload&a=ajax_upload_file&name="+upload_file_btn.siblings('.input-file').attr('name');
					editor.loadPlugin('insertfile', function(){
						editor.plugin.fileDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-file').val(url);
								editor.hideDialog();
							}
						});
					});
				

			});

		});
</script>
<script type="text/javascript">
$(function(){
	$('#add_form').submit(function(){
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('Ownpay/save')}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.reload();
			}else{
				alert(result.info);
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Group/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>
<include file="Public:footer"/>