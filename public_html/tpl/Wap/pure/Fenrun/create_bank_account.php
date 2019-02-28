<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>添加银行卡</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}fenrun/css/fenrun.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		
    <style>
	
	</style>
</head>

 <body>
		<section class="balance">
		<form class="my_from">
            <div class="present bw">
                <div class="fl">持卡人姓名</div>
                <div class="p208">
                    <input type="text" placeholder="请填写持卡人姓名" name="account_name" value="{pigcms{$bank.account_name}"/>
                </div>
            </div>
            <div class="present bw">
                <div class="fl">银行卡号</div>
                <div class="p208">
                    <input type="text" placeholder="请填写卡号" name="account"  value="{pigcms{$bank.account}"/>
                </div>
            </div>
			 <div class="present bw">
                <div class="fl">所属银行</div>
                <div class="p208">
                    <input type="text" placeholder="请填写所属银行" name="remark" value="{pigcms{$bank.remark}"/>
                </div>
            </div>
			 <div class="present bw">
                <div class="fl">是否默认</div>
                <div class="p208">
                   <div class="select" >
				
					<select name="is_default"  style="width:100%">
						<option value="0"  <if condition="$bank.is_default eq 0">selected</if>>否</option>	
						<option value="1"  <if condition="$bank.is_default eq 1">selected</if>>是</option>	
					</select>
					</div>
                </div>
            </div>
				<input type="hidden" name="bank_id" value="{pigcms{$bank.id}">
            
		</form>
            <div class="confirm on">保存</div>
        </section>
        
        <script src="{pigcms{$static_path}fenrun/js/fenrun.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
		var flag = true;
		
		$('input[name="account"]').change(function(){
			$.ajax({
				url: '{pigcms{:U('get_bank_name')}',
				type: 'POST',
				dataType: 'json',
				data: {card_number: $('input[name="account"]').val()},
				success:function(data){
					if(data.status){
						$('input[name="bank"]').val(data.info)
				
					}else{
				
						layer.open({
							content:'没有查询到相关银行，请手动输入',
							btn: ['我知道了']
							 ,yes: function(index){
							 
							  layer.close(index);
							}
						});
					}
					
				}
			});
		});
		
			
		$('.confirm').click(function(){
			if(flag){
				var withdraw_url = '{pigcms{:U('create_bank_account')}'
				$.ajax({
					url: withdraw_url,
					type: 'POST',
					dataType: 'json',
					data:$('.my_from').serialize(),
					beforeSend:function(){
						flag = false;
					},
					success:function(data){
						flag = true
						layer.open({
							content:data.info,
							shadeClose:false,
							btn: ['我知道了']
							 ,yes: function(index){
								 console.log(data.url)
								 if(data.url!=''){
									 window.location.href=data.url;
								 }else{
								
									  layer.close(index);
								 }
							}
						});
						
					}
				});
			}
		});
	</script>
    </body>

</html>