<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">报表统计</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink" data-url="{pigcms{:U('shop_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('goods')}">
						<div class="icon list"></div>
						<div class="text">待发商品清单</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('goods_sale')}">
						<div class="icon list"></div>
						<div class="text">商品销售统计</div>
					</li>
                    <if condition="$config['eleme_app_key'] OR $config['meituan_sign_key']">
                    <li class="urlLink cur" data-url="{pigcms{:U('shop_order_report_form')}">
                        <div class="icon list"></div>
                        <div class="text">各平台订单统计</div>
                    </li>
                    </if>
					<if condition="$config['pay_in_store']">
					<li class="urlLink" data-url="{pigcms{:U('market')}">
						<div class="icon list"></div>
						<div class="text">线下零售</div>
					</li>
					</if>
				</ul>
			</div>
			<div class="rightMain">
				<div class="alert alert-block alert-success" style="position:relative;">
					<span>时间筛选：</span>
					<div style="display:inline-block;">
						<select class='custom-date' id="time_value" name='select'>
							<option  value='1'>今天</option>
							<option selected='selected' value='7'>7天</option>
							<option value='30'>30天</option>
							<option value='180'>180天</option>
							<option value='365'>365天</option>
							<option value='custom'>自定义</option>
						</select>
					</div>
					<input type="button" value="查询" class="button" id="time" style="width: 60px;height: 25px;"/>
				</div>
				<div class="grid-view">
					<div class="widget-box">
						<div class="widget-body" id="main" style="text-align:center;">
							<div style="float:left;width:600px;height:400px;" id="behavior_chart">
							</div>
							<div style="float:left;width:600px;height:400px;" id="sex_chart">
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		</div>
	</body>
    <script src="./static/js/echarts.min.js"></script>
    
<script>
var day = '', period = '', type = '{pigcms{$type}', option = null;
$(document).ready(function(){
	$('#time').click(function(){
		int_html();
	});
	option = {
	        title : {
	            text: '各个平台的订单',
	            subtext: '',
	            x:'center'
	        },
	        tooltip : {
	            trigger: 'item',
	            formatter: "{a} <br/>{b} : {c} ({d}%)"
	        },
	        legend: {
	            orient: 'vertical',
	            left: 'left',
	            data: []
	        },
	        series : [
	            {
	                name: '',
	                type: 'pie',
	                radius : '55%',
	                center: ['50%', '60%'],
	                data:[],
	                itemStyle: {
	                    emphasis: {
	                        shadowBlur: 10,
	                        shadowOffsetX: 0,
	                        shadowColor: 'rgba(0, 0, 0, 0.5)'
	                    }
	                }
	            }
	        ]
	    };
	int_html();
});


function int_html()
{
	if($('#time_value option:selected').attr('value')=='custom'){
		period = $('#time_value option:selected').html();
	}else{
		day = $('#time_value option:selected').attr('value');
	}
	var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
	$.post('{pigcms{:U("Store/ajaxShopOrder")}', {'type':type, 'day':day, 'period':period}, function(response){
		if (response.error_code) {
		} else {
            option.legend.data = response.plat;
            option.title.subtext = '销售数量';
            option.series[0].name = '订单数量';
            option.series[0].data = response.count;
            
        	echarts.init(document.getElementById('behavior_chart')).setOption(option);

            option.title.subtext = '销售总金额';
            option.series[0].name = '销售总金额';
            option.series[0].data = response.price;
        	echarts.init(document.getElementById('sex_chart')).setOption(option);
			layer.closeAll('loading');
		}
	},'json');
}
</script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
</html>