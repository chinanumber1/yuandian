<include file="Public:header"/>

<div class="main-content">
<style type="text/css">
.form-group{border:1px solid #c5d0dc;padding:10px;margin:10px}
.form-group div p span{ color:green;}
.page-content div{ float:left}
</style>


<!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">业主数据</li>
        </ul>
    </div>
	
	<div class="form-group">
			<div><p>房屋总数：<span>{pigcms{$count_user}</span>套</p></div>
	</div>
	
	<div class="page-content">
		<div id="user_echarts" style="width: 600px;height:400px;"></div>
		<div id="park_echarts" style="width: 600px;height:400px;"></div>
	</div>
</div>
<script type="text/javascript">
var user_echarts = echarts.init(document.getElementById('user_echarts'));
var park_echarts = echarts.init(document.getElementById('park_echarts'));
user_option = {
	 title: {
		text: '业主分析表',
		left: 'center',
		top: 20,
		textStyle: {
			color: '#000'
		}
	},
	 tooltip : {
		trigger: 'item',
		formatter: "{a} <br/>{b} : {c} ({d}%)"
	},
	
	series : [
		{
			name: '',
			type: 'pie',
			radius : '55%',
			avoidLabelOverlap: false,
			selectedMode: 'multiple',
			center: ['50%', '50%'],
			 label: {
				normal: {
					position: 'inner'
				}
			},
			labelLine: {
				normal: {
					show: false
				}
			},
			data:[
				{value:{pigcms{$wx_user_count}, name:'绑定平台业主',itemStyle:{normal:{color:'#3CB9B3'}},selected:true},
				{value:{pigcms{$count_user - $wx_user_count}, name:'未绑定平台业主',itemStyle:{normal:{color:'#CCC'}}},
			],
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

park_option = {
	 title: {
		text: '业主停车位分析表',
		left: 'center',
		top: 20,
		textStyle: {
			color: '#000'
		}
	},
	 tooltip : {
		trigger: 'item',
		formatter: "{a} <br/>{b} : {c} ({d}%)"
	},
	
	series : [
		{
			name: '',
			type: 'pie',
			radius : '55%',
			avoidLabelOverlap: false,
			selectedMode: 'multiple',
			center: ['50%', '50%'],
			 label: {
				normal: {
					position: 'inner'
				}
			},
			labelLine: {
				normal: {
					show: false
				}
			},
			data:[
				{value:{pigcms{$part_count}, name:'有',itemStyle:{normal:{color:'#3CB9B3'}},selected:true},
				{value:{pigcms{$count_user - $part_count}, name:'无',itemStyle:{normal:{color:'#CCC'}}},
			],
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
user_echarts.setOption(user_option);
park_echarts.setOption(park_option);
</script>
<include file="Public:footer"/>
