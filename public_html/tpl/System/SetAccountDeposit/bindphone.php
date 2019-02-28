<include file="Public:header"/>
		<div class="mainbox">
			
				<div id="nav" class="mainnav_title">
					<ul>
						<a href="javascript:void(0)"class="on">平台存托管企业信息</a>
					</ul>
				</div>
		
				
			<form id="myform" method="post" action="{pigcms{:U('bindphone')}" refresh="true">
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
				
				<tr>
					<th width="160">手机号码</th>
					<td><input type="text" name="phone" id="phone" class="input-text" value="" style="width:210px;"/></td>
				</tr>
				
				<tr>
					<th width="160">验证码</th>
					<td><input type="text" name="code" id="code" class="input-text" value="" style="width:210px;"/> <a href="javascript:void(0)" onclick="sendsms(this)" class="btn btn-sm btn-success" id="Create_Allinyun">发送验证码</a></td>
				</tr>
								
			
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				<if condition="$deposit.bizUserId neq ''">
					云商通账号：{pigcms{$deposit.bizUserId}
				<else />
					<a href="{pigcms{:U('SetAccountDeposit/createAllinyun')}" class="btn btn-sm btn-success" id="Create_Allinyun">创建账号</a>
				</if>
				
				<if condition="!empty($deposit['phone'])">
					云商通绑定手机：{pigcms{$deposit.phone}
					<a href="{pigcms{:U('SetAccountDeposit/editphone')}" class="btn btn-sm btn-success" >重置手机</a>
				<else />
					<a href="{pigcms{:U('SetAccountDeposit/bindphone')}" class="btn btn-sm btn-success">绑定手机</a>
				</if>
				
				<a href="{pigcms{:U('SetAccountDeposit/submitVerify')}" class="btn btn-sm btn-success" id="Submit_verify"><if condition="$deposit.status eq 0">提交审核<elseif condition="$deposit.status eq 1" /> 查看企业信息<else />编辑企业信息</if></a>
				
				<a href="{pigcms{:U('SetAccountDeposit/signConnect')}&sign=1" class="btn btn-sm btn-success" id="signConnect"><if condition="$deposit.sign_status eq 0">签约电子协议<elseif condition="$deposit.sign_status eq 1" /> 已签约</if></a>
			   </div> 
			
			</form>
		</div>

		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<script type="text/javascript">
			var countdown = 60;
			function sendsms(val){
			
				
					if($("input[name='phone']").val()==''){
						alert('手机号码不能为空！');
					}else{
						
						
						if(countdown==60){
							$.ajax({
								url: '{pigcms{:U('SetAccountDeposit/sendsms')}',
								type: 'POST',
								dataType: 'json',
								data: {phone: $("input[name='phone']").val()},
								success:function(date){
									if(date.error_code){
										
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
		</script>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
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

