<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/merchant')}">商家设置</a>
			</li>
			<li class="active">商家设置</li>
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
								<a data-toggle="tab" href="#txtstore">商家描述</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtpwd">修改密码</a>
							</li>
							<if condition="$config.open_merchant_change_phone eq 1">
							<li>
								<a data-toggle="tab" href="#txtPhone">修改手机</a>
							</li>
							</if>
							<li>
								<a data-toggle="tab" href="#bindUser">管理账号</a>
							</li>
							<li style="display:none;">
								<a data-toggle="tab" href="#AllinyunUser">云商通</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>商户帐号</label></label>
									<span style="line-height:32px;color:#939192;">{pigcms{$now_merchant.account}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>商户名称</label></label>
									<span style="line-height:32px;color:#939192;">{pigcms{$now_merchant.name}</span>
								</div>
								<if condition="$config.open_admin_code eq 1">
								<div class="form-group">
									<label class="col-sm-1"><label>邀请码</label></label>
									<input class="col-sm-2" size="20" name="invit_code" value="{pigcms{$now_merchant.invit_code}" id="invit_code" type="text"/>
									
								</div>
								</if>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">联系电话</label></label>
									<input class="col-sm-2" size="20" name="phone" value="{pigcms{$now_merchant.phone}" id="phone" type="text" <if condition="$config.open_merchant_change_phone eq 1">readOnly</if> />
									<span class="form_tips">多个手机号码时，在修改手机号时将使用第一个手机号发送验证码且只可修改第一个手机号，建议只填写一个手机号</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">商家邮箱</label></label>
									<input class="col-sm-2" size="20" name="email" value="{pigcms{$now_merchant.email}" id="email" type="text"/>
									<span class="form_tips">可选，建议填写</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>{pigcms{$config.group_alias_name}快递收货超时时间</label></label>
									<input class="col-sm-2" size="20" name="group_express_outtime" value="{pigcms{$now_merchant.group_express_outtime}" type="text"/><span class="form_tips">0为永不超时，1为一天后超时并确认消费。（店员发货后开始计时）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="is_offline">扫描团购商品二维码是否发送其他推荐</label>
									<select name="scan_group_show_other" id="is_offline">
										<option value="1" <if condition="$now_merchant.scan_group_show_other eq 1">selected="selected"</if>>推荐</option>
										<option value="0" <if condition="$now_merchant.scan_group_show_other eq 0">selected="selected"</if>>不推荐</option>
									</select>
									<span class="form_tips">扫描每个团购商品的二维码时，是否将其他推荐信息发送给用户，不推荐则每次只发送该团购商品信息</span>
								</div>
								<if condition="$pay_offline_open eq 0 AND $now_merchant.is_close_offline eq 1">
									<div class="form-group">
										<label class="col-sm-1">线下支付的权限</label>
										<label class="col-sm-2" style="color: red;">系统禁止您使用线下支付的权限</label>
									</div>
								<elseif condition="$pay_offline_open eq 1" />
									<div class="form-group">
										<label class="col-sm-1" for="is_offline">线下支付的权限</label>
										<select name="is_offline" id="is_offline">
											<option value="0" <if condition="$now_merchant.is_offline eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_merchant.is_offline eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label>微官网点击量</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.hits}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								
								
								<div class="form-group">
									<label class="col-sm-1">商家LOGO</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectLogo">上传图片</a>
									<span class="form_tips">商家LOGO将显示在商家会员卡，以及用户在微信分享网页时会分享此图。建议上传200*200 或 宽高相同的图片</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">LOGO预览</label>
									<div id="upload_pic_box">
										<ul id="upload_logo_ul">
											<if condition="$now_merchant['logo']">
												<li class="upload_pic_li"><img src="{pigcms{$now_merchant.logo}"/><input type="hidden" name="logo" value="{pigcms{$now_merchant.logo}"/></li>
											</if>
										</ul>
									</div>
								</div>
							</div>
							<div id="txtstore" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">商家描述</label>
									<textarea class="col-sm-5" rows="5" name="txt_info">{pigcms{$now_merchant.txt_info}</textarea>
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
											<volist name="now_merchant['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<div id="txtpwd" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1"><label for="email">原密码</label></label>
									<input class="col-sm-2" size="20" name="old_pass" type="password"/>
									<span class="form_tips">不修改密码可不填写</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">新密码</label></label>
									<input class="col-sm-2" size="20" name="new_pass" type="password"/>
									<span class="form_tips">不修改密码请留空，最少6个字符</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">确认密码</label></label>
									<input class="col-sm-2" size="20" name="re_pass" type="password"/>
									<span class="form_tips">请再输入一次上面的新密码，以便确保输对了</span>
								</div>
							</div>
							<php>if($config['open_merchant_change_phone']== 1){</php>
								<div id="txtPhone" class="tab-pane">
									<div class="form-group">
										<label class="col-sm-1"><label for="email">原手机</label></label>
										<php>$phone  = explode(' ',$now_merchant['phone']);$phone = $phone[0];</php>
										<input class="col-sm-2" size="20" name="old_phone" type="tel" value="{pigcms{$phone}" readOnly />
										
									</div>
									<if condition="$config.open_merchant_reg_sms eq 1">
										<div class="form-group">
											<label class="col-sm-1"><label for="email">验证码</label></label>
											<input class="col-sm-2" size="20" name="SmsCode" type="text" value=""/>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="javascript:void(0)" onclick="sendsms(this)" class="btn btn-sm btn-success">发送验证码</a>
										</div>
									</if>
									<div class="form-group">
										<label class="col-sm-1"><label for="email">新手机</label></label>
										<if condition="$config.international_phone eq 1">
											<select name="phone_country_type" id="phone_country_type" style="height:34px;float:left;margin-right:5px;">
											<option value="86" <if condition="$now_merchant.phone_country_type eq 86">selected</if>>+86 中国 China</option>
											<option value="1" <if condition="$now_merchant.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
											</select>
										</if>
										<input class="col-sm-2" size="20" name="new_phone" type="tel"/>
										<span class="form_tips">不修改手机留空</span>
									</div>
									
									<if condition="$config.open_merchant_reg_sms eq 1">
										<div class="form-group">
											<label class="col-sm-1"><label for="email">验证码</label></label>
											<input class="col-sm-2" size="20" name="SmsCode2" type="text" value=""/>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="javascript:void(0)" onclick="sendsms2(this)" class="btn btn-sm btn-success">发送验证码</a>
										</div>
									</if>
									
								</div>
										<php>}</php>
							<div id="bindUser" class="tab-pane">
								<div class="form-group" id="has_user" <if condition="!$user">style="display:none"</if>>
									<label class="col-sm-1">绑定微信用户</label>
									<label class="col-sm-1"><label for="email" id="bind_usernickname">{pigcms{$user['nickname']}</label></label>
									<a class="form_tips" id="delete_bind">解除绑定</a>
								</div>
								<div class="form-group" id="no_user" <if condition="$user">style="display:none"</if>>
									<label class="col-sm-1">绑定微信用户</label>
									<label class="col-sm-1"><a href="{pigcms{:U('Config/see_tmp_qrcode')}" class="see_qrcode">查看二维码</a></label>
									<span class="form_tips">绑定微信用户之后，收款后会通过微信公众号通知。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="open_money_tempnews">商家收款微信通知</label>
									<select name="open_money_tempnews" id="open_money_tempnews">
										<option value="0" <if condition="$now_merchant.open_money_tempnews eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$now_merchant.open_money_tempnews eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
							</div>
							
							<div id="AllinyunUser" class="tab-pane">
								
							
								<div class="form-group">
									<label class="col-sm-1" for="open_money_tempnews">云商通企业账户创建</label>
									<if condition="$deposit.bizUserId neq ''">
										云商通账号：{pigcms{$deposit.bizUserId}
									<else />
										<a href="{pigcms{:U('SetAccountDeposit/createAllinyunAccount')}" class="btn btn-sm btn-success" id="Create_Allinyun">创建账号</a>
									</if>
									
									<if condition="!empty($deposit['phone'])">
										云商通绑定手机：{pigcms{$deposit.phone}
										<a href="{pigcms{:U('SetAccountDeposit/editphone')}" class="btn btn-sm btn-success" >重置手机</a>
									<else />
										<a href="{pigcms{:U('SetAccountDeposit/bindphone')}" class="btn btn-sm btn-success">绑定手机</a>
									</if>
									
									<a href="{pigcms{:U('SetAccountDeposit/submitVerify')}" class="btn btn-sm btn-success" id="Submit_verify"><if condition="$deposit.status eq 0">提交审核<elseif condition="$deposit.status eq 1" /> 查看企业信息<else />编辑企业信息</if></a>
									
									<a href="{pigcms{:U('SetAccountDeposit/signConnect')}&sign=1" class="btn btn-sm btn-success" id="signConnect"><if condition="$deposit.sign_status eq 0">签约电子协议<elseif condition="$deposit.sign_status eq 1" /> 已签约</if></a>
									
									<a href="{pigcms{:U('SetAccountDeposit/addBank')}" class="btn btn-sm btn-success" id="signConnect"><if condition="$deposit.bank_list neq ''">添加银行卡<else />银行卡列表</if></a>
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

#upload_pic_box #upload_logo_ul img{width:70px;height:70px;}

.small_btn{
margin-left: 10px;
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
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
background: #DDD;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
background: linear-gradient(top, #FFF, #DDD);
background: -moz-linear-gradient(top, #FFF, #DDD);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
text-shadow: 0px 1px 1px rgba(255, 255, 255, 1);
box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
-moz-transition: -moz-box-shadow linear .2s;
-webkit-transition: -webkit-box-shadow linear .2s;
transition: box-shadow linear .2s;
outline: 0;
}
.small_btn:active{
border-color: #1c6a9e;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee', endColorstr='#2288cc');
background: linear-gradient(top, #33bbee, #2288cc);
background: -moz-linear-gradient(top, #33bbee, #2288cc);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee), to(#2288cc));
}
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
		if($('#upload_pic_ul .upload_pic_li').size() >= 10){
			alert('最多上传10个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
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
		$.post("{pigcms{:U('Config/merchant')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Config/merchant')}";
			}else{
				alert(result.info);
			}
		})
		return false;
	});
	
	$('#indexsort_edit_btn').click(function(){
		$(this).prop('disabled',true).html('提交中...');
		$.post("{pigcms{:U('Config/merchant_indexsort')}",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
			alert('处理完成！正在刷新页面。');
			window.location.href = window.location.href;
		});
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Config/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	var loop_time = null, art_index = null;
	$(function(){
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					loop_time = setInterval(is_bind, 1500);
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'绑定管理账号的二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: function(){clearInterval(loop_time);},
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});

		$('#delete_bind').click(function(){
			art.dialog.confirm('你确定要解除绑定的用户吗？', function () {
				$.get("{pigcms{:U('Config/ajax_del_binduser')}", function(response){
					if (response.error_code) {
						art.dialog.tips(response.msg);
					} else {
						$('#has_user').hide();
						$('#no_user').show();
					}
				}, 'json');
			}, function () {});
		});
	});
	var countdown = 60;
	function sendsms(val){
		
			var phone =  $("input[name='old_phone']").val();
	
			if(phone==''){
				alert('手机号码不能为空！');
			}else{
				
				
				if(countdown==60){
					$.ajax({
						url: '{pigcms{:U('sendsms')}',
						type: 'POST',
						dataType: 'json',
						data: {phone:phone},
						success:function(date){
							if(date.status==0){
								// alert(date.info)
							}
						}

					});
				}
				if (countdown == 0) {
					val.removeAttribute("disabled");
					$(val).html("验证短信");
					countdown = 60;
					//clearTimeout(t);
				} else {
					val.setAttribute("disabled", true);
					$(val).html("重新发送(" + countdown + ")");
					countdown--;
					setTimeout(function() {
						sendsms(val);
					},1000)
				}
			}
		}
		var countdown2 = 60;
		function sendsms2(val){
			
		
			var phone = $("input[name='new_phone']").val()
			
		
			if(phone==''){
				alert('手机号码不能为空！');
			}else{
				
				
				if(countdown2==60){
					$.ajax({
						url: '{pigcms{:U('sendsms2')}',
						type: 'POST',
						dataType: 'json',
						data: {newphone:phone},
						success:function(date){
							if(date.status==0){
								// alert(date.info)
							}
						}

					});
				}
				if (countdown2 == 0) {
					val.removeAttribute("disabled");
					$(val).html("验证短信");
					countdown2 = 60;
					//clearTimeout(t);
				} else {
					val.setAttribute("disabled", true);
					$(val).html("重新发送(" + countdown2 + ")");
					countdown2--;
					setTimeout(function() {
						sendsms2(val);
					},1000)
				}
			}
		}
	function is_bind()
	{
		$.get("{pigcms{:U('Config/has_bind')}", function(response){
			if (response.error_code == false) {
				$('#bind_usernickname').html(response.nickname);
				$('#has_user').show();
				$('#no_user').hide();
				clearInterval(loop_time);
				art.dialog.list['handle'].close();
			}
		}, 'json');
	}
</script>
<include file="Public:footer"/>
