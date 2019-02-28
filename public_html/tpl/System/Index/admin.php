<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Index/saveAdmin')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">账号</th>
				<td><input type="text" class="input fl" name="account" id="account" size="20" placeholder="请输入账号" validate="maxlength:30,required:true" value="{pigcms{$admin['account']}"/></td>
			</tr>
			<tr>
				<th width="80">密码</th>
				<td><input type="password" class="input fl" name="pwd" id="pwd" size="20" placeholder=""  tips="添加时候必填，在修改时候不填写证明不修改密码" autocomplete="off"/></td>
			</tr>
			<tr>
				<th width="80">真实姓名</th>
				<td><input type="text" class="input fl" name="realname" id="realname" size="20" placeholder="" tips="填写该账号使用者的真实姓名" value="{pigcms{$admin['realname']}"/></td>
			</tr>
			<if condition="$config.international_phone eq 1">
			<tr>
				<th width="80">区号</th>
				<td>
					<select name="phone_country_type" id="phone_country_type" class="fl" style="margin-right:5px;">
					
					<option value="">请选择国家...,choose country</option>
				  <option value="86" <if condition="$admin.phone_country_type eq 1">selected</if>>+86 中国 China</option>
				  <option value="1" <if condition="$admin.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
					</select>
				
			</tr>
			</if>
			<tr>
				<th width="80">电话</th>
				<td>
				
				<input type="text" class="input fl" name="phone" size="20" placeholder=""  value="{pigcms{$admin['phone']}"/></td>
			</tr>
			<tr>
				<th width="80">EMAIL</th>
				<td><input type="text" class="input fl" name="email" size="20" value="{pigcms{$admin['email']}"/></td>
			</tr>
			<tr>
				<th width="80">QQ</th>
				<td><input type="text" class="input fl" name="qq" size="20" value="{pigcms{$admin['qq']}"/></td>
			</tr>
			<if condition="$config.open_admin_code eq 1">
			<tr>
				<th width="80">邀请码</th>
				<td><input type="text" class="input fl" name="invit_code" size="20" value="{pigcms{$admin['invit_code']}"/></td>
			</tr>
			</if>
			<if condition="$config.open_extra_price  eq 1">
			<tr>
				<th width="80">区域管理员{pigcms{$config.score_name}结算比例</th>
				<td><input type="text" class="input fl" name="score_percent" size="20"  validate="required:true,min:0,max:100" value="{pigcms{$admin['score_percent']|floatval}"/></td>
			</tr>
			</if>
			
			<if condition="$system['area_type'] lt 3">
			<tr>
				<th width="160">是否是区域管理员</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['open_admin_area'] eq 1">selected</if>"><span>是</span>
					<input type="radio" name="open_admin_area" value="1" <if condition="$admin['open_admin_area'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable" ><label class="cb-disable  <if condition="$admin['open_admin_area'] eq 0">selected</if>"><span>否</span>
					<input type="radio" name="open_admin_area" value="0" <if condition="$admin['open_admin_area'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后请设置管理员所在区域"></em>
				</td>
			</tr>
			</if>
				<if condition="$system['area_type'] lt 3">
			<tr class="open_admin_area" <if condition="$admin.open_admin_area eq 0">style="display:none"</if>>
				<th width="160">管理员所在区域</th>
				<!--<td id="choose_cityarea1" province_id="{pigcms{$admin.area.1}" city_id="{pigcms{$admin.area.2}" area_id="{pigcms{$admin.area.3}" circle_id="-1"></td>-->
				<td id="choose_pca" province_idss="{pigcms{$admin.area.1}" city_idss="{pigcms{$admin.area.2}" area_id="{pigcms{$admin.area.3}" style="display:inline"></td>
			</tr>
			</if>
			<tr>
				<th width="80">是否接收商户申请提现/商户欠费时提醒</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['withdraw_notice'] eq 1">selected</if>"><span>接收</span><input type="radio" name="withdraw_notice" value="1" <if condition="$admin['withdraw_notice'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$admin['withdraw_notice'] eq 0">selected</if>"><span>不接收</span><input type="radio" name="withdraw_notice" value="0" <if condition="$admin['withdraw_notice'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后，商户提现与商户欠费会通过微信模板消息提醒到该管理员，区域管理员则接受自己区域下的商户提醒"></em>
				</td>
			</tr>
			
			<tr>
				<th width="80">是否接收商户申请注册时提醒</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['mer_reg_notice'] eq 1">selected</if>"><span>接收</span><input type="radio" name="mer_reg_notice" value="1" <if condition="$admin['mer_reg_notice'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$admin['mer_reg_notice'] eq 0">selected</if>"><span>不接收</span><input type="radio" name="mer_reg_notice" value="0" <if condition="$admin['mer_reg_notice'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后，商户注册会通过微信模板消息提醒到该管理员，区域管理员只能接收到自己区域下的商户申请提现"></em>
				</td>
			</tr>
			
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['status'] eq 1">selected</if>"><span>显示</span><input type="radio" name="status" value="1" <if condition="$admin['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$admin['status'] eq 0">selected</if>"><span>隐藏</span><input type="radio" name="status" value="0" <if condition="$admin['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
			
			<tr>
				<th width="80">权限</th>
				<td>
					
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Index/menu',array('admin_id'=>$admin['id']))}','设置管理员使用权限',700,500,true,false,false,editbtn,'menu',true);" style="color:blue">设置管理员使用权限</a>
					
				</td>
				
			</tr>
			
			<tr>
				<th width="80">权限分组</th>
				<td>
					
					<select name="authority_group_id" tips="权限分组">
						<option value="0" <if condition="empty($admin['authority_group_id'])">selected="selected"</if>>不选</option>	
						<volist name="authority_group" id="vo">
							<option value="{pigcms{$vo.id}" <if condition="$admin['authority_group_id'] eq $vo['id']">selected="selected"</if>>{pigcms{$vo.name}</option>								
						</volist>
					</select>
				</td>
				
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script type="text/javascript">
		get_first_word('area_name','area_url','first_pinyin');
		
		$('input[name="open_admin_area"]').click(function(){
			var sub = $(this);
			if(sub.val()==1){
				$('.open_admin_area').show();
			}else{
				$('.open_admin_area').hide();
			}
		});
	</script>
	<if condition="$system['area_type'] lt 3">
	<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js?22"></script>
	</if>
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>