<include file="Public:header"/>
		<div class="mainbox">
			
				<div id="nav" class="mainnav_title">
					<ul>
						<a href="javascript:void(0)"class="on">平台企业信息</a>
						<a href="{pigcms{:U('setAccountDeposit/setAllinyun')}">平台云商通配置</a>
						<a href="{pigcms{:U('setAccountDeposit/withdraw_apply')}" >平台云商通提现</a>
					</ul>
				</div>
		
				
			<form id="myform" method="post" action="{pigcms{:U('submitVerify')}" refresh="true">
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
				<tr>
					<th width="160">企业名称</th>
					<td><input type="text" name="companyName" id="companyName" class="input-text" value="{pigcms{$company.companyName}" style="width:210px;"/></td>
				</tr>
				
				<tr>
					<th width="160">企业地址</th>
					<td><input type="text" name="companyAddress" id="companyAddress" class="input-text" value="{pigcms{$company.companyAddress}" style="width:210px;"/></td>
				</tr>
				
				
				 <tr >
				  <th width="160">认证类型</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <if condition="$company.authType eq 1 OR empty($company)">selected</if>"><span>三证</span>
						<input type="radio" name="authType" value="1" <if condition="$company.authType eq 1 OR empty($company)">checked="checked"</if> />
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <if condition="$company.authType eq 2">selected</if>"><span>一证</span>
						<input type="radio" name="authType" value="2" <if condition="$company.authType eq 2">checked="checked"</if>/>
						</label>
					</span>
					<em tips="" class="notice_tips"></em></td>
				 </tr>
				 
				<tr class="one_verify">
					<th width="160">统一社会信用</th>
					<td><input type="text" name="uniCredit" id="uniCredit" class="input-text" value="{pigcms{$company.uniCredit}" style="width:210px;"/></td>
				</tr>
				
				<tr class="three_verify">
					<th width="160">营业执照号</th>
					<td><input type="text" name="businessLicense" id="businessLicense" class="input-text" value="{pigcms{$company.businessLicense}" style="width:210px;"/></td>
				</tr>
				
				<tr class="three_verify">
					<th width="160">组织机构代码</th>
					<td><input type="text" name="organizationCode" id="organizationCode" class="input-text" value="{pigcms{$company.organizationCode}" style="width:210px;"/></td>
				</tr>
				
				<tr class="three_verify">
					<th width="160">税务登记证</th>
					<td><input type="text" name="taxRegister" id="taxRegister" class="input-text" value="{pigcms{$company.taxRegister}" style="width:210px;"/></td>
				</tr>
				
				 <tr>
				  <th width="160">统一社会信用/营业执照号到期时间</th>
				  <td>
				  <input type="text" class="input-text" name="expLicense" style="width:120px;" id="d4311" value="<if condition="$company['expLicense'] gt 0">{pigcms{$company.expLicense|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
				  </td>
				 </tr>
				
				<tr>
					<th width="160">联系电话</th>
					<td><input type="text" name="telephone" id="telephone" class="input-text" value="{pigcms{$company.telephone}" style="width:210px;"/></td>
				</tr>	
				
				<tr>
					<th width="160">法人姓名</th>
					<td><input type="text" name="legalName" id="legalName" class="input-text" value="{pigcms{$company.legalName}" style="width:210px;"/></td>
				</tr>	
				
				<tr>
					<th width="160">法人证件号</th>
					<td><input type="text" name="legalIds" id="legalIds" class="input-text" value="{pigcms{$company.legalIds}" style="width:210px;"/></td>
				</tr>
				
				<tr>
					<th width="160">法人手机号码</th>
					<td><input type="text" name="legalPhone" id="legalPhone" class="input-text" value="{pigcms{$company.legalPhone}" style="width:210px;"/></td>
				</tr>	
				
				<tr>
					<th width="160">企业对公账户</th>
					<td><input type="text" name="accountNo" id="accountNo" class="input-text" value="{pigcms{$company.accountNo}" style="width:210px;"/></td>
				</tr>		
				
				<tr>
					<th width="160">开户银行名称</th>
					<td><input type="text" name="parentBankName" id="parentBankName" class="input-text" value="{pigcms{$company.parentBankName}" style="width:210px;"/></td>
				</tr>	
				
				<tr>
					<th width="160">开户行地区代码</th>
					<td><input type="text" name="bankCityNo" id="bankCityNo" class="input-text" value="{pigcms{$company.bankCityNo}" style="width:210px;"/></td>
				</tr>	
				
				<tr>
					<th width="160">开户行支行名称</th>
					<td><input type="text" name="bankName" id="bankName" class="input-text" value="{pigcms{$company.bankName}" style="width:210px;"/></td>
				</tr>		
				
				<tr>
					<th width="160">支付行号</th>
					<td><input type="text" name="unionBank" id="unionBank" class="input-text" value="{pigcms{$company.unionBank}" style="width:210px;"/></td>
				</tr>	
				
		
                 
                 
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				
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

