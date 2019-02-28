<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>自提地址</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
	    .address-container {
	        font-size: .3rem;
	        -webkit-box-flex: 1;
	    }
	    .kv-line h6 {
	        width: 4em;
	    }
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    .address-wrapper a {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .address-select {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	        padding-right: .2rem;
	        -webkit-box-align: center;
	        -webkit-box-pack: center;
	        -moz-box-align: center;
	        -moz-box-pack: center;
	        -ms-box-align: center;
	        -ms-flex-pack: justify;
	    }
	
	    .list.active dd {
	        background-color: #fff5e3;
	    }
	
	    .confirmlist {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .confirmlist li {
	        -ms-flex: 1;
	        -moz-box-flex: 1;
	        -webkit-box-flex: 1;
	        height: .88rem;
	        line-height: .88rem;
	        border-right: 1px solid #C9C3B7;
	        text-align: center;
	    }
	
	    .confirmlist li a {
	        color: #2bb2a3;
	    }
	
	    .confirmlist li:last-child {
	        border-right: none;
	    }
	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
      
		<volist name="pick_list" id="vo">
			<dl class="list <if condition="$vo['default']">active</if>">
		        <dd class="address-wrapper <if condition="!$vo['select_url']">dd-padding</if>">
		        	<if condition="$vo['select_url']">
		           		<a class="react" href="{pigcms{$vo.select_url}">
		                <div class="address-select"><input class="mt" type="radio" name="addr" <if condition="$vo['pick_addr_id'] eq $_GET['pick_addr_id']">checked="checked"</if>/></div>
			         </if>
			            <div class="address-container">
			                <div class="kv-line">
			                    <h6>市区：</h6><p>{pigcms{$vo.area_info.province} {pigcms{$vo.area_info.city} {pigcms{$vo.area_info.area} </p>
			                </div>
			                <div class="kv-line">
			                    <h6>手机：</h6><p>{pigcms{$vo.phone}</p>
			                </div>
			                <div class="kv-line">
			                    <h6>地址：</h6><p>{pigcms{$vo.name}</p>
			                </div><div class="kv-line">
			                    <h6>距离: </h6><p style="color:green">{pigcms{$vo.distance}</p>
			                </div>
			               
							
			            </div>
			        <if condition="$vo['select_url']">
		            	</a>
		            </if>
		        </dd>
		       
		    </dl>
	    </volist>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(function(){
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm('您确定要删除此地址吗？')){
						$.post(now_dom.attr('href'),function(result){
							if(result.status == '1'){
								now_dom.closest('dl').remove();
							}else{
								alert(result.info);
							}
						});
					}
					return false;
				});
				$('.address-wrapper input.mt').click(function(){
					window.location.href = $(this).closest('a').attr('href');
				});
			});
		</script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
</body>
</html>