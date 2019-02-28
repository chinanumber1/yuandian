<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li>
				<a href="{pigcms{:U('Card_new/card_new_coupon')}">会员卡优惠券</a>
			</li>
			<li class="active">派发优惠券</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					
					<div class="tabbable" style="margin-top:20px;">
						<ul class="nav nav-tabs" id="myTab">
							<li >
								<a  href="{pigcms{:U('Card_new/send_coupon')}&tab=1">
									分组派发
								</a>
							</li>
							<li>
								<a  href="{pigcms{:U('Card_new/send_coupon')}&tab=2" >
									个人派发
								</a>
							</li>
						
							<li class="active">
								<a href="{pigcms{:U('Card_new/send_all')}" >
									全部派发
								</a>
							</li>
							<li>
								<a href="{pigcms{:U('Card_new/weixin_send')}" >
									微信购买派发
								</a>
							</li>
							<li>
								<a href="{pigcms{:U('Card_new/send_history')}" >
									派发记录
								</a>
							</li>
						
						</ul>
						<div class="tab-content">
							
							<div id="groupinfo" class="tab-pane active">
								<div class="widget-box">
								
									<table class="table table-striped table-bordered table-hover" id="user_table" style="display:none">
										<thead>
											<tr>
												<th>勾选会员</th>
												<th>用户会员卡ID</th>
												<th>用户昵称</th>
												<th>用户手机</th>
												
											</tr>
										</thead>
										<tbody id="user_list">
											
										</tbody>
									</table>
								</div>	
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
											
										</div>						
									</div>
								
								</div>
							</div>
							
							<style type="text/css">
								.radio {
								    min-height: 27px;
								}
							</style>
							<div class="tab-pane " style="display:block">
								<div class="widget-box">
									
									<div class="widget-body form-group"  style="padding:20px;height:300px;width:100%;line-height: 1.5;overflow:auto">
										<!--<volist name="coupon_list" id="vo">
											<div class="radio">
												<label>
													<input class="paycheck ace store-list" type="checkbox" name="coupon_id[]" value="{pigcms{$vo.coupon_id}" id="coupon{pigcms{$vo.coupon_id}"/>
													<span class="lbl"><label for="coupon{pigcms{$vo.coupon_id}">{pigcms{$vo.name} - {pigcms{$vo.des}</label></span>
												</label>
											</div>
										</volist>-->
										
										<table class="table table-striped table-bordered table-hover"  >
											<thead>
												<tr>
													<th>勾选优惠券</th>
													
													<th>优惠券名称</th>
													<th>优惠券描述</th>
													
												</tr>
											</thead>
											<tbody id="coupon_list">
											<volist name="coupon_list" id="vo">
												
											
												<tr class="even">
												<td style="width: 120px">
													<input type="checkbox" name="coupon_id[]" value="{pigcms{$vo.coupon_id}" id="coupon{pigcms{$vo.id}">
												</td>
													<td style="width: 120px">{pigcms{$vo.name}</td>
													<td style="width: 120px">{pigcms{$vo.des}</td>
												</tr>
											</volist>
										</tbody>
										</table>
									</div>
								</div>	
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
											<button class="btn btn-info" type="submit" id="save_btn" style="margin-left: 25%;" href="">
												<i class="ace-icon fa fa-check bigger-110"></i>
												派发优惠券
											</button>
										</div>						
									</div>
								
								</div>
							</div>
							
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
	
	var tab = $('#myTab .active a').attr('data-id');
	$(function(){
		$('#search_user').click(function(){
			var keyword = $('select[name="keyword"]').val();
			var search_val = $('input[name="search_val"]').val();
			$.post("{pigcms{:U('Card_new/ajax_get_user_merid')}", {keyword:keyword,search_val:search_val}, function(data, textStatus, xhr) {
				
				if(data == null || data == undefined || data == ''){
					$('#user_list').html('<tr class="odd"><td class="button-column" colspan="4" >没有查询到用户</td></tr>');
				}else{
					var str;
					$.each(data, function(index, val) {
						console.log(val)
						if(index%2==0){
							str += '<tr class="even">';
						}else{
							str += '<tr class="odd">';
						}
						str+='<td style="width: 120px"><input type="radio" name="uid" value="'+val.uid+'"></td>';
						str+='<td style="width: 120px">'+val.id+'</td>';
						str+='<td style="width: 120px">'+val.nickname+'</td>';
						str+='<td style="width: 120px">'+val.phone+'</td>';
						str+='</tr>';
					});
					
					$('#user_list').html(str);
					$('#user_table').show();
				}
				
			});
		});
		$('#save_btn').click(function(){
			var send_url = "{pigcms{:C('config.site_url')}{pigcms{:U('Card_new/send')}";
			
			if($("input[name='coupon_id[]']:checked").length==0){
				alert('没有勾选优惠券');return false;				
			}
			

            var coupon_id='';
		 	$('input[name="coupon_id[]"]').each(function () {
                if ($(this).is(":checked")) {
                    coupon_id +=$(this).val()+',';
                }
            });
            coupon_id=coupon_id.substring(0,coupon_id.length-1);
						
			$(this).attr('href',send_url+'&all=1&coupon_id='+coupon_id);
			$.post(send_url, {all:1,coupon_id:coupon_id}, function(data, textStatus, xhr) {
				artDialog({content:data.info,time:3})
				if(data.status){
					window.location.reload();
				}
			},'json');
			
            console.log(coupon_id);
		});

		$('input[name="card_group_id[]"]').click(function(){
		 	var card_group_id='';
		 	$('input[name="card_group_id[]"]').each(function () {
                if ($(this).is(":checked")) {
                    card_group_id +=$(this).val()+',';
                }
            });
            card_group_id=card_group_id.substring(0,card_group_id.length-1);
			$.post("{pigcms{:U('Card_new/ajax_get_send_coupon')}", {card_group_id:card_group_id}, function(data, textStatus, xhr) {
				console.log(data);
				if(data == null || data == undefined || data == ''){
					$('#coupon_list').html('没有可用的优惠券');
				}else{
					var str='';
					$.each(data, function(coupon_id, val) {
						// str += '<div class="radio"><label>';
						// if(val.disable){
							// str+='<input class="paycheck ace store-list" disabled="disabled" type="checkbox" name="coupon_id[]" value="'+coupon_id+'" id="coupon'+coupon_id+'"/>';
						// }else{							
							// str+='<input class="paycheck ace store-list" type="checkbox" name="coupon_id[]" value="'+coupon_id+'" id="coupon'+coupon_id+'"/>';
						// }
						// str+='<span class="lbl"><label for="coupon'+coupon_id+'">'+val.name+' - '+val.des+'</label></span>';
						// str+='</label></div>';
						if(coupon_id%2==0){
							str += '<tr class="even">';
						}else{
							str += '<tr class="odd">';
						}
						str+='<td style="width: 120px">';
						if(val.disable){
							str+='<input type="checkbox" name="coupon_id[]" value="'+coupon_id+'" disabled="disabled"  id="coupon'+coupon_id+'"></td>';
						}else{
							str+='<input type="checkbox" name="coupon_id[]" value="'+coupon_id+'" id="coupon'+coupon_id+'"></td>';
						}
						str+='<td style="width: 120px">'+val.name+'</td>';
						str+='<td style="width: 120px">'+val.des+'</td>';
						str+='</tr>';
					});
					$('#coupon_list').html(str);
				}
			});
		});

		$('#myTab li').click(function(event) {
		    $(":checkbox").attr("checked", false);
			tab = $(this).find('a').attr('data-id');
			$.post("{pigcms{:U('Card_new/ajax_get_send_coupon')}", '', function(data, textStatus, xhr) {
				if(data == null || data == undefined || data == ''){
					$('#coupon_list').html('没有可用的优惠券');
				}else{
					var str='';
					$.each(data, function(coupon_id, val) {
						// str += '<div class="radio"><label>';
						// str+='<input class="paycheck ace store-list" type="checkbox" name="coupon_id[]" value="'+coupon_id+'" id="coupon'+coupon_id+'"/>';
						// str+='<span class="lbl"><label for="coupon'+coupon_id+'">'+val.name+' - '+val.des+'</label></span>';
						// str+='</label></div>';
						str+='<tr class="even">';
						str+='<td style="width: 120px">';
						str+='<input type="checkbox" name="coupon_id[]" value="'+coupon_id+'" id="coupon'+coupon_id+'"></td>';
						str+='<td style="width: 120px">'+val.name+'</td>';
						str+='<td style="width: 120px">'+val.des+'</td>';
						str+='</tr>';	
						
					});
					$('#coupon_list').html(str);
				}
			});
		});
	});
</script>


<include file="Public:footer"/>
