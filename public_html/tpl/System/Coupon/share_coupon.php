<include file="Public:header"/>
		
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Coupon/index')}" >平台优惠券列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Coupon/add')}','添加平台优惠券',800,500,true,false,false,addbtn,'edit',true);">添加平台优惠券</a>
					<a href="{pigcms{:U('Coupon/had_pull')}" >领取列表</a>
					<a href="{pigcms{:U('Coupon/send_coupon')}" >派发优惠券</a>
				
				</ul>
			</div>
			 <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
			
				
				 
				  <tr>
				  <th width="160">开启分享抢券：</th>
				  <td>
				  
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.share_coupon')==1){</php>selected<php>}</php>">
							<span>开启</span>
							<input type="radio" name="share_coupon" value="1" <php>if(C('config.share_coupon')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.share_coupon')==0){</php>selected<php>}</php>">
							<span>关闭</span>
							<input type="radio" name="share_coupon" value="0" <php>if(C('config.share_coupon')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="开启后，支付并验证消费后用户可以分享抢券信息到朋友圈，让朋友领一定数量的优惠券，同时自己可以随机得到一张优惠券" class="notice_tips"></em>
				  </td>
			   </tr>
				<tr>
				  <th width="160">只能抢随机派发的优惠券：</th>
				  <td>
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.share_rand_send_coupon')==1){</php>selected<php>}</php>">
							<span>是</span>
							<input type="radio" name="share_rand_send_coupon" value="1" <php>if(C('config.share_rand_send_coupon')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.share_rand_send_coupon')==0){</php>selected<php>}</php>">
							<span>否</span>
							<input type="radio" name="share_rand_send_coupon" value="0" <php>if(C('config.share_rand_send_coupon')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="开启后给朋友的券只能是随机派发的券" class="notice_tips"></em>
				  </td>
			    </tr>
				
				<tr>
				  <th width="160">抢券的数量：</th>
				  <td><input type="text" class="input-text" name="share_coupon_num" id="config_share_coupon_num" value="{pigcms{$config.share_coupon_num}" size="10" validate="required:true,	digits:true" tips="分享后可以抢到券的数量" /></td>
				 </tr>
				 
				 <tr>
				  <th width="160">分享后获得券的数量：</th>
				  <td><input type="text" class="input-text" name="share_coupon_get_num" id="config_share_coupon_get_num" value="{pigcms{$config.share_coupon_get_num}" size="10" validate="required:true,digits:true" tips="用户分享后获得券的数量" /></td>
				 </tr>
				
                 
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				
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
			
			$(function(){
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