<include file="Public:header" />

<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<script src="{pigcms{$static_path}/js/echarts.min.js"></script>

	<script type="text/javascript">
		parentShowIndex = true;
	</script>
	<div class="mainbox">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/main.css" />
		<div id="nav" class="mainnav_title">
		
			<a href="{pigcms{:U('Index/main')}" class="on">网站概况</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
			<if condition="$now_area['area_type'] neq 3">
				<span>地区筛选：</span>
				<div id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}" area_id="{pigcms{$_GET.area_id}" style="display:inline"></div>
			</if>
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
			<input type="button" value="筛选报表" class="button" id="time"/>
		</div>
		<div class="topTable" id="topTable">
			<div class="echart" id="echart"></div>
			<div class="chartFooter" id="chartFooter">
				<ul>
					<li class="all active" data-type="all">全部</li>
					<li class="group" data-type="group">{pigcms{$config.group_alias_name}</li>
					<li class="shop" data-type="shop">{pigcms{$config.shop_alias_name}</li>
					<li class="meal" data-type="meal">{pigcms{$config.meal_alias_name}</li>
					<if condition="$config['appoint_page_row']">
						<li class="appoint" data-type="appoint">{pigcms{$config.appoint_alias_name}</li>
					</if>
					<if condition="$config['is_cashier'] OR $config['pay_in_store']">
						<li class="store" data-type="store">到店</li>
					</if>
					<if condition="$config['is_open_weidian']">
						<li class="weidian" data-type="weidian">微店</li>
					</if>
					<if condition="$config['wxapp_url']">
						<li class="wxapp" data-type="wxapp">营销</li>
					</if>
					<!--li>营销</li-->
				</ul>
			</div>
			<div class="chartWidget" id="chartWidget">
				<div class="chartData" style="height:184px">
					<div class="chartDataCon" id="chartDataCon" style="padding-top: 10px;   ">
						<p style="line-height:0.5em; margin-left: -20px;"><em style="background:url({pigcms{$static_path}images/notice.png)  center no-repeat;    display: inline-block;width: 15px;height: 15px;background-size: contain;vertical-align: middle;margin-right: 3px;"></em>随业务、区域、时间段筛选变化</p>
						<p style="line-height:0.5em">订单量总数：<span id="orderCountNum"></span></p>
						<p style="line-height:0.5em">消费量总数：<span id="consumeCountNum"></span></p>
						<p style="line-height:0.5em">系统抽成：<span id="systemCountNum"></span></p>
						
						<p style="line-height:0.5em"></p>
						<p style="line-height:0.5em; margin-left: -20px;"><em style="background:url({pigcms{$static_path}images/notice.png)  center no-repeat;    display: inline-block;width: 15px;height: 15px;background-size: contain;vertical-align: middle;margin-right: 3px;"></em>随区域、时间段筛选变化</p>
						<p style="line-height:0.5em">微信支付总金额：<span id="weixinPaymoney"></span>元</p>
						<p style="line-height:0.5em">支付宝支付总金额：<span id="alipayPaymoney"></span>元</p>
						<p style="line-height:0.5em;display:none">配送订单总数：<span id="deliver_count"></span></p>
						<p style="line-height:0.5em;display:none">配送订单总金额:<span id="deliver_money"></span>元</p>
					</div>
				</div>
				<div class="chartDataDown">
					<input type="button" class="chartDataDownBtn" id="chartDataDownBtn" onclick="exports();" value="下载报表"/>
				</div>
			</div>
		</div>
		<div class="bottomTable" id="bottomTable" style="margin-top:5px;">
			<div class="box" style="width:35%;" id="bottomTableLeft">
				<div class="top">数据总览</div>
				<div class="body">
					<div>
						<ul>
							<li><b>收入总数</b><br><span>￥{pigcms{$website_collect_count}</span></li>
							<li><b>用户总数</b><br><span>{pigcms{$website_user_count}</span></li>
							<li><b>商户总数</b><br><span>{pigcms{$website_merchant_count}</span></li>
							<li><b>店铺总数</b><br><span>{pigcms{$website_merchant_store_count}</span></li>
							<li><b>{pigcms{$config.group_alias_name}总数</b><br><span>{pigcms{$group_group_count}</span></li>
							<li><b>{pigcms{$config.meal_alias_name}店铺总数</b><br><span>{pigcms{$meal_store_count}</span></li>
							<li><b>{pigcms{$config.shop_alias_name}店铺总数</b><br><span>{pigcms{$shop_store_count}</span></li>
							<li><b>{pigcms{$config.appoint_alias_name}总数</b><br><span>{pigcms{$appoint_group_count}</span></li>
							<li><a href="{pigcms{:U('Merchant/wait_merchant')}"><b style="color:#CC3366;">待审核商家数</b><br><span style="color:#CC3366;">{pigcms{$merchant_verify_count}</span></a></li>
							<li><a href="{pigcms{:U('Merchant/wait_store')}"><b style="color:#CC3366;">待审核店铺数</b><br><span style="color:#CC3366;">{pigcms{$merchant_verify_store_count}</span></a></li>
							<li><a href="{pigcms{:U('Group/wait_product')}"><b style="color:#CC3366;">待审核{pigcms{$config.group_alias_name}数</b><br><span style="color:#CC3366;">{pigcms{$group_verify_count}</span></a></li>
							<li><b></b><br><span></span></li>
						</ul>
					</div>
					<div style="clear:both;"></div>
				</div>
				<div class="top" >商家余额<!--(商家总余额：{pigcms{$mer_money.all_mer_money} 元,商家待提现：{pigcms{$mer_money['all_need_pay']} 元)--></div>				
				<div class="body">
					<div id="merchantMoneyEcharts" style="background:#3486AC; /* margin: 0 auto; */">
						
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="box" style="border-left:1px solid #f1f1f1;width:64.5%;" id="bottomTableRight">
				<div class="top">用户分析</div>
				<div class="body" id="userEcharts">
					<div id="userSexEcharts" >
						
					</div>
					<div id="userWechatEcharts" >
						
					</div>
					<div id="userPhoneEcharts" >
						
					</div>
					<div id="userAppEcharts" >
						
					</div>
					<p style="clear:both;"></p>
				</div>
			</div>
			<p style="clear:both;"></p>
		</div>
	</div>
	<script type="text/javascript">
		var echart_data =null;
		$(function(){
			var all_date;
			windowResize();
			$(window).resize(function(){
				windowResize();
			});
			// $.post('{pigcms{:U('Index/ajax_all_date')}', {}, function(data, textStatus, xhr) {
				// echart_data = data;
				// show_chart();
			// });
			$('.chartFooter li').mouseover(function() {
				var type = $(this).attr('data-type');
				$('.chartFooter li').removeClass('active')
				$(this).addClass('active');
				show_chart(type);
				
			})
			
			$('#time').click(function(){
				var day='';
				var period='';
				if($('#time_value option:selected').attr('value')=='custom'){
					period = $('#time_value option:selected').html();
				}else{
					day = $('#time_value option:selected').attr('value');
				}
				
				$.ajax({
					url: '{pigcms{:U('Index/ajax_all_date')}',
					type: 'POST',
					dataType: 'json',
					data: {day: day,period:period,province_idss:$('#choose_provincess').val(),city_idss:$('#choose_cityss').val(),area_id:$('#choose_areass').val()},
					success:function(date){
						if(typeof(date.error_code)!='undefined'&&date.error_code){
							parent.msg(data.msg);
						}else{
							echart_data = date;
							//console.log(echart_data);
							show_chart();
						}
					}
				});	
			});
			$('#time').trigger('click');
			
		});
		
		function exports(){
			if($('#time_value option:selected').attr('value')=='custom'){
				period = $('#time_value option:selected').html();
				var export_url ="{pigcms{:U('Index/ajax_all_date')}&period="+period+'&type='+$('.chartFooter .active').attr('data-type');
			}else{
				day = $('#time_value option:selected').attr('value');
				var export_url ="{pigcms{:U('Index/ajax_all_date')}&day="+day+'&type='+$('.chartFooter .active').attr('data-type');
			}
			window.location.href = export_url;
		}
	   
		
		function windowResize(){
			$('#topTable').height(($(window.parent).height()-50)/2);
			$('#echart').height($('#topTable').height()-80);
			
			$('#chartFooter ul ').width($('#chartFooter').width()-220);
			$('#echart').width($('#chartFooter').width()-220);
			
			var echartSize = $('#chartFooter ul li').size();
			$('#chartFooter').addClass('size-'+echartSize);
			
			/* $('#bottomTable').css('min-height',$(window).height() - ($(window.parent).height()-50)/2); */
			
			
			var merchantMoneyEchartsWidth = ($('#bottomTableLeft').width()-20)*0.8;
			if(merchantMoneyEchartsWidth > 360){
				merchantMoneyEchartsWidth = 360;
			}
			$('#merchantMoneyEcharts').height(merchantMoneyEchartsWidth);
			$('#merchantMoneyEcharts').width(merchantMoneyEchartsWidth);
			
			
			var merchantUserEchartsWidth = ($('#bottomTableRight').width())*0.4;
			if(merchantUserEchartsWidth > 360){
				merchantUserEchartsWidth = 360;
			}
			$('#userEcharts div').height(merchantUserEchartsWidth);
			$('#userEcharts div').width(merchantUserEchartsWidth);
			$('#userEcharts div:odd').css('margin-right','0px');
			
			if(echart_data!=null){
				show_chart($('.chartFooter .active').attr('data-type'));
			}
			//数据分析
			var charts = Object();
			//var pay_date = echart_data['pay_type'];
	
			charts = {
				weixin:{name:'userWechatEcharts',title:'绑定微信分析',part1:{value:'{pigcms{$user.weixin}',name:'已绑定',color:'#27c24c'},part2:{value:'{pigcms{$user['user_count']-$user['weixin']}',name:'未绑定',color:'#CCC'}},
				phone:{name:'userPhoneEcharts',title:'绑定手机分析',part1:{value:'{pigcms{$user.phone}',name:'已绑定',color:'#3CB9B3'},part2:{value:'{pigcms{$user['user_count']-$user['phone']}',name:'未绑定',color:'#CCC'}},
				//paytype:{name:'userPaytypeEcharts',title:'微信支付宝支付数据分析',part1:{value:pay_date.weixin,name:'微信支付',color:'#27c24c'},part2:{value:pay_date.alipay,name:'支付宝支付',color:'#CCC'}},
				<if condition="C('config.pay_weixinapp_open')">app:{name:'userAppEcharts',title:'APP用户分析',part1:{value:'{pigcms{$user.app}',name:'使用',color:'#E37979'},part2:{value:'{pigcms{$user['user_count']-$user['phone']}',name:'未使用',color:'#D9D154'}},</if>
			}
			var sex = Object();
			sex = {name:'userSexEcharts',title:'用户性别分析',part1:{value:'{pigcms{$user.men}',name:'男性',color:'#00A79D'},part2:{value:'{pigcms{$user.women}',name:'女性',color:'#ED0B5F'},part3:{value:'{pigcms{$user.unknow_user}',name:'未知',color:'#FC9F1E'}},
				
			$.each(charts, function(index, val) {
				var index  = echarts.init(document.getElementById(val.name));
				option = {
					 title: {
						text: val.title,
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
					// legend: {
						// orient: 'vertical',
						// left: 'left',
						// data: [val.part1.name,val.part2.name]
					// },
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
								{value:val.part1.value, name:val.part1.name,itemStyle:{normal:{color:val.part1.color}},selected:true},
								{value:val.part2.value, name:val.part2.name,itemStyle:{normal:{color:val.part2.color}}},
								// {value:val.part3.value, name:val.part3.name,itemStyle:{normal:{color:val.part3.color}}},
								// {value:val.part4.value, name:val.part4.name,itemStyle:{normal:{color:val.part4.color}}},
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
				
			  index.setOption(option);
			});
			
			var sexs  = echarts.init(document.getElementById(sex.name));
				option_sex = {
					 title: {
						text: sex.title,
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
					// legend: {
						// orient: 'vertical',
						// left: 'left',
						// data: [val.part1.name,val.part2.name]
					// },
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
								{value:sex.part1.value, name:sex.part1.name,itemStyle:{normal:{color:sex.part1.color}}},
								{value:sex.part2.value, name:sex.part2.name,itemStyle:{normal:{color:sex.part2.color}},selected:true},
								{value:sex.part3.value, name:sex.part3.name,itemStyle:{normal:{color:sex.part3.color}}},
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
				
			  sexs.setOption(option_sex);
			
			var mer = Object();
			mer = {name:'merchantMoneyEcharts',title:'待提现金额占比',
				part1:{
					value:'{pigcms{$mer_money.all_money}',name:'商家总余额',color:'red'
				},
				// part2:{
					// value:'{pigcms{$mer_money.all_mer_money}',name:'商家总余额',color:'blue'
				// },
				part3:{
					value:'{pigcms{$mer_money['all_need_pay']/100}',name:'待提现金额',color:'green'
				},
				// part4:{
					// value:'{pigcms{$mer_money.all_count}',name:'女性用户',color:'yellow'
				// },
			};
			
			var mer_money  = echarts.init(document.getElementById(mer.name));
				option_mer = {
					title: {
						text: mer.title,
						x: 'center',
						y: 'center',
						itemGap: 20,
						textStyle : {
							color : 'red',
							fontFamily : '微软雅黑',
							fontSize : '14',
							fontWeight : 'bold'
						}
					},
					
					tooltip: {},
					series : [
						{
							name:'',
							type:'pie',
							radius: ['50%', '70%'],
							avoidLabelOverlap: false,
							center: ['50%', '50%'], 
							 label: {
								normal: {
									textStyle: {
										fontSize: 12,
										color: '#235894'
									}
								}
							},
							
							 labelLine: {
								normal: {
									lineStyle: {
										color: '#235894'
									}
								}
							},
							data:[
								{value:mer.part1.value, name:mer.part1.name},
								{
									value:mer.part3.value, 
									name:mer.part3.name,
									selected:true
								},
							
							],
						}
					]
				};
				
			  mer_money.setOption(option_mer);
		}

		
		function show_chart(type){
			if(!type){
				type='all';
			}
			var data = echart_data;
			
			$('#orderCountNum').html(data.count.sales_count[type]);
			$('#consumeCountNum').html(data.count.consume[type]);
			if(data.system_take[type]){				
				$('#systemCountNum').html(data.system_take[type].toFixed(2)+'元');
			}else{
				$('#systemCountNum').html('0元');
			}
			$('#weixinPaymoney').html(data.weixin[type].toFixed(2));
			$('#alipayPaymoney').html(data.alipay[type].toFixed(2))
			$('#alipayPaymoney').html(data.alipay[type].toFixed(2))
			if(type=='shop'||type=='all'){
				$('#deliver_count').html(data.deliver.count);
				$('#deliver_money').html(data.deliver.money);
				$('#deliver_count').parent('p').show()
				$('#deliver_money').parent('p').show()
			}else{
				$('.chartWidget').css('height','254px');
				$('.chartWidget .chartData').css('height','184px');
				$('#deliver_count').parent('p').hide()
				$('#deliver_money').parent('p').hide()
			}
			
			
			var myCharts  = echarts.init(document.getElementById('echart'));
			
			var ob = new Array();
			var income = new Array();
			var mer_income = new Array();
			//console.log(data);
			var chart_title = data.alias_name[type];
			$.each(data.xAxis_txt,function(i,v){
				ob.push(v);
				income.push(data[type].income_txt[i]);
				mer_income.push(data[type].mer_income_txt[i]);
			});
		
			var myDate = new Date();
			var mytime=myDate.toLocaleString();  
			var subtxt = $('#time_value option:selected').html()+'  '+mytime;
			
			var option = {
				
				title : {
					text: chart_title+'数据分析',
					x:'left',
					textStyle:{
						color:'#fff'
					},
					padding:[
						20,10,10,40
					],
					
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['下单总金额','验证消费总金额'],
					textStyle:{
						color:'#fff'
					},
					padding:[
						20,10,10,40
					]
				},
				grid: {
					left: '40',
					right: '50',
					top: '70',
					bottom: '15',
					containLabel: true
				},
				toolbox: {
					// backgroundColor: '#fff', // 工具箱背景颜色
					padding: [5,50,5,10],
					itemGap:15,
					feature : {
						magicType: {
							show : true,
							title : {
								line : '折线图模式',
								bar : '柱形图模式',
								
							},
							type : ['line', 'bar'],
							icon : {
								'line':'image://{pigcms{$static_path}images/echarts_quxian.png',
								'bar':'image://{pigcms{$static_path}images/echarts_zhuzhuang.png',
							},
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						},
						restore : {
							show : true,
							title : '初始化报表',
							icon:'image://{pigcms{$static_path}images/echarts_refresh.png',
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						},
						saveAsImage : {
							show: true,
							title : '保存为图片',
							name : chart_title+'数据分析('+subtxt+')',
							// icon : chart_title+'数据分析('+subtxt+')',
							lang : ['点击保存'],
							icon:'image://{pigcms{$static_path}images/echarts_down.png',
							backgroundColor:'#029BDC',
							pixelRatio:2,
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						}
					},
					top:15
				},
				calculable : true,
				xAxis : 
					{
					
						type : 'category',
						boundaryGap : false,
						data :  ob,
						splitLine: {
							show: false
						},
						axisLine: {
							lineStyle: {
								color: '#fff' //坐标轴线颜色
							}
						},
					}
				,
				yAxis : [
					{
						type : 'value',
						splitLine: {
							show: false
						},
						axisLine: {
							lineStyle: {
								color: '#fff' //坐标轴线颜色
							}
						},	
					}
				],
				
				series : [
					{
						name:'下单总金额',
						type:'line',
						tiled: '总量',
						smooth:true,
						itemStyle : {
							normal : {
								color:'#C3E870',
								label : {
									show : true,
									formatter : '{c}',
									position : 'top'
								},
								lineStyle:{
									color:'#9CD028'
								},
								itemStyle: {
									normal: {
										color: '#fff'
									}
								},
								areaStyle: {type: 'default'}
							}
						},
						data: income
					},
					{
						name:'验证消费总金额',
						type:'line',
						tiled: '总量',
						smooth:true,
						itemStyle : {
							normal : {
								color:'#4BC490',
								label : {
									show : true,
									formatter : '{c}',
									position : 'top'
								},
								lineStyle:{
									color:'#4BC495'
								},
								areaStyle: {type: 'default'}
							}
						},
						data: mer_income
					},

				]
				
				 

			};
			
			myCharts.setOption(option);
		}
		
	</script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
	
<include file="Public:footer"/>