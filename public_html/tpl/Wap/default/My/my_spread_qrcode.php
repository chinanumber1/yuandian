<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>推广海报</title>
<link href="{pigcms{$static_path}css/spread_hb.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body style="background: #f0efed;">
    <section class="code">
        <div class="code_top">
            <div class="code_input clr">
                <input type="text" name="url" id="url" class="fl" value="平台首页">
                <div class="del fr">清除</div>   
            </div>
            <p class="remind">复制任意页面地址即可生成推广二维码</p>
            <a href="javascript:void(0)" class="make">点击生成二维码</a>  
        </div>
        <div class="code_end">   
            <ul class="clr">
                <li> 
                  <span style="background: url({pigcms{$static_path}images/my_07.png) center  no-repeat; background-size: 24px;"></span>
                  <p class="text">分享推广二维码给小伙伴</p> 
                </li>
                <li> 
                  <span style="background: url({pigcms{$static_path}images/my_09.png) center  no-repeat; background-size: 24px;"></span>
                  <p class="text">小伙伴就与你绑定推广关系</p> 
                </li>
                <li> 
                  <span style="background: url({pigcms{$static_path}images/my_11.png) center  no-repeat; background-size: 24px;"></span>
                  <p class="text">获得小伙伴消费订单金额一定比例的推广<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if></p> 
                </li>
            </ul>
        </div>
        <div class="ul_list">
            <ul>
                <li>
                    <div class="ul_top">1级推广<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例</div>
                    <div class="ul_end clr">
                        <div class="jdt fl" data-num="100">
                            <div class="jdt_n" data-num="{pigcms{$config.user_spread_rate}"></div>
                        </div>
                        <span class="bfb fr"><i>{pigcms{$config.user_spread_rate}</i>%</span>
                    </div>
                </li>
                <li <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
                    <div class="ul_top">2级推广<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例</div>
                    <div class="ul_end clr">
                        <div class="jdt fl" data-num="100">
                            <div class="jdt_n" data-num="{pigcms{$config.user_first_spread_rate}"></div>
                        </div>
                        <span class="bfb fr"><i>{pigcms{$config.user_first_spread_rate}</i>%</span>
                    </div>
                </li>
                <li <if condition="$config.open_extra_price eq 1 OR  C('config.user_third_level_spread') eq 0">style="display:none"</if>>
                    <div class="ul_top">3级推广<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例</div>
                    <div class="ul_end clr">
                        <div class="jdt fl" data-num="100">
                            <div class="jdt_n" data-num="{pigcms{$config.user_second_spread_rate}"></div>
                        </div>
                        <span class="bfb fr"><i>{pigcms{$config.user_second_spread_rate}</i>%</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</body>
{pigcms{$hideScript}
</html>

<script type="text/javascript">
	$(function(){
		// $("#url").change(function(event) {
		$('.make').click(function(event){
			var url = $("#url").val();
			var uid = {pigcms{$uid};
			
			if(url=='平台首页'){
				url = "{pigcms{:C('config.site_url')}/wap.php";
			}
			get_code(url,uid);
		});
	});
	
	$('#url').blur(function(){
		var url = $("#url").val();
		if(url==''){
			 $("#url").val('平台首页');
		}
	});
	
	function get_code(url,uid){
		$.post("{pigcms{:U('My/get_spread_qrcode',array('qrcode_id'=>$uid))}", {url:url,uid:uid}, function(data, textStatus, xhr) {
			data = eval('('+data+')');

			if(!data.error_code){
				location.href='{pigcms{:U('My/my_spread_hb')}&id='+data.id;
			}else{
				alert(data.msg);
			}
		});
	}
    
    //清除
    $(".del").click(function(){
        $(this).siblings("input").val("");
    })


    $(".ul_end").each(function(){
        var jdt = $(this).find(".jdt");
        var num1=jdt.data("num");
        var w=jdt.width();
        var jdt_n = $(this).find(".jdt_n");
        var num2=jdt_n.data("num");
        jdt_n.width(w*(num2/num1));
        //$(this).find("i").text(((num2/num1)*100).toFixed(2))

    })
</script>



