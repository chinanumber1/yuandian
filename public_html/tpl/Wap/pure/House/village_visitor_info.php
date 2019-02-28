<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<section class="query-container">
						<div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" value="{pigcms{$detail.visitor_name}" readonly/>
							<span class="nametip">访客姓名</span>
						</div>
						<div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" value="{pigcms{$detail.visitor_phone}" readonly/>
							<span class="nametip">访客手机号</span>
						</div>
                        <div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" value="{pigcms{$detail.add_time|date='Y-m-d h:i:s',###}" readonly/>
							<span class="nametip">访客访问时间</span>
						</div>
                        
                         <div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" value="{pigcms{$detail.owner_address}" readonly/>
							<span class="nametip">访问业主小区地址</span>
						</div>
                        
                        <if condition='$detail["pass_time"]'>
                             <div class="area_input" style="margin-top:15px;">
                                <input type="tel" class="recharge_txt" value="{pigcms{$detail.pass_time|date='Y-m-d h:i:s',###}" readonly/>
                                <span class="nametip">业主放行时间</span>
                            </div>
                        </if>
                        
					<if condition='!$detail["pass_time"]'>
						<div style="margin-top:15px; color:#888888; text-align:center">请及时进行确认处理</div>
						<div class="area_btn"><input type="button" id="recharge_btn" value="确认放行" /></div>
                    </if>
				</section>
                
                
				
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
				
			</div>
		</div>
		{pigcms{$shareScript}
        <script type="text/javascript">
        $('#recharge_btn').click(function(){
			var village_visitor_info_url = "__SELF__";
			var id = "{pigcms{$_GET['id']}";
			$.post(village_visitor_info_url,{'id':id,'status':1},function(data){
				if(data.err_code == -1){
					motify.log(data.err_msg);
					setTimeout(function(){
						location.href=data.err_url;
					},3000)
				}else{
					motify.log(data.msg);
					location.href = village_visitor_info_url;
				}
			},'json')
		});
        </script>
	</body>
</html>