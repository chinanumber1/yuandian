<include file="Public:header"/>
		
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('AppRedpack/index')}" >红包周期发放列表</a>
				
					<a href="{pigcms{:U('AppRedpack/setting')}" class="on">红包配置</a>
				
				</ul>
			</div>
			 <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true" > 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
					
				 <tr>
				  <th width="160">是否开启发放App红包：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.open_app_redpack')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="open_app_redpack" value="1" <php>if(C('config.open_app_redpack')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.open_app_redpack')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="open_app_redpack" value="0" <php>if(C('config.open_app_redpack')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启app登录后可以获取一定金额的红包" class="notice_tips"></em></td>
				 </tr> 
				 
				 <tr>
				  <th width="160">是否允许App红包提现：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.app_redpack_withdraw')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="app_redpack_withdraw" value="1" <php>if(C('config.app_redpack_withdraw')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.app_redpack_withdraw')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="app_redpack_withdraw" value="0" <php>if(C('config.app_redpack_withdraw')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启后App红包不能提现，但是可以正常消费" class="notice_tips"></em></td>
				 </tr>
				  <tr>
				  <th width="160">发红包开始时间</th>
				  <td>
					
					<input type="text" class="input-text" name="start_redpack_time" style="width:120px;" value="{pigcms{$config.start_redpack_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:00:00'})"/>			   
					<em tips="App红包开始发放的时间" class="notice_tips"></em>
					</td>
				 </tr>
				 
				 <tr>
				  <th width="160">发放红包周期</th>
				  <td><input type="text" class="input-text" name="redpack_cycle_time" id="config_redpack_cycle_time" value="{pigcms{$config.redpack_cycle_time}" size="10" validate="required:true,number:true,min:0" tips="红包周期，单位/小时，0 为不发送，1为每隔一小时发送一次" />
				
				  </td>
				 </tr>
				 
				<tr>
				  <th width="160">红包最小金额</th>
				  <td><input type="text" class="input-text" name="redpack_min_money" id="config_redpack_min_money" value="{pigcms{$config.redpack_min_money}" size="10" validate="required:true,number:true,min:0" tips="发红包的最小金额" /></td>
				</tr>
				
				 <tr>
				  <th width="160">红包最大金额</th>
				  <td><input type="text" class="input-text" name="redpack_max_money" id="config_redpack_max_money" value="{pigcms{$config.redpack_max_money}" size="10" validate="required:true,number:true,min:0" tips="发红包的最大金额" /></td>
				</tr>
				<tr>
				  <th width="160">红包比例</th>
				  <td><input type="text" class="input-text" name="redpack_money_percent" id="config_redpack_money_percent" value="{pigcms{$config.redpack_money_percent}" size="10" validate="required:true,number:true,min:0,max:100" tips="红包占上一周期平台收入的比例" /></td>
				</tr>     
				
				<tr>
				  <th width="160">积分有效期</th>
				  <td><input type="text" class="input-text" name="score_end_days" id="config_score_end_days" value="{pigcms{$config.score_end_days}" size="10" validate="required:true,number:true,min:0,max:100" tips="积分有效期，过期将清零" /></td>
				</tr>   
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="button" value="提交" class="button" id="submit_red"/> 
				
			   </div> 
			</form> 
		</div>
		<script>
			$('.cb-enable-user-integral').click(function(){
				$(".dis-user-integral").css("display","");
			});
			$('.cb-disable-user-integral').click(function(){
				$(".dis-user-integral").css("display","none");
			});
			
			$('.cb-enable-integral').click(function(){
				$('.class-village-pay-integral').css("display","");
			});
			$('.cb-disable-integral').click(function(){
				$('.class-village-pay-integral').css("display","none");
			});
			function check(){
				
				if($('#config_redpack_max_money').val()<$('#config_redpack_min_money').val()){
					window.top.msg(0,'红包最大金额不能比红包最小金额小',true,5);
					return false;
				}else if($('#config_redpack_max_money').val()==0){
					window.top.msg(0,'红包最大金额不能为0',true,5);
					return false;
				}else{
					return true;//不写此返回值也行，此时就直接提交了
				}
			}
			$(function(){
				
				$('#submit_red').click(function(){
					if(check()){
						$('#myform').submit()
					}
				})
				var data_rate_arr = [];
				var test = $('.input-text').each(function(index,val){
					var ids = $(val).attr('id');
					data_rate_arr[ids] = $(val).val()
					
				});
				
				
				$('.input-text').blur(function(){
					$('.input-text').each(function(index,val){
						var ids = $(val).attr('id');
						data_rate_arr[ids] = $(val).val()
						console.log(data_rate_arr)
					});
				})

				
				$('.score_max input:radio').click(function(){
					var percent = $(this).parents('td').find('input[type="text"]');
					var text_id = percent.attr('id');
					var	open_percent = data_rate_arr[text_id];
					if($(this).val()==1){
						if(open_percent<0){
							percent.val('');
						}else{
							percent.val(open_percent);
						}
						percent.show();
					}else{
						percent.hide();
						percent.val(-1);
						percent.hide();
					}
				});
				
				$('input[name="open_score_get_percent"]:radio').click(function(){
				
					if($(this).val()==0){
						
						$('#config_user_score_get').show();
						$('#config_score_get_percent').hide();
						$('#config_score_get_percent').nextAll().remove();
						$('#config_score_get_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得的积分" style="margin-top:1px;">');
					}else{
						$('#config_score_get_percent').show();
						$('#config_user_score_get').hide();
						$('#config_score_get_percent').nextAll().remove();
						$('#config_score_get_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得积分百分比" style="margin-top:1px;">');
					}
				});
				
			});

			function add(){
				var item = $('.plus:last');
				if($('.plus').length<=1&&$('.plus').css('display')=='none'){
					$('.plus').show();
				}else{
					var newitem = $(item).clone(true);
					var No = parseInt(item.find(".sort").html())+1;
					$(item).after(newitem);
					newitem.find('input').attr('value','');
					newitem.find(".sort").html(No);
				}
				// newitem.find('input[name="url[]"]').attr('id','url'+No);
			}
			
			function del(obj){
				if($('.plus').length<=1){
					$('.plus').hide();
				}else{
					$(obj).parents('.plus').remove();
					$.each($('.plus'), function(index, val) {
						var No =index+1;
						$(val).find('.sort').html(No);
						$(val).find('input[name="url[]"]').attr('id','url'+No);
						
					});
				}
			}
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