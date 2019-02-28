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
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    dd>label.react {
	        padding: .28rem .2rem;
	    }
	
	    .kv-line h6 {
	        width: .8rem;
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
        <form id="form" method="post" action="{pigcms{:U('My/edit_adress')}">
        
		    <dl class="list list-in">
		    	<dd>
		    		<dl>
		        		<dd class="dd-padding kv-line">
		        			<h6>姓名:</h6>
		        			<input name="name" type="text" class="kv-v input-weak" placeholder="最少2个字" pattern=".{2,}" data-err="姓名必须大于2个字！" value="{pigcms{$now_adress.name}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<h6>电话:</h6>
		        			<input name="phone" type="tel" class="kv-v input-weak" placeholder="不少于7位" pattern="\d{3}[\d\*]{4,}" data-err="电话必须大于7位！" value="{pigcms{$now_adress.phone}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
				            <h6>省份:</h6>
				            <label class="select kv-v">
				                <select name="province">
									<if condition="$now_adress">
										<volist name="province_list" id="vo">
											<option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_adress['province']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
										</volist>
									<else/>
										<volist name="province_list" id="vo">
											<option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_pid']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
										</volist>
									</if>
				                </select>
				            </label>
				        </dd>
				        <dd class="dd-padding kv-line">
				            <h6>城市:</h6>
				            <label class="select kv-v">
				                <select name="city">
									<if condition="$now_adress">
										<volist name="city_list" id="vo">
											<option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_adress['city']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
										</volist>
									<else/>
										<volist name="city_list" id="vo">
											<option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
										</volist>
									</if>
				                </select>
				            </label>
				        </dd>
				        <dd class="dd-padding kv-line">
				            <h6>区县:</h6>
				            <label class="select kv-v">
				                <select name="area">
				                    <volist name="area_list" id="vo">
				                        <option value="{pigcms{$vo.area_id}"  <if condition="$vo['area_id'] eq $now_adress['area']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
				                    </volist>
				                </select>
				            </label>
				        </dd>
		        		<dd class="dd-padding kv-line" id="color-gray">
		        			<h6>位置:</h6>
	                        <i class="icon-location" data-node="icon"></i><span class="color-gray" data-node="addAddress"><?php if(!empty($now_adress['adress'])): ?><?php echo $now_adress['adress']; ?><?php else : ?><img src="{pigcms{$static_path}images/location.png" style=" width:25px; height:25px"/>点击选择位置<?php endif; ?></span> <i class="right_arrow"></i>
	                        <!--div class="weaksuggestion"> 请点击这里，进行添加！<i class="toptriangle"></i> </div-->
		        			<!--textarea name="adress" class="input-weak kv-v" placeholder="最少5个字,最多60个字,不能全部为数字" pattern="^.{5,60}$" data-err="请填写正确的地址信息！">{pigcms{$now_adress.adress}</textarea-->
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<h6>地址:</h6>
		        			<input name="detail" type="text" class="kv-v input-weak" placeholder="请填写详细的地址和门牌号" pattern=".{2,}" data-err="详址必须大于2个字！" value="{pigcms{$now_adress.detail}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<h6>邮编:</h6>
		        			<input type="tel" name="zipcode" class="input-weak kv-v" placeholder="6位邮政编码，可不填写"  maxlength="6" value="<if condition="$now_adress['zipcode']">{pigcms{$now_adress.zipcode}</if>"/>
		        		</dd>
		        		<dd>
			            	<label class="react">
			                	<input type="checkbox" name="default" value="1" class="mt"  <if condition="$now_adress['default']">checked="checked"</if>/>
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
				$("select[name='province']").change(function(){
					show_city($(this).find('option:selected').attr('value'));
				});
				$("select[name='city']").change(function(){
					show_area($(this).find('option:selected').attr('value'));
				});
				$("#color-gray").click(function(){
					var detail = new Object();
					detail.name = $('input[name="name"]').val();
					detail.province = $('select[name="province"]').val();
					detail.area = $('select[name="area"]').val();
					detail.city = $('select[name="city"]').val();
					detail.defaul = $('input[name="default"]').val();
					detail.detail = $('input[name="detail"]').val();
					detail.zipcode = $('input[name="zipcode"]').val();
					detail.phone = $('input[name="phone"]').val();
					detail.id = $('input[name="adress_id"]').val();
					
					$.cookie("user_address", JSON.stringify(detail));
					location.href = "{pigcms{:U('My/adres_map', $params)}";
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
										window.location.href="{pigcms{:U('My/adress',$params)}";
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
				$.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
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
				$.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
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
			
			
			$('#address_del').click(function(){
				layer.open({
					content:'确认删除',
					btn: ['确认','取消'],
					yes:function(){
						var del_url = "{pigcms{:U('My/ajax_del_adress')}";
						$.get(del_url,{'adress_id':"{pigcms{$now_adress['adress_id']}"},function(data){
							if(data.status){
								var address_url = "{pigcms{:U('My/adress')}";
								location.href = address_url;
							}
						},'json')
					}
				});
			});
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>