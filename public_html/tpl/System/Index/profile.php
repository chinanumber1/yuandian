<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<a href="{pigcms{:U('Index/profile')}" class="on">修改资料</a>
		</div>
		<form method="post" id="myform" action="{pigcms{:U('amend_profile')}" refresh="true" onclick='javascript:return submitcheck();'>
			<input type="hidden" class="input-text" name="system_menu" value=""/>
			<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
				<tr>
					<th  width="120">帐号：</td>
					<td>{pigcms{$admin.account}</th>
				</tr>
				<tr>
					<th  width="120">真实姓名：</th>
					<td><input type="text" class="input-text"  name="realname" value="{pigcms{$admin.realname}" validate="required:true" /></td>
				</tr>
				<tr>
					<th>邮箱：</th>
					<td><input type="text" class="input-text"  name="email" value="{pigcms{$admin.email}" validate="required:true,email:true,minlength:1,maxlength:40" /></td>
				</tr>
				<tr>
					<th>Q Q：</th>
					<td><input type="text" class="input-text"  name="qq" value="{pigcms{$admin.qq}" validate="required:true,qq:true" /></td>
				</tr>
				<if condition="$config.international_phone eq 1">
				<tr>
					<th>区号：</th>
					<td>
					
						<select name="phone_country_type" id="phone_country_type" class="fl" style="margin-right:5px;">
					
					<option value="">请选择国家...,choose country</option>
				  <option value="86" <if condition="$admin.phone_country_type eq 1">selected</if>>+86 中国 China</option>
				  <option value="1" <if condition="$admin.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
					</select>
					</td>
				</tr>
				</if>

				<tr>
					<th>手机号码：</th>
					<td>
				
					<input type="text" class="input-text"  name="phone" value="{pigcms{$admin.phone}"  validate="required:true,mobile:true" /></td>
				</tr>
				<tr>
					<th>菜单排序：</th>
					<td>
						描述：此功能只对当前用户生效。数字越大，排序越靠前。<br/>
						<volist name="system_menu" id="vv" key="k">
							<div style="margin-top:10px;width:30%;float:left;">{pigcms{$vv['name']} <input type="number" min="0" class="input-text input1" name="{pigcms{$vv.id}" value="{pigcms{$sort_menus_son[$vv['id']]}" /></div>
							<if condition="$k%3 eq 0"><br /></if>
						</volist>
					</td>
				</tr>
			</table>
			<div class="btn">
				<input TYPE="submit" id="submit" name="dosubmit" value="提交" class="button" />
				<input type="reset" value="取消" class="button" />
			</div>
		</form>
	</div>
	<script type="text/javascript">
		function submitcheck(){
			var system_menu	=	'';
			$(".input1").each(function(){
				if(this.value){
					system_menu	+=	this.name+','+this.value+';';
				}
		    });
		    system_menu=system_menu.substring(0,system_menu.length-1);
			$("input:[name$='system_menu']").val(system_menu);
		}
	</script>
<include file="Public:footer"/>