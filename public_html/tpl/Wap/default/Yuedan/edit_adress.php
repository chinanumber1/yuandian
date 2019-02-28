<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>编辑收货地址</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
		body{background-color:#f1f1f1;}
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    dd>label.react {
	        padding: .28rem .2rem;
	    }
	
	    .kv-line h6 {
	        width:1.1rem;
	    }
		.btn {
			background: #06c1bb;
		}
		dl.list-in dd {
			border-bottom: 1px dashed #e5e5e5;
		}
	</style>  
</head>
<body id="index" data-com="pagecommon">
        <div id="tips" class="tips"></div>
        <form id="form" method="post" action="{pigcms{:U('Yuedan/edit_adress')}">
		    <dl class="list list-in">
		    	<dd>
		    		<dl style="padding-right:.2rem;">
		        		<dd class="dd-padding kv-line">
		        			<h6>联系人</h6>
		        			<input name="name" type="text" class="kv-v input-weak" placeholder="最少2个字" pattern=".{2,}" data-err="姓名必须大于2个字！" value="{pigcms{$now_adress.name}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<h6>手机号</h6>
		        			<input name="phone" type="tel" class="kv-v input-weak" placeholder="不少于7位" pattern="\d{3}[\d\*]{4,}" data-err="电话必须大于7位！" value="{pigcms{$now_adress.phone}">
		        		</dd>
		        		<dd class="dd-padding kv-line" id="color-gray" style="padding-right:0px;">
		        			<h6>地址</h6>
	                        <span class="color-gray" id="color-gray-address" style="position:relative;display:block;">
								<img src="{pigcms{$static_path}images/location.png" style="width:16px;height:16px;position:absolute;left:3px;top:0px;"/>
								<span style="padding-left:22px;display:block;line-height:1.5"><?php if(!empty($now_adress['adress'])): ?><?php echo $now_adress['adress']; ?><?php else : ?>点击选择位置<?php endif; ?></span>
							</span>
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<h6>门牌号</h6>
		        			<input name="detail" type="text" class="kv-v input-weak" placeholder="详细地址，例：1号楼一单元101室" pattern=".{2,}" data-err="详址必须大于2个字！" value="{pigcms{$now_adress.detail}"/>
		        		</dd>
		        		<dd>
			            	<label class="react">
			                	<input type="checkbox" name="default" value="1" class="mt" <if condition="$now_adress['default']">checked="checked"</if>/>
			              		  设为默认地址
			            	</label>
			        	</dd>
			    	</dl>
		   		</dd>
			</dl>
		    <div class="btn-wrapper">
	    		<input type="hidden" name="adress_id" value="{pigcms{$now_adress.adress_id}"/>
				<input type="hidden" name="longitude" value="{pigcms{$now_adress.longitude}"/>
				<input type="hidden" name="latitude" value="{pigcms{$now_adress.latitude}"/>
				<input type="hidden" name="adress" value="{pigcms{$now_adress.adress}"/>
				<input type="hidden" name="group_id" value="{pigcms{$_GET.group_id}"/>
				<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
				<input type="hidden" name="buy_type" value="{pigcms{$_GET.buy_type}"/>
				<input type="hidden" name="gift_id" value="{pigcms{$_GET.gift_id}"/>
				<input type="hidden" name="classify_userinput_id" value="{pigcms{$_GET.classify_userinput_id}"/>
				<input type="hidden" name="current_id" value="{pigcms{$_GET.current_id}"/>
				<input type="hidden" name="province" value="{pigcms{$now_adress.province}"/>
				<input type="hidden" name="city" value="{pigcms{$now_adress.city}"/>
				<input type="hidden" name="area" value="{pigcms{$now_adress.area}"/>
				<button type="submit" class="btn btn-block btn-larger"><if condition="$now_adress">保存<else/>添加</if></button>
				<if condition="$now_adress"><button type="button" class="btn btn-block btn-larger" style=" background:#fff; color:#000; margin-top:.1rem" id="address_del">删除</button></if>
		    </div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.cookie.js"></script> 
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$(function(){
				
				if($('input[name="name"]').val() == ''){
					$('input[name="name"]').focus();
				}
				
				$("select[name='province']").change(function(){
					show_city($(this).find('option:selected').attr('value'));
				});
				$("select[name='city']").change(function(){
					show_area($(this).find('option:selected').attr('value'));
				});
				$("#color-gray").click(function(){
					var detail = new Object();
					detail.name = $('input[name="name"]').val();
					detail.province = $('input[name="province"]').val();
					detail.area = $('input[name="area"]').val();
					detail.city = $('input[name="city"]').val();
					detail.defaul = $('input[name="default"]').val();
					detail.detail = $('input[name="detail"]').val();
					detail.zipcode = $('input[name="zipcode"]').val();
					detail.phone = $('input[name="phone"]').val();
					detail.id = $('input[name="adress_id"]').val();
					detail.longitude = $('input[name="longitude"]').val();
					detail.latitude = $('input[name="latitude"]').val();
					
					$.cookie("user_address", JSON.stringify(detail));
					location.href = "{pigcms{:U('Yuedan/adres_map', $params)}";
				});

				
				$('#form').submit(function(){
					$('#tips').removeClass('tips-err').empty();
					var form_input = $(this).find("input[type='text'],input[type='tel'],textarea");
					$.each(form_input,function(i,item){
						if($(item).attr('pattern')){
							var re = new RegExp($(item).attr('pattern'));
							if($(item).val().length == 0 || !re.test($(item).val())){
								$('#tips').addClass('tips-err').html($(item).attr('data-err'));
								return false;
							}
						}

						if(i+1 == form_input.size()){
							layer.open({type:2,content:'提交中，请稍候'});
							$.post($('#form').attr('action'),$('#form').serialize(),function(result){
								layer.closeAll();
								if(result.status == 1){
									<if condition="$_GET['referer']">
										window.location.href="{pigcms{$_GET.referer|htmlspecialchars_decode=###}";
									<else/>
										window.location.href="{pigcms{:U('Yuedan/adress',$params)}";
									</if>
								}else{
									$('#tips').addClass('tips-err').html(result.info);
								}
							});
						}
					});
			
					return false;
				});
			});
			function show_city(id){
				$.post("{pigcms{:U('Yuedan/select_area')}",{pid:id},function(result){
					result = $.parseJSON(result);
					if(result.error == 0){
						var area_dom = '';
						$.each(result.list,function(i,item){
							area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>'; 
						});
						$("select[name='city']").html(area_dom);
						show_area(result.list[0].area_id);
					}
				});
			}
			function show_area(id){
				$.post("{pigcms{:U('Yuedan/select_area')}",{pid:id},function(result){
					result = $.parseJSON(result);
					if(result.error == 0){
						var area_dom = '';
						$.each(result.list,function(i,item){
							area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>'; 
						});
						$("select[name='area']").html(area_dom);
					}else{
						$("select[name='area']").html('<option value="0">请手动填写区域</option>');
					}
				});
			}
			
			$('#color-gray-address').width($('#color-gray').width() - $('#color-gray h6').width());
			
			$('#address_del').click(function(){
			    var backUrl = "{pigcms{:U('adress')}";
				var store_id = "{pigcms{$_GET['store_id']}";
				var mer_id = "{pigcms{$_GET['mer_id']}";
				var current_id = "{pigcms{$_GET['current_id']}";
				var buy_type = "{pigcms{$_GET['buy_type']}"
				var type = "{pigcms{$_GET['type']}"
				var cid = "{pigcms{$_GET['cid']}"
			    backUrl += '&store_id=' + store_id + '&mer_id=' + mer_id + '&current_id=' + current_id + '&buy_type=' + buy_type + "&cid=" + cid + '&type=' + type;
				layer.open({
					content:'确认删除',
					btn: ['确认','取消'],
					yes:function(){
						var del_url = "{pigcms{:U('Yuedan/ajax_del_adress')}";
						$.get(del_url,{'adress_id':"{pigcms{$now_adress['adress_id']}"},function(data){
							if(data.status){
								var address_url = "{pigcms{:U('Yuedan/adress')}";
								location.href = backUrl;
							}
						},'json')
					}
				});
			});
		</script>
{pigcms{$hideScript}
</body>
</html>