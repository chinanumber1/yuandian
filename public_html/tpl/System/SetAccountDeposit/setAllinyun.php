<include file="Public:header"/>
		<div class="mainbox">
			
				<div id="nav" class="mainnav_title">
					<ul>
						<a href="{pigcms{:U('setAccountDeposit/submitVerify')}">平台企业信息</a>
						<a href="{pigcms{:U('setAccountDeposit/setAllinyun')}" class="on">平台云商通配置</a>
						<a href="{pigcms{:U('setAccountDeposit/withdraw_apply')}" >平台云商通提现</a>
					</ul>
				</div>
		
				
			<form id="myform" method="post" action="{pigcms{:U('submitVerify')}" refresh="true">
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
				<tr>
					<th width="160">云商通账号：</th>
					<td><if condition="$deposit.bizUserId neq ''">{pigcms{$deposit.bizUserId}<else />
					<a href="{pigcms{:U('SetAccountDeposit/createAllinyun')}" class="btn btn-sm btn-success" id="Create_Allinyun">创建账号</a>
					</if></td>
				</tr>
				<tr>
					<th width="160">云商通绑定手机：</th>
					<td>
						<if condition="!empty($deposit['phone'])">
							云商通绑定手机：{pigcms{$deposit.phone}
							<a href="{pigcms{:U('SetAccountDeposit/editphone')}" class="btn btn-sm btn-success" >重置手机</a>
						<else />
							<a href="{pigcms{:U('SetAccountDeposit/bindphone')}" class="btn btn-sm btn-success">绑定手机</a>
						</if>
					</td>
				</tr>
				
				<tr>
					<th width="160">云商通企业审核：</th>
					<td>
						<a href="{pigcms{:U('SetAccountDeposit/submitAllinyun')}" class="btn btn-sm btn-success" id="Submit_verify"><if condition="$deposit.status eq 0">提交审核<elseif condition="$deposit.status eq 1" /> 查看企业信息<else />编辑企业信息</if>
					</td>
				</tr>
				
				<tr>
					<th width="160">云商通签约电子协议：</th>
					<td>
						<if condition="$deposit.sign_status eq 0"><a href="{pigcms{:U('SetAccountDeposit/signConnect')}&sign=1" class="btn btn-sm btn-success" id="signConnect">签约电子协议</a><elseif condition="$deposit.sign_status eq 1" /> 已签约</if>
					</td>
				</tr>
				
				<tr>
					<th width="160">云商通绑定银行卡：</th>
					<td>
						<if condition="$deposit.bank_list neq ''">	
					<a href="{pigcms{:U('SetAccountDeposit/bank_list')}" class="btn btn-sm btn-success" id="signConnect">
						银行卡列表
						</a>
						
						<else />
						
							<a href="{pigcms{:U('SetAccountDeposit/addBank')}" class="btn btn-sm btn-success" id="signConnect">添加银行卡</a>
						</if>
					</td>
				</tr>
		
                 
                 
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 

			   </div> 
			
			</form>
		</div>
		<script>
			$(function(){
				$('.table_form:eq(0)').show();
				
				$('.tab_ul li a').click(function(){
					$(this).closest('li').addClass('active').siblings('li').removeClass('active');
					$($(this).attr('href')).show().siblings('.table_form').hide();
					return false;
				});
		
				$('input[name="authType"]').click(function(){
					console.log(1)
					if($(this).val()==2){
						$('.one_verify').show();
						$('.three_verify').hide();
					}else{
						$('.one_verify').hide();
						$('.three_verify').show();
					}
				});
				

				
			});
				<if condition="empty($company) OR $company['authType'] eq 1">$('.one_verify').hide();
				$('.three_verify').show();</if>
		</script>
		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<script type="text/javascript">
			KindEditor.ready(function(K){
				var site_url = "{pigcms{$config.site_url}";
				var editor = K.editor({
					allowFileManager : true
				});
				$('.config_upload_image_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-image').val(site_url+url);
								editor.hideDialog();
							}
						});
					});
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
				});
				$('.config_upload_file_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_file')}&name="+upload_file_btn.siblings('.input-file').attr('name');
					editor.loadPlugin('insertfile', function(){
						editor.plugin.fileDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-file').val(url);
								editor.hideDialog();
							}
						});
					});
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
				});
				
				window.editor = K.create('#config_register_agreement',{pasteType : 1});
				
								
				
				
			});
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

