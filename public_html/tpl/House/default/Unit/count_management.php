<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">统计管理</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group" style="margin-left:7%">
									<div id="main1" style="width: 500px;height:430px;float:left;"></div>
									<div id="main5" style="width: 500px;height:430px;float:left;"></div>
									<div id="main2" style="width: 500px;height:430px;float:left;"></div>
									<div id="main3" style="width: 500px;height:430px;float:left;"></div>
									<!--<div id="main4" style="width: 460px;height:430px;float:left;"></div>-->
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){ 
		map1();
		map2();
		map3();
		// map4();
		map5();
	});	
	//房屋统计
	function map1(){
		var myChart = echarts.init(document.getElementById('main1'));
        var option = {
		    aria: {
		        show: true
		    },
		    tooltip : {
		        trigger: 'item',
		    },
		    title: {
		        text: '房屋统计',
		        x: 'center'
		    },
		    series: [
		        {
		            name: '',
		            type: 'pie',
		            selectedMode: 'single',
		            data: [
		                { value: {pigcms{$room_count.total_count}, name: '空置房屋数' },
		                { value: {pigcms{$room_count.bind_count}, name: '入住房屋数' },
		            ]
		        }
		    ],
		    itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            color:['#27C24C', '#CCCCCC']
		};
        myChart.setOption(option);
	}
    //车位统计
    function map2(){
    	var myChart = echarts.init(document.getElementById('main2'));
        var option = {
		    aria: {
		        show: true
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    title: {
		        text: '车位统计',
		        x: 'center'
		    },
		    series: [
		        {
		            name: '',
		            type: 'pie',
		            selectedMode: 'single',
		            data: [
		                { value: {pigcms{$position_count.yes_bind}, name: '已绑定车位' },
		                { value: {pigcms{$position_count.no_bind}, name: '未绑定车位' },
		            ]
		        }
		    ],
		    
            color:['#3CB9B3', '#F45947','#666ECA','#CCCCCC']
		};
        myChart.setOption(option);
    }
    //欠费统计
    function map3(){
    	var myChart = echarts.init(document.getElementById('main3'));
        var option = {
		    aria: {
		        show: true
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    title: {
		        text: '欠费统计',
		        x: 'center'
		    },
		    series: [
		        {
		            name: '',
		            type: 'pie',
		            selectedMode: 'single',
		            data: [
		                { value: {pigcms{$pay_count.water_price}, name: '水费' },
		                { value: {pigcms{$pay_count.electric_price}, name: '电费' },
		                { value: {pigcms{$pay_count.gas_price}, name: '燃气费' },
		                { value: {pigcms{$pay_count.park_price}, name: '停车费' },
		                { value: {pigcms{$pay_count.property_price}, name: '物业费' },
		                { value: {pigcms{$pay_count.cunstom_money}, name: '自定义项费' },
		            ]
		        }
		    ],
		    
            color:['#159DCD' ,'#A7CD15', '#47CD15','#15CDC4','#1555CD']
		};
        myChart.setOption(option);
    }

    //收入统计
   /* function map4(){
		var myChart = echarts.init(document.getElementById('main4'));
        var option = {
		    aria: {
		        show: true
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    title: {
		        text: '收入统计',
		        x: 'center'
		    },
		    series: [
		        {
		            name: '',
		            type: 'pie',
		            selectedMode: 'single',
		            data: [
		                { value: {pigcms{$pay_order.water}, name: '水费' },
		                { value: {pigcms{$pay_order.electric}, name: '电费' },
		                { value: {pigcms{$pay_order.gas}, name: '燃气费' },
		                { value: {pigcms{$pay_order.custom}, name: '自定义缴费' },
		                { value: {pigcms{$pay_order.property}, name: '物业费' },
		            ]
		        }
		    ],
		    
		};
        myChart.setOption(option);
	}*/

	//业主统计
	function map5(){
		var myChart = echarts.init(document.getElementById('main5'));
        var option = {
		    aria: {
		        show: true
		    },
		    tooltip : {
		        trigger: 'item',
		        // formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    title: {
		        text: '业主统计',
		        x: 'center'
		    },
		    series: [
		        {
		            name: '',
		            type: 'pie',
		            selectedMode: 'single',
		            data: [
		                { value: {pigcms{$user_bind.owner}, name: '房主' },
		                { value: {pigcms{$user_bind.family}, name: '家人' },
		                { value: {pigcms{$user_bind.tenant}, name: '租客' },
		                { value: {pigcms{$user_bind.new_owner}, name: '更新房主' },
		            ]
		        }
		    ],
		    
		};
        myChart.setOption(option);
	}
</script>

<include file="Public:footer"/>