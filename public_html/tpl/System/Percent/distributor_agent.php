<include file="Public:header"/>
		
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Percent/distributor_agent')}" class="on">分销员/代理商设置</a>
				
				</ul>
			</div>
			 <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
				 <tr>
				  <th width="160">开启分销商代理商功能</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.open_distributor')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="open_distributor" value="1" <php>if(C('config.open_distributor')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.open_distributor')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="open_distributor" value="0" <php>if(C('config.open_distributor')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启分销商代理商功能" class="notice_tips"></em></td>
				 </tr>
				 
			     <tr>
				  <th width="160">成为分销员金额</th>
				  <td>
					<input type="text" class="input-text" name="buy_distributor_money" id="config_buy_distributor_money" value="{pigcms{:C('config.buy_distributor_money')}" size="10" validate="required:true,number:true,min:0" tips="成为分销员金额" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">成为代理商金额</th>
				  <td>
					<input type="text" class="input-text" name="buy_agent_money" id="config_buy_agent_money" value="{pigcms{:C('config.buy_agent_money')}" size="10" validate="required:true,number:true,min:0" tips="成为代理商金额" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">代理商最多推广商家</th>
				  <td>
					<input type="text" class="input-text" name="agent_spread_num" id="config_agent_spread_num" value="{pigcms{:C('config.agent_spread_num')}" size="10" validate="required:true,number:true,min:0" tips="代理商最多推广商家，0不限制" />
				  </td>
				 </tr>
				 
				  <tr>
				  <th width="160">分销员别名</th>
				  <td>
					<input type="text" class="input-text" name="distributor_alias_name" id="config_distributor_alias_name" value="{pigcms{:C('config.distributor_alias_name')}" size="10" validate="required:true," />
				  </td>
				 </tr>
				 
				  <tr>
				  <th width="160">代理商别名</th>
				  <td>
					<input type="text" class="input-text" name="agent_alias_name" id="config_agent_alias_name" value="{pigcms{:C('config.agent_alias_name')}" size="10" validate="required:true," />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">分销员有效期</th>
				  <td>
					<input type="text" class="input-text" name="distributor_effective_time" id="config_distributor_effective_time" value="{pigcms{:C('config.distributor_effective_time')}" size="10" validate="required:true,number:true,min:0" tips="单位年" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">代理商有效期</th>
				  <td>
					<input type="text" class="input-text" name="agent_effective_time" id="config_agent_effective_time" value="{pigcms{:C('config.agent_effective_time')}" size="10" validate="required:true,number:true,min:0" tips="单位年" />
				  </td>
				 </tr>
				
				
				 <tr>
				  <th width="160">开通分销员/代理商后自动开启会员等级</th>
				  <td>
			
					<select name="distributor_level" id="config_distributor_level" class="valid">
						<option value="0" <if condition="0 eq C('config.distributor_level')">selected</if>>无</option>
						<volist name="levelarr" id="vo">
							<option value="{pigcms{$vo.level}" <if condition="$vo['level'] eq C('config.distributor_level')">selected</if>>{pigcms{$vo.lname}</option>
						</volist>
						
					</select>
				  </td>
				 </tr>
				 
				  <tr>
				  <th width="160">代理商分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="agent_percent" id="config_agent_percent" value="{pigcms{:C('config.agent_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="代理商分佣比例" />
				  </td>
				 </tr>
				 
				 
				 <tr>
				  <th width="160">直接推广分销员分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="first_distributor_percent" id="config_first_distributor_percent" value="{pigcms{:C('config.first_distributor_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广分销员分佣比例" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">直接推广分销员的上级分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="second_distributor_percent" id="config_second_distributor_percent" value="{pigcms{:C('config.second_distributor_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广分销员的上级分佣比例" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">直接推广分销员的上上级分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="third_distributor_percent" id="config_third_distributor_percent" value="{pigcms{:C('config.third_distributor_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广分销员的上上级分佣比例" />
				  </td>
				 </tr>
				 
				  <tr>
				  <th width="160">直接推广代理商分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="first_agent_percent" id="config_first_agent_percent" value="{pigcms{:C('config.first_agent_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广代理商分佣比例" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">直接推广代理商的上级分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="second_agent_percent" id="config_second_agent_percent" value="{pigcms{:C('config.second_agent_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广代理商的上级分佣比例" />
				  </td>
				 </tr>
				 
				 <tr>
				  <th width="160">直接推广代理商的上上级分佣比例</th>
				  <td>
					<input type="text" class="input-text" name="third_agent_percent" id="config_third_agent_percent" value="{pigcms{:C('config.third_agent_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="直接推广代理商的上上级分佣比例" />
				  </td>
				 </tr>	

				 <tr>
				  <th width="160">分销员协议</th>
				  <td>
					<textarea id="distributor_rule" name="distributor_rule" style="width:700px;height:300px;">
						{pigcms{$config.distributor_rule|html_entity_decode}
					</textarea>
				  </td>
				 </tr>
				 
				  <tr>
				  <th width="160">代理商协议</th>
				  <td>
					<textarea id="agent_rule" name="agent_rule" style="width:700px;height:300px;">
					{pigcms{$config.agent_rule|html_entity_decode}
					</textarea>
				  </td>
				 </tr>
				 
				 
                 
                 
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				
			   </div> 
			</form> 
		</div>
		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<script>
		KindEditor.ready(function(K){
			window.editor = K.create('#distributor_rule',{pasteType : 1});
			window.editor = K.create('#agent_rule',{pasteType : 1});
		});
		</script>
		
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
			.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
			.tab_ul>li>a {
				position: relative;
				display: block;
				padding: 10px 15px;
				margin-right: 2px;
				line-height: 1.42857143;
				border: 1px solid transparent;
				border-radius: 4px 4px 0 0;
				padding: 7px 12px 8px;
				min-width: 100px;
				text-align: center;
				}
				.tab_ul>li>a, .tab_ul>li>a:focus {
				border-radius: 0!important;
				border-color: #c5d0dc;
				background-color: #F9F9F9;
				color: #999;
				margin-right: -1px;
				line-height: 18px;
				position: relative;
				}
				.tab_ul>li>a:focus, .tab_ul>li>a:hover {
				text-decoration: none;
				background-color: #eee;
				}
				.tab_ul>li>a:hover {
				border-color: #eee #eee #ddd;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li>a:hover {
				background-color: #FFF;
				color: #4c8fbd;
				border-color: #c5d0dc;
				}
				.tab_ul>li:first-child>a {
				margin-left: 0;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul:before,.tab_ul:after{
				content: " ";
				display: table;
				}
				.tab_ul:after{
				clear: both;
			}
		</style>
<include file="Public:footer"/>