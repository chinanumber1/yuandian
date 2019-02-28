<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>成为{pigcms{$config.distributor_alias_name}</title>
<link href="{pigcms{$static_path}css/spread_hb.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<style>


.code_n .pd15 {
	padding: 20px 50px;
}
p{
	font-size: 22px;
    padding: 13px;
}
.code_n .trade span {
	padding:0px;
}

.layermcont p{
  font-size:14px;
}
</style>
</head>
<body style="background: #f0efed;">
    <section class="code_n">
		<p>开通{pigcms{$config.distributor_alias_name}费用：<em style="color:red">{pigcms{$config.buy_distributor_money|floatval}元</em></p>
        <div class="pd15" style="height: 322px;">
            <img src="{pigcms{$adver_agent.pic}" width="100%" height="100%">
        </div>
		<div class="keep clr">
      
            <span class="span_14"><input name="agree" id="agree" value="1" type="checkbox" checked><a class="agent_rule" href="javascript:void(0)">我已阅读并且同意<font color="red">《{pigcms{$config.distributor_alias_name}协议》</font></a></span>
        </div>
        <div class="trade" id="trade">
            <span>开通{pigcms{$config.distributor_alias_name}</span>
        </div>
        
    </section>
	<div id="agent_rule_html" class="hide">
		{pigcms{$config.distributor_rule|html_entity_decode}
	</div>
</body>
{pigcms{$hideScript}
</html>

<script>
     // 图片比例
    // $(".pd15 img").each(function(){
        // $(this).height($(this).width()*1.172)
    // })
	$(function(){
		$('.agent_rule').click(function(){
			layer.open({
				content:$('#agent_rule_html').html(),
				style:' height:400px; width:300px;     overflow: auto;'
			});
		})
		$('#trade').click(function(){
			window.location.href="{pigcms{:U('buy',array('type'=>1))}&agree="+$('input[name="agree"]:checked').val();
		})
	})
</script>



