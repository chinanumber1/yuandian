<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('User/amend')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.uid}<if condition="$now_user.spread gt 0">({pigcms{$now_user.spread_txt})</if></div></td>
				<th width="15%">微信唯一标识</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.openid}</div></td>
			<tr/>

			<tr>
				<th width="15%">昵称</th>
				<td width="35%"><input type="text" class="input fl" name="nickname" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.nickname}"/></td>
				
				<th width="15%">手机号</th>
				<td width="35%" <php>if(isset($config['not_o2o_demo'])){</php>style="display:none"<php>}</php>>
				<if condition="$config.international_phone eq 1">
					<select name="phone_country_type" id="phone_country_type" style="float:left;margin-right:5px;">
					<option value="86" <if condition="$now_user.phone_country_type eq 86">selected</if>>+86 中国 China</option>
					<option value="1" <if condition="$now_user.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
					</select>
				</if>
				<input type="text" class="input fl" name="phone" size="20" <if condition="!$config['international_phone']">validate="mobile:true"</if> value="{pigcms{$now_user.phone}" autocomplete="off"/></td>

			</tr>
			<tr>
				<th width="15%">密码</th>
				<td width="35%"><input type="password" class="input fl" name="pwd" size="20" value="" tips="不修改则不填写" autocomplete="new-password"/></td>
				<th width="15%">性别</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_user['sex'] eq 1">selected</if>"><span>男</span><input type="radio" name="sex" value="1"  <if condition="$now_user['sex'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_user['sex'] eq 2">selected</if>"><span>女</span><input type="radio" name="sex" value="2"  <if condition="$now_user['sex'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">姓名</th>
				<td width="35%"><input type="text" class="input fl" name="truename" size="20" validate="maxlength:20" value="{pigcms{$now_user.truename}"/></td>
				</tr>
			
			<tr>
				<th width="15%">省份</th>
				<td width="35%"><input type="text" class="input fl" name="province" size="20" validate="maxlength:20" value="{pigcms{$now_user.province}"/></td>
				<th width="15%">城市</th>
				<td width="35%"><input type="text" class="input fl" name="city" size="20" validate="maxlength:20" value="{pigcms{$now_user.city}"/></td>
			</tr>
			<tr>
				<th width="15%">QQ号</th>
				<td width="35%"><input type="text" class="input fl" name="qq" size="20" value="{pigcms{$now_user.qq}"/></td>
				<th width="15%">状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>正常</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
					   <if condition="$now_user['status'] eq 2">
						<span class="cb-disable"><label class="cb-disable selected"><span>未审核</span><input type="radio" name="status" value="2"  checked="checked"/></label></span>
						<elseif condition="$now_user['status'] eq 0" />				     
					     <span class="cb-disable"><label class="cb-disable selected"><span>禁用</span><input type="radio" name="status" value="0" checked="checked"/></label></span>
						 <else/>				     
					     <span class="cb-disable"><label class="cb-disable"><span>禁用</span><input type="radio" name="status" value="0"/></label></span>
						 </if>
				</td>
				</tr>
			 <if condition="isset($config['specificfield'])">
			 	<tr>
				<th width="15%">姓名</th>
				<td width="35%"><input type="text" class="input fl" name="truename" value="{pigcms{$now_user.truename}"/></td>
				<th width="15%">邮箱</th>
				<td width="35%"><input type="text" class="input fl" name="email" value="{pigcms{$now_user.email}"/></td>
			</tr>
				<tr>
				<th width="15%">地址</th>
				<td width="85%" colspan="3"><input type="text" class="input fl" name="youaddress" value="{pigcms{$now_user.youaddress}" style="width: 95%" /></td>
			</tr>
			 </if>
			<!--tr>
				<th width="15%">手机号验证</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$vo['is_check_phone'] eq 1"><font color="green">已验证</font><else/><font color="red">未验证</font></if></div></td>
				<th width="15%">关注微信号</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$vo['is_follow'] eq 1"><font color="green">已关注</font><else/><font color="red">未关注</font></if></div></td>
			</tr-->
			<tr>
				<th width="15%">注册时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.add_time|date='Y-m-d H:i:s',###}</div></td>
				<th width="15%">注册IP</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.add_ip|long2ip=###}</div></td>
			</tr>
			<tr>
				<th width="15%">最后访问时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.last_time|date='Y-m-d H:i:s',###}</div></td>
				<th width="15%">最后访问IP</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$now_user.last_ip|long2ip=###}</div></td>
			</tr>
			<tr> 
				<th width="15%">{pigcms{$config.score_name}</th>
				<td width="85%" colspan="3">
				<div style="height:30px;line-height:24px;">
				现在{pigcms{$config.score_name}：{pigcms{$now_user.score_count} &nbsp;&nbsp;&nbsp;&nbsp;
				 <php>if($can_recharge==1){</php>
				<select name="set_score_type"><option value="1">增加</option><option value="2">减少</option></select>&nbsp;&nbsp;
				<input type="text" class="input" name="set_score" size="10" validate="number:true" tips="此处填写增加或减少的{pigcms{$config.score_name}，不是将{pigcms{$config.score_name}变为此处填写的值"/>
				备注：<input type="text" class="input" name="score_remark" size="20"  tips="更改积分时请填写备注"/><php>}</php>
				</div>
				
				</td>
			</tr>
			
			 <tr <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo 'style="display:none"';}</php>>
				  <th width="160">是否积分冻结</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if($now_user['forzen_score']==1){</php>selected<php>}</php>"><span>是</span>
						<input type="radio" name="forzen_score" value="1" <php>if($now_user['forzen_score']==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if($now_user['forzen_score']==0){</php>selected<php>}</php>"><span>否</span>
						<input type="radio" name="forzen_score" value="0" <php>if($now_user['forzen_score']==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="冻结后用户不能领红包兑换余额，消费" class="notice_tips"></em></td>
				 </tr> 
		
			<tr >
				<th width="15%">余额</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">现在余额：￥{pigcms{$now_user.now_money|floatval=###} &nbsp;&nbsp;&nbsp;&nbsp;
				<php>if($can_recharge==1){</php><select name="set_money_type"><option value="1">增加</option><option value="2">减少</option></select>&nbsp;&nbsp;<input type="text" class="input" name="set_money" size="10" validate="number:true" tips="此处填写增加或减少的额度，不是将余额变为此处填写的值"/>
				备注：<input type="text" class="input" name="money_remark" size="20"  tips="更改余额时请填写备注"/><php>}</php>
				</div></td>
			</tr>
			<tr <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo 'style="display:none"';}</php>>
				<th width="15%">余额冻结</th>
				<td width="85%" colspan="3">
				<div style="height:30px;line-height:24px;"><input type="text" class="input fl" name="frozen_money" size="10" validate="number:true,min:0" value="{pigcms{$now_user.frozen_money}"  tips="冻结金额"/>
				</div></td>
			</tr>
			<tr <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo 'style="display:none"';}</php>>
				<th width="15%">冻结时间</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">
				冻结时间：
				<input type="text" class="input-text" name="frozen_time" style="width:120px;" id="d4311"  value="<if condition="$now_user.frozen_time gt 0">{pigcms{$now_user.frozen_time}</if>" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>	
				解冻时间：
				<input type="text" class="input-text" name="free_time" style="width:120px;" id="d4311" value="<if condition="$now_user.free_time gt 0">{pigcms{$now_user.free_time}</if>" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" tips="冻结时间"/>
				</div></td>
			</tr>
			<tr <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo 'style="display:none"';}</php>>
				<th width="15%">冻结理由</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">
				<input type="text" class="input" name="frozen_reason" size="40" value="{pigcms{$now_user.frozen_reason}" tips="冻结理由"/>
				</div></td>
			</tr>
			
			<tr>
				<th width="15%">实体卡</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">实体卡卡号<input type="text" class="input" name="cardid" size="16"  value="{pigcms{$now_user.cardid}" validate="number:true" tips="绑定后不能更改" readOnly/>实体卡初始余额<input type="text" class="input" name="balance_money" size="10" value="{pigcms{$balance_money}" validate="number:true" tips="实体卡绑定时的初始金额，一旦绑定不能更改" readOnly/></div></td>
			</tr>
			<tr>
				<th width="15%">等级</th>
				<td width="85%" colspan="3">
				<div style="height:30px;line-height:24px;">现在等级：<php>if(isset($levelarr[$now_user['level']])){ echo $levelarr[$now_user['level']]['lname'];}else{echo '暂无等级';}</php> &nbsp;&nbsp;&nbsp;&nbsp;
				<if condition="!empty($levelarr)">
				请设定等级：&nbsp;&nbsp; 
				<select name="level" style="width:100px;">
				<option value="0">无</option>
				<volist name="levelarr" id="vo">
				<option value="{pigcms{$vo['level']}" <if condition="$now_user['level'] eq $vo['level']"> selected="selected"</if>>{pigcms{$vo['lname']}</option>
				</volist>
				</select>
				</if>
				&nbsp;&nbsp;</div>
				</td>
			</tr>
			<tr>
				<th width="15%">记录表</th>
				<td width="85%" colspan="3">
					<div style="height:30px;line-height:24px;">
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$now_user['uid']))}','余额记录列表',680,560,true,false,false,null,'money_list',true);">余额记录</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/score_list',array('uid'=>$now_user['uid']))}','{pigcms{$config.score_name}记录列表',680,560,true,false,false,null,'score_list',true);">{pigcms{$config.score_name}记录</a>
					</div>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>