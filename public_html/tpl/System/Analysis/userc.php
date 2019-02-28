<include file="Public:header" />
    <!-- 内容头部 -->
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                    <ul>

                    </ul>
            </div>
            <div class="page-content">
                    <div class="page-content-area">
                            <div class="row">
                                    <div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
                                        <div class="widget-box">
                                            <div class="widget-header">
                                                    <h5 ><font id="note" color="red">用户统计是按地区统计的，用户必须要有完整的配送地址信息</font></h5>
                                                    <div class="year"></div>
                                                    <div class="month"></div>
                                            </div>
											
											<!--时间筛选-->
											<div id="period">
											<form id="myform" method="post" action="{pigcms{:U('Analysis/userc')}" >
												<input type="hidden" name="funcName" id="funcName" value="">
												<input type="hidden" name="type" id="type" value="">
												<input type="hidden" name="areaid" id="areaid" value="">
												<div class="express" style="display:none;"> 
												<font color="#000">小区列表:</font>
													<select name="village_id" id="village_id">
														<option value="0" selected="selected">所有小区</option>
														<volist name="village_list" id="vo">
															<option value="{pigcms{$vo.village_id}" >{pigcms{$vo.village_name}</option>
														</volist>
													
													</select>
												</div>
												<font color="#000">时间段：</font>
												<input type="text" class="input-text" name="begin_time" style="width:120px;" id="begin_time"  value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
											   
												<input type="text" class="input-text" name="end_time" style="width:120px;" id="end_time" value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
												<input type="button"  value="提交" class="button" onclick="formsend()">
											</form>
											</div>
											
                                            <div class="widget-body" id="main" style="text-align:center;">
                                                    <div style="float:left" id="user_chart">
                                                    </div>
                                                    <div style="float:left" id="rank">
                                                    </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <style type="text/css">
                                            table.gridtable {
                                                    font-family: verdana,arial,sans-serif;
                                                    font-size:11px;
                                                    color:#333333;
                                                    border-width: 1px;
                                                    border-color: #666666;
                                                    border-collapse: collapse;
                                            }
                                            table.gridtable th {
                                                    border-width: 1px;
                                                    padding: 8px;
                                                    border-style: solid;
                                                    border-color: #666666;
                                                    background-color: #dedede;
                                            }
                                            table.gridtable td {
                                                    border-width: 1px;
                                                    padding: 8px;
                                                    border-style: solid;
                                                    border-color: #666666;
                                                    background-color: #ffffff;
                                            }
                                            </style>
                                            <script type="text/javascript" src="{pigcms{$static_public}fushionCharts/FusionCharts.js"></script>
                                            <script type="text/javascript">
                                                var title = '';
                                                var star_year={pigcms{$star_year};
                                                var now = new Date();
                                                var now_year=now.getFullYear();
												
                                                var prefix = '';
                                                $(document).ready(function(){
                                                    $.ajax({
                                                        url:'/admin.php?g=System&c=Analysis&a=getmenu',
                                                        type:"post",
                                                        dataType:"JSON",
                                                        success:function(data){
                                                            $.each(data,function(func,value){
                                                                if(func=='getuserc'){
                                                                    $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxsend(\''+func+'\''+",'','',2016,''"+')" class="on">'+value+'</a>');
                                                                }else{
                                                                    $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxsend(\''+func+'\''+",'','',"+now_year+",''"+')">'+value+'</a>');
                                                                }
                                                            });
                                                        }
                                                    });
                                                    ajaxsend('getuserc','','',now_year,'');
                                                });
                                                function getyear(func,areaid,_type,year,month){
                                                    if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                        $('.year').empty();
                                                        var year_list='<div id="nav" class="mainnav_title"><ul>';
                                                        year_list+='<font color="#000">年 :</font>' ;
                                                        for(var year=star_year;year<=now.getFullYear();year++){
                                                            year_list+='<a href="JavaScript:void(0)" id="'+func+year+'" onclick="ajaxsend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"',''"+')" >'+year+'</a> | ';
                                                        }
                                                        year_list+='</ul></div>'
                                                        prefix = '￥';
                                                        $('.year').append(year_list);
                                                    }else{
                                                        prefix = '';
                                                        $('.year').empty();
                                                    }
                                                }
                                                function getmonth(func,areaid,_type,year,month){
                                                    if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                        $('.month').empty();
                                                        var now = new Date();
                                                        var month_list = '<div id="nav" class="mainnav_title"><ul>';
                                                        month_list+='<font color="#000">月 :</font>' ;
                                                        var month_end = year<now.getFullYear()?12:now.getMonth()+1;
                                                        for (var i = 1; i <= month_end; i++) {
                                                           month_list +='<a href="JavaScript:void(0)" id='+func+i+' onclick="ajaxsend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"','"+i + '\')" >'+i+'月 '+'</a>';
                                                        }
                                                        month_list+='</ul></div>';
                                                        $('.month').empty();
                                                        $('.month').append(month_list);
                                                    }else{
                                                        $('.month').empty();
                                                    }
                                                }
												function formsend(){
													var form_data=$('#myform').serializeArray();
													console.log(form_data)
													var send = new Array();
													$.each(form_data, function(i, field){
															send[field.name]=field.value;
													});
													if(!send['begin_time']==''&&!send['end_time']==''){
														if(send['begin_time']>send['end_time']){
															window.top.msg(0,"结束时间应大于开始时间",true);
														}else{
															if(send.funcName=='villagebasec'){
																send.type = send.village_id;
															}
															ajaxsend(send.funcName,send.areaid,send.type,'','',send.begin_time+'~'+send.end_time);
														}
													}else{
														if(send.funcName=='villagebasec'){
															send.type = send.village_id;
															if($('.month .on').html()!=null){
																month_ = $('.month .on').html();
															
																month_value = parseInt($('.month .on').html());
															}else{
																month_value='';
															}											
															ajaxsend(send.funcName,send.areaid,send.type,$('.year .on').html(),month_value);
														}else{
																
															window.top.msg(0,"时间段不能为空",true);
														}
													}
													
												}
												
                                                function ajaxsend(func,areaid,_type,year,month,period){
													var title='',time_title= '';
													if(func!='getuserc'&&func!='merc'&&func!='fanc'){
														$('#period').css('display', 'block');
														$('#period').css('visibility', 'visible');
														
														if(year!=null){
															title=year+'年';
														}
														if(month!=null){
															title+=month+'月';
														}
														if(period!=null&&period!='undefined'&&period!=''){
															periods=period.split('~');
															if(periods[0]==periods[1]){
																title=periods[0];
															}else{
																title=periods[0]+"至"+periods[1];
															}
														}else{
															title = year+'年';
															if(month){
																title+=month+'月';
															}
															period = '';
														}
														$('#funcName').val(func);
														$('#type').val(_type);
														$('#areaid').val(areaid);
													}else{
														$('#period').css('display', 'none');
													}
													
													if(func=='villagebasec'){
														$('.express').css('display','inline');
														type=$('#express_id').val();
													}else{
														$('.express').css('display','none');
													}
													
                                                    getyear(func,areaid,_type,year,month);
                                                    year=year.length==0?now_year:year;
                                                    getmonth(func,areaid,_type,year,month);
                                                    $('.mainnav_title ul a').removeClass('on');
                                                    $('#'+func).addClass('on');
                                                    if(year!=null){
                                                        $('.year').removeClass('on');
                                                        $('#'+func+year).addClass('on');
                                                    }
                                                    if(month!=null){
                                                        $('.month').removeClass('on');
                                                        $('#'+func+month).addClass('on');
                                                    }
                                                    $.ajax({
                                                        url:'/admin.php?g=System&c=Analysis&a='+func,
                                                        type:"post",
                                                        dataType:"JSON",
                                                        data: {area_id: areaid,type:_type,year:year,month:month,period:period},
                                                        beforeSend: function(){
                                                            $('#user_chart').empty();
                                                            $('#user_chart').append('<img src="/static/kindeditor/themes/common/loading.gif"/>');
                                                        },
                                                        success:function(date){
                                                            var chartXml='',chartXml3='';
                                                            var json=eval(date);
                                                            if(json.error.length>0){
                                                                alert(json.error);
                                                            }
															$('#note').html(date.note);
                                                            title+= json.area_pname;
															time_title = title;
                                                            if (json.msg!=null&&json.msg!='') {
															
																	$.each(json.msg,function(i,value){
																		var k_=new Array();
																		var link = '';
																	
																			
																			for(var k in value){
																				k_.push(k);
																			}
																			if(value[k_[3]]<3){
																				var link='link="JavaScript:ajaxsend(\''+func+'\','+value[k_[0]]+','+value[k_[3]]+','+year+',\''+month+'\',\''+period+'\')"';
																			}else if(func=='villagec'){
																				var link='link="JavaScript:ajaxsend(\''+func+'\','+value[k_[0]]+','+value[k_[3]]+','+year+',\''+month+'\',\''+period+'\')"';
																			}else{
																				var link='link="JavaScript:tips()"';
																			}
																			chartXml+='<set label="'+value[k_[1]]+'" value="'+value[k_[2]]+'" '+link+' />';
																		
																	});
																	charting(chartXml,title,'user_chart',prefix);
																
																
															}else if(func=='villagebasec'){
																if(json.village_express_list){
																	$.each(json.village_express_list,function(i,value){
																		chartXml3+='<set label="'+value.express_type+'" value="'+value.num+'"  />';
																	});
																}
																chartXml4='';
																if(json.village_user.phone!=0 || json.village_user.nophone!=0){
																	chartXml4+='<set label="已绑定平台手机" value="'+json.village_user.phone+'"  />';
																	chartXml4+='<set label="未绑定平台手机" value="'+json.village_user.nophone+'"  />';
																}
																
																chartXml5='';
																if(json.village_user.phone!=0 || json.village_user.nophone!=0){
																	chartXml5+='<set label="用户快递代收" value="'+json.sms_list.village_express+'"  />';
																	chartXml5+='<set label="用于访客登记" value="'+json.sms_list.village_vistor+'"  />';
																}
                                                            }else{
                                                                $('#user_chart').empty();
                                                            }
                                                            if (json.type_money!=null) {
                                                                var chartXml2='';
                                                                $.each(json.type_money,function(type,money){
                                                                    var t_=new Array();
                                                                    var link = '';
                                                                    for(var t in money){
                                                                        t_.push(t);
                                                                    }
                                                                        chartXml2+='<set label="'+money[t_[0]]+'" value="'+money[t_[2]]+'"  />';
                                                                });
                                                                $('#main').children('#chart2').remove();
                                                                $('#main').append('<div id="chart2" style="float:left"></div>');
                                                                charting(chartXml2,title,"chart2",prefix);
                                                            }else{
                                                                $('#main').children('#chart2').remove();
                                                            }
                                                            if (json.rank!=null&&json.rank!='') {
                                                                var html='<table class="gridtable">';
                                                                html+='<h3>消费排名前十</h3>';
                                                                $.each(json.rank,function(key,value){
                                                                    var k_=new Array();
                                                                    html+="<tr>";
                                                                    for(var k_ in value){
                                                                        if(!isNaN(value[k_])){
                                                                            html+="<td>"+value[k_]+"元</td>";
                                                                        }else{
                                                                            html+="<td>"+value[k_]+"</td>";
                                                                        }
                                                                    }
                                                                    html+="</tr>";
                                                                });
                                                                html+='</table>';
                                                                $('#rank').empty();
                                                                $('#rank').append(html);
                                                            }else{
                                                                $('#rank').empty();
                                                            }
                                                            if(func!='villagebasec'){
																if(json.msg.length==0){
																	$('#user_chart').empty();
																	$('#chart2').empty();
																	$('#user_chart').append("<div><font size='3px' color='red'>没有查询到数据</font></div>");
																}
															}
															
															if(func=='villagebasec'){
																$('#user_chart').empty();
																$('#chart2').empty();
																if(chartXml3){
																	charting(chartXml3,title+'快递统计',"user_chart",'');
																}else{
																	$('#user_chart').append("<div style='width:100%;text-align:center;'><font size='3' color='red'>没有查询到快递数据</font></div>");
																}
																var top_height = 30;
																
																if(chartXml4){
																	$('#main').children('#chart2').remove();
																	$('#main').append('<div id="chart2" style="float:left"></div>');
																	charting(chartXml4,time_title+'短信数量统计',"chart2",'');
																}else{
																	if(chartXml3){
																		var plus = top_height+400;
																	}
																	top_height = plus;
																	$('#main').children('#chart2').remove();
																	$('#main').append('<div id="chart2" style="float:left;text-align:center;width:600px;position: absolute;margin-top: '+plus+'px;"></div>');
																	$('#chart2').append("<font size='3' color='red'>没有查询到短信数量数据</font>");
																}
																
																
																if(chartXml5){
																	$('#main').children('#chart3').remove();
																	$('#main').append('<div id="chart3" style="float:left;"></div>');
																	charting(chartXml5,time_title+'业主数量统计',"chart3",'');
																}else{
																	plus += 30;
																	if(chartXml4){
																		var plus = top_height+460;
																	}
																	$('#main').children('#chart3').remove();
																	$('#main').append('<div id="chart3" style="float:left;text-align:center;width:600px;position: absolute;margin-top: '+plus+'px;"></div>');
																	$('#chart3').append("<font size='3' color='red'>没有查询到短信数量数据</font>");
																}
                                                                
																
																
                                                            }
                                                        }
                                                    });
                                                }
                                                function tips(){
                                                    window.top.msg(0,"向下没有数据了",true);
                                                }
                                                function charting(chartXml,title,at_where,prefix){
                                                    var chart_sex = new FusionCharts("{pigcms{$static_public}fushionCharts/Pie3D.swf", "ChartId", "600", "400", "0", "1");
                                                            chart_sex.setDataXML('<chart borderThickness="0" numberPrefix="'+prefix+'" formatNumberScale="0" caption="'+title+'" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">'
                                                            +chartXml+'</chart>');
                                                            chart_sex.render(at_where);
                                                }
                                                    
                                            </script>

                                        </div>
                                    </div>
                                
                            </div>
                    </div>
            </div>
<include file="Public:footer"/>

