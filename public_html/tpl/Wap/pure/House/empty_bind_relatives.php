<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>绑定亲属</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}village_list/css/pigcms.css" rel="stylesheet"/>
    </head>
    <body>
        <section class="relatives">
        	<form method="post" id="submitFrom" action="__SELF__">
            <div class="choice">
                <h2>选择要绑定的房屋</h2>
                <empty name="user_all_village_list">
                <div class="output">
                <p>您还不是业主，请先绑定业主！</p>
                </div>
                <else />
                <div class="select">
                	<input type="hidden" name="name" class="user-name-info" value="">
                    <input type="hidden" name="type" value="1">
                    <input type="hidden" name="error_code" class="error_code" value="">
                    <select name="pigcms_id">
                    	<volist name="user_all_village_list" id="vo">
                        <option value="{pigcms{$vo.pigcms_id}">{pigcms{$vo.village_name}-{pigcms{$vo.floor_layer}{pigcms{$vo.floor_name}-{pigcms{$vo.layer}#{pigcms{$vo.room}</option>
                        </volist>
                    </select>
                </div>
                </empty>
            </div>
            <notempty name="user_all_village_list">
            <div class="output">
                <input type="tel" name="phone" class="bind-relatives-tel" placeholder="请输入绑定家属手机号码">
                <ul class="user-info-view">
                   
                </ul>
            </div>
            <div class="determine">确定绑定</div>
            </notempty>
            </form>
        </section>

        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
        
        
         <script type="text/html" id="load_user_info_view">
			<li class="clr">
				<div class="img fl">
					<img src="{{# if(d.avatar){ }}{{ d.avatar }}{{# }else{ }}{pigcms{$static_path}village_list/images/user_avatar.jpg{{# } }}">
				</div>
				<div class="p95">
					<h2>{{# if(d.truename){ }}{{ d.truename}}{{# }else{ }}{{ d.nickname}}{{# }}}</h2>
					<p>{{ d.phone }}</p>
				</div>
			</li>
		</script>
		
        
        <script type="text/javascript">
			 
			 //确定绑定
			 $(".determine").on('click' , function(){
	
			
				var error_code = $(".error_code").val();
				if(error_code=='10001'){
					motify.log("不能绑定业主本人");
					return false;
				}

				var name = $(".user-name-info").val();
				if(name==''){
					motify.log("当前手机号未注册，请先注册，再进行绑定！");
					return false;
				}

				var phone = $(".bind-relatives-tel").val();
				if(phone==''){
					motify.log("请填写亲属手机号码");
					return false;
				}
				$("#submitFrom").submit();	
			});
			 
			//搜索用户 blur
			$(".bind-relatives-tel").on('blur' , function(){
				var Tel = $(this).val();
				if(Tel.length != 11){
					$(".user-info-view").html('');
					$(".user-name-info").val('');
					return false;	
				}else{
					//获取输入手机号码的用户信息
					$.post("{pigcms{:U('ajax_empty_bind_relatives_user')}" , {relatives_user:Tel} , function(dataVal){
						
						if(dataVal=='10001'){
							motify.log('不能绑定业主本人');
							$(".user-info-view").html('');
							$(".user-name-info").val('');
							$(".error_code").val(dataVal);
							return false;	
						}else{
							var tpl = $("#load_user_info_view").html(); //读取模版
							laytpl(tpl).render(dataVal, function(html){
							  $(".user-info-view").html(html);
							  $(".user-name-info").val(dataVal.truename ? dataVal.truename : dataVal.nickname);
							});	
							$(".error_code").val('');
						}
						
							
					},"json");	
				}
			});
			//搜索用户 keyup
			$(".bind-relatives-tel").on('keyup' , function(){
				var Tel = $(this).val();
				if(Tel.length != 11){
					$(".user-info-view").html('');
					$(".user-name-info").val('');
					return false;	
				}else{
					//获取输入手机号码的用户信息
					$.post("{pigcms{:U('ajax_empty_bind_relatives_user')}" , {relatives_user:Tel} , function(dataVal){
						
						if(dataVal=='10001'){
							motify.log('不能绑定业主本人');
							$(".user-info-view").html('');
							$(".user-name-info").val('');
							return false;	
						}else{
							var tpl = $("#load_user_info_view").html(); //读取模版
							laytpl(tpl).render(dataVal, function(html){
							  $(".user-info-view").html(html);
							  $(".user-name-info").val(dataVal.truename ? dataVal.truename : dataVal.nickname);
							});	
						}	
					},"json");	
				}
			});
			
		</script>
        
        
    </body>
</html>